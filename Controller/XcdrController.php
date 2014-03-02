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
 * XcdrController.
 *
 * @author Josh Whiting <josh@invitenetworks.com>
 */
class XcdrController extends ContainerAware
{

    /**
     * Xcdr Soap Webservice action.
     */
    public function serverAction()
    {
        $xcdrServer = $this->container->get('cisco_wsapi.xcdr_server');

        $response = new Response();
        $response->headers->set('Content-Type', 'application/soap+xml');

        $result = $xcdrServer->processXcdr();

        if ($result['status'] === 'error') {
            $response->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE);
            $msg = 'XCDR Conroller: ' . $result['message'];
            $response->setContent($msg);
            $logger = $this->container->get('logger');
            $logger->error('XCDR:CONTROLLER: SOAP SERVER ERROR: ' . $msg);
        } else {
            $response->setContent($result['result']);
        }

        return $response;
    }

}
