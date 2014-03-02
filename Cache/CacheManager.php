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
use Predis\Client as PredisClient;

/**
 * INVITE Cisco WsApi Cache Manager
 *
 * @category   CDR
 * @author     Josh Whiting <josh@invitenetworks.com>
 * @version    Release: @package_version@
 * @since      Class available since Release 1.1.0
 */
class CacheManager
{

    /**
     * @var \Predis\Client
     */
    protected $redis;

    /**
     * @var Memcache client
     */
    protected $memcache;

    /**
     * @array Xcdr client options
     */
    protected $options = array();

    /**
     * Xcdr Soap client construct.
     * 
     * @param array of wsapi client parameters $options
     * @param \Predis\Client $redis
     */
    public function __construct($options, PredisClient $redis = null, $memcache = null)
    {
        $this->redis = $redis;
        $this->memcache = $memcache;
        $this->options = $options;
    }

    public function checkCache($host, $type)
    {
        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $ns = $type . ':' . $host;
                if ($this->redis->exists($ns)) {
                    if ($this->redis->hexists($ns, 'reg.id')) {
                        if ($this->redis->get($ns, 'status' === 'IN_SERVICE')) {
                            return true;
                        }
                    }
                }
            } else {
                $msg = 'Redis service enabled but no service provided.';
                throw new LogicException($msg);
            }
        }
        if ($this->options['memcache_enabled']) {
            // TODO finish memcache logic
        }

        // TODO write to disk cache?
        return false;
    }

    public function setCache($host, $route, $result, $type, $tId = null, $extras = array())
    {
        $msgHdr = $result['msgHeader'];

        if ($tId) {
            if ($tId !== $msgHdr->transactionID) {
                return array(
                    'status' => 'error',
                    'type' => 'cache',
                    'message' => 'transactionIDs do not match'
                );
            }
        }

        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $ns = $type . ':' . $host;
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
                $this->redis->expire($ns, 3600);
            } else {
                $msg = 'Redis service enabled but no service provided.';
                throw new LogicException($msg);
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

}
