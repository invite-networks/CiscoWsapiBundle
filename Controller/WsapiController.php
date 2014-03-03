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
use Symfony\Component\HttpFoundation\Request;
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
    public function xcdrAction(Request $request)
    {
        $wsapiServer = $this->container->get('cisco_wsapi.server');
        $listener = $this->container->get('cisco_wsapi.xcdr_listener');

        $options = array(
            'host' => $request->getClientIP(),
            'route' => $request->getUri(),
            'customer' => 'stevens-henegar-college' // How do i get this?
        );

        $response = new Response();
        $response->headers->set('Content-Type', 'application/soap+xml');
        $result = $wsapiServer->processXcdr($listener, $options);

        if ($result['status'] === 'error') {
            $response->setStatusCode(Response::HTTP_SERVICE_UNAVAILABLE);
            $response->setContent($result['message']);
            $logger = $this->container->get('logger');
            $logger->alert($request->getClientIP() . ' : '
                    . $result['message'] . ' : '
                    . $result['class']);
        } else {
            $response->setContent($result['result']);
        }

        return $response;
    }

}
