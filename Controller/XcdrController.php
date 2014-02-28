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
    public function apiAction()
    {
        $xcdrServer = $this->container->get('cisco_wsapi.xcdr_server');

        $response = new Response();
        $response->headers->set('Content-Type', 'application/soap+xml');
        $response->setContent($xcdrServer->processXcdr());

        return $response;
    }

}
