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
use Invite\Bundle\Cisco\WsapiBundle\Cache\CacheManager;

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
     * @var \Invite\Bundle\Cisco\WsapiBundle\Cache\CacheManager
     */
    protected $cacheManager;

    /**
     * @array Xcdr client options
     */
    protected $options = array();

    /**
     * Xcdr Soap client construct.
     * 
     * @param \Symfony\Component\Routing\Router $router
     * @param \Invite\Bundle\Cisco\WsapiBundle\Cache\CacheManager $cacheManager
     * @param array of client setup parameters $options
     */
    public function __construct(Router $router, CacheManager $cacheManager, $options)
    {
        $this->router = $router;
        $this->cacheManager = $cacheManager;
        $this->options = $options;
    }

    public function register($host, $extras = array(), $url = null)
    {
        if ($this->cacheManager->checkCache($host, 'xcdr')) {
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
        $tId = uniqid('xcdr');
        $this->options['transactionId'] = $tId;
        $result = $xcdrClient->requestXcdrRegister($host, $route, $this->options);
        $cache = $this->setCache($host, $route, $result, 'xcdr', $tId, $extras);

        if ($cache['status'] === 'error') {
            $cache['result'] = $result;
            return $cache;
        }

        return array(
            'status' => $host . ' registered successfully!',
            'result' => $result
        );
    }

}
