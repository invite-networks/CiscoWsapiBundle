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

    public function checkCacheByHost($host, $type)
    {
        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $hostNS = $type . ':host:' . $host;
                if ($this->redis->exists($hostNS)) {
                    if ($this->redis->hexists($hostNS, 'registration.id')) {
                        $status = $this->redis->hget($hostNS, 'status');
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
        // TODO finish memcache logic

        return false;
    }

    public function checkCacheByRegId($regId, $type)
    {
        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $regNS = $type . ':registration:' . $regId;
                if ($this->redis->exists($regNS)) {
                    $host = $this->redis->get($regNS);
                    $hostNS = $type . ':host:' . $host;
                    if ($this->redis->exists($hostNS)) {
                        if ($this->redis->hexists($hostNS, 'registration.id')) {
                            $status = $this->redis->hget($hostNS, 'status');
                            if ($status === 'IN_SERVICE') {
                                return true;
                            }
                        }
                    }
                }
            } else {
                $msg = 'Redis service enabled but no service provided. ';
                $this->logger->emergency($msg . get_class($this));
                throw new LogicException($msg . get_class($this));
            }
        }
        // TODO finish memcache logic

        return false;
    }

    public function getCacheByHost($host, $type)
    {
        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $hostNS = $type . ':host:' . $host;
                if ($this->redis->exists($hostNS)) {
                    return $this->redis->hgetall($hostNS);
                }
            } else {
                $msg = 'Redis service enabled but no service provided. ';
                $this->logger->emergency($msg . get_class($this));
                throw new LogicException($msg . get_class($this));
            }
        }
        // TODO finish memcache logic

        return false;
    }

    public function getCacheByRegId($regId, $type)
    {
        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $regNS = $type . ':registration:' . $regId;
                if ($this->redis->exists($regNS)) {
                    $host = $this->redis->get($regNS);
                    $hostNS = $type . ':host:' . $host;
                    if ($this->redis->exists($hostNS)) {
                        return $this->redis->hgetall($hostNS);
                    }
                }
            } else {
                $msg = 'Redis service enabled but no service provided. ';
                $this->logger->emergency($msg . get_class($this));
                throw new LogicException($msg . get_class($this));
            }
        }
        // TODO finish memcache logic

        return false;
    }

    public function setCache(array$data, array$options = array())
    {
        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $ttl = array_key_exists('ttl', $data) ? $data['ttl'] : 130;
                $hostNS = $data['type'] . ':host:' . $data['host'];
                $this->redis->hmset($hostNS, array(
                    'host' => $data['host'],
                    'app.name' => $data['app.name'],
                    'app.url' => $data['app.url'],
                    'reg.id' => $data['registration.id'],
                    'provider.url' => $data['provider.url'],
                    'ttl' => $ttl,
                    'type' => $data['type'],
                    'status' => $data['status']
                ));
                if (array_key_exists('transaction.id', $data)) {
                    $this->redis->hset($hostNS
                            , 'transaction.id'
                            , $data['transaction.id']);
                }
                if (count($options) > 0) {
                    foreach ($options as $k => $v) {
                        $this->redis->hset($hostNS, $k, $v);
                    }
                }
                $regNS = $data['type'] . ':registration:' . $data['registration.id'];
                $this->redis->set($regNS, $data['host']);

                $this->redis->expire($regNS, $ttl);
                $this->redis->expire($hostNS, $ttl);
            } else {
                $msg = 'Redis service enabled but no service provided. ';
                $this->logger->emergency($msg . get_class($this));
                throw new LogicException($msg . get_class($this));
            }
        }
        // TODO finish memcache logic

        return array(
            'status' => 'success',
            'message' => 'Cache was successfully set for host ' . $data['host']
        );
    }

    public function deleteCacheByHost($host, $type)
    {
        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $hostNS = $type . ':host:' . $host;
                $this->redis->del($hostNS);
                return true;
            } else {
                $msg = 'Redis service enabled but no service provided. ';
                $this->logger->emergency($msg . get_class($this));
                throw new LogicException($msg . get_class($this));
            }
        }
        // TODO finish memcache logic

        return false;
    }

    public function deleteCacheByRegId($regId, $type)
    {
        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $regNS = $type . ':registration:' . $regId;
                if ($this->redis->exists($regNS)) {
                    $host = $this->redis->get($regNS);
                    $hostNS = $type . ':host:' . $host;
                    $this->redis->del($regNS, $hostNS);
                    return true;
                }
            } else {
                $msg = 'Redis service enabled but no service provided. ';
                $this->logger->emergency($msg . get_class($this));
                throw new LogicException($msg . get_class($this));
            }
        }
        // TODO finish memcache logic

        return false;
    }

}
