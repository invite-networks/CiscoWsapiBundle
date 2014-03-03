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
namespace Invite\Bundle\Cisco\WsapiBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Invite\Component\Cisco\Wsapi\Request\WsapiRequestInterface;

class WsapiStatusEvent extends Event
{

    /**
     * @var \Invite\Component\Cisco\Wsapi\Request\WsapiRequestInterface
     */
    protected $statusRequest;
    protected $responseXml;

    public function __construct(WsapiRequestInterface $statusRequest, $responseXml = null)
    {
        $this->statusRequest = $statusRequest;
        $this->responseXml = $responseXml;
    }

    /**
     * @return array
     */
    public function getStatusRequest()
    {
        return $this->statusRequest;
    }

    /**
     * @return array
     */
    public function getStatusResponse()
    {
        return $this->responseXml;
    }

    /**
     * @param string xml string to send
     */
    public function setStatusResponse($responseXml)
    {
        $this->responseXml = $responseXml;
    }

}
