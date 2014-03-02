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
namespace Invite\Bundle\Cisco\WsapiBundle\Client;

use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\Routing\Router;
use Invite\Component\Cisco\Wsapi\Client\XcdrClient as BaseXcdrClient;

/**
 * INVITE Cisco WsApi XCDR Client
 *
 * @category   CDR
 * @author     Josh Whiting <josh@invitenetworks.com>
 * @version    Release: @package_version@
 * @since      Class available since Release 1.1.0
 */
class XcdrClient
{

    /**
     * @var \Symfony\Component\Routing\Router
     */
    protected $router;

    /**
     * @var Redis client
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
     * @param \Symfony\Component\Routing\Router $router
     * @param array of client setup parameters $options
     */
    public function __construct(Router $router, $options, $redis = null, $memcache = null)
    {
        $this->router = $router;
        $this->redis = $redis;
        $this->memcache = $memcache;
        $this->options = $options;
    }

    public function register($host, $extras = array(), $url = null)
    {
        if ($this->checkCache($host)) {
            return array('status' => $host . ' is already registered with XCDR Provider');
        }

        if (!$url) {
            $context = $this->router->getContext();
            $context->setHost($this->optons['app_host']);
            $context->setScheme($this->optons['app_scheme']);

            if ($this->optons['app_scheme'] === 'https') {
                $context->setHttpsPort($this->optons['app_port']);
            } else {
                $context->setHttpPort($this->optons['app_port']);
            }
            $route = $this->router->generate('cisco_wsapi.xcdr_app', array(), true);
        } else {
            $route = $url;
        }

        $xcdrClient = new BaseXcdrClient();
        $this->options['transactionId'] = uniqid('xcdr');
        $result = $xcdrClient->requestXcdrRegister($host, $route, $this->options);
        $this->setCache($host, $route, $result, $extras);

        return array(
            'status' => $host . ' registered successfully!',
            'result' => $result
        );
    }

    public function checkCache($host)
    {
        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $ns = 'xcdr:' . $host;
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

    public function setCache($host, $route, $result, $extras)
    {
        $msgHdr = $result['msgHeader'];
        $transactionId = $this->options['transactionId'];

        if ($transactionId !== $msgHdr->transactionID) {
            $msg = 'transactionIDs dont match';
            if ($this->redis) {
                $this->redis->set('log:xcdr:client', $msg);
            } elseif ($this->memcache) {
                $this->redis->set('log:xcdr:client', $msg);
            }
            return $msg;
        }

        if ($this->options['redis_enabled']) {
            if ($this->redis) {
                $ns = 'xcdr:' . $host;
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
        return;
    }

}
