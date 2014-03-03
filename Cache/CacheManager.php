<?php
/*
 * This file is part of the Invite Wsapi Library
 * 
 * (c) Invite Networks Inc. <info@invitenetworks.com>
 *
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace Invite\Bundle\Cisco\WsapiBundle\Cache;

use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Bridge\Monolog\Logger;
use Predis\Client as PredisClient;

/**
 * INVITE Cisco WsApi Cache Manager
 *
 * @category   API
 * @author     Josh Whiting <josh@invitenetworks.com>
 * @version    Release: @package_version@
 * @since      Class available since Release 1.1.0
 */
class CacheManager
{

    /**
     * @array Xcdr client options
     */
    protected $options = array();

    /**
     * @var \Predis\Client
     */
    protected $redis;

    /**
     * @var Memcache client
     */
    protected $memcache;

    /**
     * @var Memcache client
     */
    protected $logger;

    /**
     * Xcdr Soap client construct.
     * 
     * @param array of wsapi client parameters $options
     * @param \Predis\Client $redis
     */
    public function __construct($options, Logger $logger, PredisClient $redis = null, $memcache = null)
    {
        $this->options = $options;
        $this->logger = $logger;
        $this->redis = $redis;
        $this->memcache = $memcache;
    }

    public function checkCache($host, $type)
    {
        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $ns = $type . ':registration:' . $host;
                if ($this->redis->exists($ns)) {
                    if ($this->redis->hexists($ns, 'reg.id')) {
                        $status = $this->redis->hget($ns, 'status');
                        if ($status === 'IN_SERVICE') {
                            return true;
                        }
                    }
                }
            } else {
                $msg = 'Redis service enabled but no service provided. ';
                $this->logger->emergency($msg . get_class($this));
                throw new LogicException($msg . get_class($this));
            }
        }
        if ($this->options['memcache_enabled']) {
            // TODO finish memcache logic
        }

        // TODO write to disk cache?
        return false;
    }

    public function getCache($host, $type)
    {
        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $ns = $type . ':registration:' . $host;
                if ($this->redis->exists($ns)) {
                    return $this->redis->hgetall($ns);
                }
            } else {
                $msg = 'Redis service enabled but no service provided. ';
                $this->logger->emergency($msg . get_class($this));
                throw new LogicException($msg . get_class($this));
            }
        }
        if ($this->options['memcache_enabled']) {
            // TODO finish memcache logic
        }

        // TODO write to disk cache?
        return false;
    }

    public function setCache($host, $route, $result, $type, $tId = null, $ttl = 130, $extras = array())
    {
        $msgHdr = $result['msgHeader'];

        if ($tId) {
            if ($tId !== $msgHdr->transactionID) {
                $msg = 'Redis service enabled but no service provided. ';
                $this->logger->alert($msg . get_class($this));
                return array(
                    'status' => 'error',
                    'type' => 'cache',
                    'message' => $msg,
                    'class' => get_class($this)
                );
            }
        }

        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $ns = $type . ':registration:' . $host;
                $this->redis->hmset($ns, array(
                    'app.route' => $route,
                    'reg.id' => $msgHdr->registrationID,
                    'status' => $result['providerStatus']
                ));
                if (count($extras) > 0) {
                    foreach ($extras as $k => $v) {
                        $this->redis->hset($ns, $k, $v);
                    }
                }
                $this->redis->expire($ns, $ttl);
            } else {
                $msg = 'Redis service enabled but no service provided. ';
                $this->logger->emergency($msg . get_class($this));
                throw new LogicException($msg . get_class($this));
            }
        }
        if ($this->options['memcache_enabled']) {
            // TODO finish memcache logic
        }

        // TODO write to disk cache?
        return array(
            'status' => 'success',
            'message' => 'Cache was successfully set for host ' . $host
        );
    }

    public function deleteCache($host, $type)
    {
        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $ns = $type . ':registration:' . $host;
                if ($this->redis->exists($ns)) {
                    $this->redis->del($ns);
                    return true;
                }
            } else {
                $msg = 'Redis service enabled but no service provided. ';
                $this->logger->emergency($msg . get_class($this));
                throw new LogicException($msg . get_class($this));
            }
        }
        if ($this->options['memcache_enabled']) {
            // TODO finish memcache logic
        }

        // TODO write to disk cache?
        return false;
    }

}
