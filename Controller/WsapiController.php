<?php
/*
 * This file is part of the Invite Wsapi Bundle
 *
 * (c) Invite Networks Inc. <info@invitenetworks.com>
 *
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */
namespace Invite\Bundle\Cisco\WsapiBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;

/**
 * WsapiController.
 *
 * @author Josh Whiting <josh@invitenetworks.com>
 */
class WsapiController extends ContainerAware
{

    /**
     * Xcc Soap Webservice action.
     */
    public function xccAction()
    {
        return;
    }

    /**
     * Xsvc Soap Webservice action.
     */
    public function xsvcAction()
    {
        return;
    }

    /**
     * Xcdr Soap Webservice action.
     */
    public function xcdrAction()
    {
        $xcdrServer = $this->container->get('cisco_wsapi.xcdr_server');
        $response = new Response();
        $response->headers->set('Content-Type', 'application/soap+xml');
        $result = $xcdrServer->processXcdr();

        if ($result['status'] === 'error') {
            $response->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE);
            $response->setContent($result['message']);
            $host = $this->container->get('request')->getClientIp();
            $logger = $this->container->get('logger');
            $logger->alert($host . ' : ' . $result['message'] . ' : ' . $result['class']);
        } else {
            $response->setContent($result['result']);
        }

        return $response;
    }

}
