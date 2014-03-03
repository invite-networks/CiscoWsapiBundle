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

    public function __construct(WsapiRequestInterface $statusRequest)
    {
        $this->statusRequest = $statusRequest;
    }

    /**
     * @return array
     */
    public function getStatusRequest()
    {
        return $this->statusRequest;
    }

}
