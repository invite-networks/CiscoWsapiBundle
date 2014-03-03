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
namespace Invite\Bundle\Cisco\WsapiBundle\EventListener;

use Invite\Bundle\Cisco\WsapiBundle\WsapiEvents;
use Invite\Bundle\Cisco\WsapiBundle\Event\XcdrRecordEvent;
use Invite\Bundle\Cisco\WsapiBundle\EventListener\WsapiListener;
use Invite\Component\Cisco\Wsapi\Request\XcdrRequest;

/**
 * XcdrListener service
 * 
 * Listens for Xcdr api events from wsapi library and
 * sets the appropriate Symfony event.
 */
class XcdrListener extends WsapiListener
{

    /**
     * Dispatch Xcdr NotifyRecord Event.
     * 
     * @param array $data Must be md array with csv key.
     */
    public function processRecord(XcdrRequest $recordRequest)
    {
        $recordEvent = new XcdrRecordEvent($recordRequest);
        $this->dispatcher->dispatch(
                WsapiEvents::XCDR_RECORD, $recordEvent
        );
    }

}
