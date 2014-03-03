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

class WsapiProbingEvent extends Event
{

    /**
     * @var array orginal probe data
     */
    protected $probeRequest = array();

    /**
     * @var mixed prob response to provider
     */
    protected $probeResponse;

    /**
     * @var string probe type: xcc, xsvc or xcdr
     */
    protected $type;

    public function __construct($probeRequest, $type)
    {
        $this->probeRequest = $probeRequest;
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getProbeType()
    {
        return $this->type;
    }

    /**
     * @return std Object
     */
    public function getMsgHeader()
    {
        return $this->probeRequest['msgHeader'];
    }

    /**
     * @return string
     */
    public function getTranactionId()
    {
        return $this->probeRequest['msgHeader']->tranactionID;
    }

    /**
     * @return string
     */
    public function getRegistrationId()
    {
        return $this->probeRequest['msgHeader']->registrationID;
    }

    /**
     * @return string
     */
    public function getApplicationData()
    {
        return $this->statusRequest['applicationData'];
    }

    /**
     * @return string
     */
    public function getProviderStatus()
    {
        return $this->statusRequest['providerStatus'];
    }

    /**
     * @return array
     */
    public function getProbeRequest()
    {
        return $this->probeRequest;
    }

    /**
     * @param array Probing response data
     */
    public function setProbeResponse($probeResponse)
    {
        $this->probeResponse = $probeResponse;
    }

}
