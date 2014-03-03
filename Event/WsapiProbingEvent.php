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

class WsapiProbingEvent extends Event
{

    /**
     * @var \Invite\Component\Cisco\Wsapi\Request\WsapiRequestInterface
     */
    protected $probingRequest;
    protected $responseXml;

    public function __construct(WsapiRequestInterface $probingRequest, $responseXml)
    {
        $this->probingRequest = $probingRequest;
        $this->responseXml = $responseXml;
    }

    /**
     * @return object
     */
    public function getProbingRequest()
    {
        return $this->probingRequest;
    }

    /**
     * @return array
     */
    public function getProbingResponse()
    {
        return $this->responseXml;
    }

    /**
     * @param string xml string to send
     */
    public function setProbingResponse($responseXml)
    {
        $this->responseXml = $responseXml;
    }

}
