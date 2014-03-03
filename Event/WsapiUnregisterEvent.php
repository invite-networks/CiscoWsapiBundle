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

class WsapiUnregisterEvent extends Event
{

    /**
     * @var \Invite\Component\Cisco\Wsapi\Request\WsapiRequestInterface
     */
    protected $unregisterRequest;
    protected $responseXml;

    public function __construct(WsapiRequestInterface $unregisterRequest, $responseXml)
    {
        $this->unregisterRequest = $unregisterRequest;
        $this->responseXml = $responseXml;
    }

    /**
     * @return array
     */
    public function getUnregisterRequest()
    {
        return $this->unregisterRequest;
    }

    /**
     * @return array
     */
    public function getUnregisterResponse()
    {
        return $this->responseXml;
    }

    /**
     * @param string xml string to send
     */
    public function setUnregisterResponse($responseXml)
    {
        $this->responseXml = $responseXml;
    }

}
