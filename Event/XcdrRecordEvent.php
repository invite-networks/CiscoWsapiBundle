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
use Invite\Component\Cisco\Wsapi\Request\XcdrRequest;

class XcdrRecordEvent extends Event
{

    /**
     * @var \Invite\Component\Cisco\Wsapi\Request\XcdrRequest
     */
    protected $recordRequest;
    protected $responseXml;

    public function __construct(XcdrRequest $recordRequest, $responseXml = null)
    {
        $this->recordRequest = $recordRequest;
        $this->responseXml = $responseXml;
    }

    /**
     * @return array
     */
    public function getRecordRequest()
    {
        return $this->recordRequest;
    }

    /**
     * @return array
     */
    public function getRecordResponse()
    {
        return $this->responseXml;
    }

    /**
     * @param string xml string to send
     */
    public function setRecordResponse($responseXml)
    {
        $this->responseXml = $responseXml;
    }

}
