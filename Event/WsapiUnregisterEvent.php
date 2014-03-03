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

    public function __construct(WsapiRequestInterface $unregisterRequest)
    {
        $this->unregisterRequest = $unregisterRequest;
    }

    /**
     * @return array
     */
    public function getUnregisterRequest()
    {
        return $this->unregisterRequest;
    }

}
