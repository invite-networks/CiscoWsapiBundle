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

use Symfony\Component\Routing\Router;
use Symfony\Bridge\Monolog\Logger;
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
    protected $cm;

    /**
     * @array Xcdr client options
     */
    protected $options = array();

    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    protected $logger;

    /**
     * Xcdr Soap client construct.
     * 
     * @param \Symfony\Component\Routing\Router $router
     * @param \Invite\Bundle\Cisco\WsapiBundle\Cache\CacheManager $cm
     * @param array of client setup parameters $options
     * @param \Symfony\Bridge\Monolog\Logger $logger
     */
    public function __construct(Router $router, CacheManager $cm, $options, Logger $logger)
    {
        $this->router = $router;
        $this->cm = $cm;
        $this->options = $options;
        $this->logger = $logger;
    }

    public function register($host, $extras = array(), $url = null)
    {
        if ($this->cm->checkCacheByHost($host, 'xcdr')) {
            $msg = $host . ' is already registered with XCDR Provider ';
            return array(
                'status' => 'success',
                'message' => $msg
            );
            $this->logger->info($msg);
        }

        if (!$url) {
            $context = $this->router->getContext();
            $context->setHost($this->options['app_host']);
            $context->setScheme($this->options['app_scheme']);

            if ($this->options['app_scheme'] === 'https') {
                $context->setHttpsPort($this->options['app_port']);
            } else {
                $context->setHttpPort($this->options['app_port']);
            }
            $route = $this->router->generate('cisco_wsapi.xcdr', array(), true);
        } else {
            $route = $url;
        }

        $xcdrClient = new BaseXcdrClient();
        $this->options['transactionID'] = uniqid('xcdr');

        $result = $xcdrClient->requestXcdrRegister($host, $route, $this->options);

        if ($result['status'] === 'error') {
            $this->logger->alert($result['message'] . ' ' . $result['class']);
            return $result;
        }

        $data = $result['result'];
        $data['host'] = $host;
        $data['registration.id'] = $data['msgHeader']->registrationID;
        $data['transaction.id'] = $data['msgHeader']->transactionID;
        $data['status'] = $data['providerStatus'];
        $data['type'] = 'xcdr';

        $cache = $this->cm->setCache($data, $extras);

        if ($cache['status'] === 'error') {
            // Already logged in CacheManager
            return $cache;
        }

        return array(
            'status' => 'success',
            'message' => $host . ' registered successfully!',
            'result' => $data
        );
    }

}
