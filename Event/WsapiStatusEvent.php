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

class WsapiStatusEvent extends Event
{

    /**
     * @var array orginal status data
     */
    protected $statusRequest = array();

    /**
     * @var mixed status response to provider
     */
    protected $statusResponse;

    /**
     * @var string probe type: xcc, xsvc or xcdr
     */
    protected $type;

    public function __construct($statusRequest, $type)
    {
        $this->statusRequest = $statusRequest;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getStatusType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getStatusRequest()
    {
        return $this->statusRequest;
    }

    /**
     * @param mixed Status response data
     */
    public function setStatusResponse($statusResponse)
    {
        $this->statusResponse = $statusResponse;
    }

}
