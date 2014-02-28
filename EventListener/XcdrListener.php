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

use Symfony\Component\EventDispatcher\EventDispatcher;
use Invite\Component\Cisco\Wsapi\Model\XcdrListenerInterface;
use Invite\Bundle\Cisco\WsapiBundle\WsapiEvents;
use Invite\Bundle\Cisco\WsapiBundle\Event\XcdrProbingEvent;
use Invite\Bundle\Cisco\WsapiBundle\Event\XcdrStatusEvent;
use Invite\Bundle\Cisco\WsapiBundle\Event\XcdrUnregisterEvent;
use Invite\Bundle\Cisco\WsapiBundle\Event\XcdrRecordEvent;

/**
 * XcdrListener service
 * 
 * Listens for Xcdr api events from wsapi library and
 * sets the appropriate Symfony event.
 */
class XcdrListener implements XcdrListenerInterface
{

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    private $dispatcher;
    private $logger;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     */
    public function __construct(EventDispatcher $dispatcher, $logger = null)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }

    /**
     * Dispatch Xcdr Probing Event.
     * 
     * @param array $data
     */
    public function processProbing($data)
    {
        $probingEvent = new XcdrProbingEvent($data);
        $this->dispatcher->dispatch(
                WsapiEvents::XCDR_PROBING, $probingEvent
        );
    }

    /**
     * Dispatch Xcdr Status Event.
     * 
     * @param array $data
     */
    public function processStatus($data)
    {
        $statusEvent = new XcdrStatusEvent($data);
        $this->dispatcher->dispatch(
                WsapiEvents::XCDR_STATUS, $statusEvent
        );
    }

    /**
     * Dispatch Xcdr UnRegister Event.
     * 
     * @param array $data Must be md array with csv key.
     */
    public function processUnRegister($data)
    {
        $unregisterEvent = new XcdrUnregisterEvent($data);
        $this->dispatcher->dispatch(
                WsapiEvents::XCDR_UNREGISTER, $unregisterEvent
        );
    }

    /**
     * Dispatch Xcdr NotifyRecord Event.
     * 
     * @param array $data Must be md array with csv key.
     */
    public function processCdrRecord($data)
    {
        // $this->logger->critical('XcdrListener: I saw the record here...');
        // var_dump($data);

        $recordEvent = new XcdrRecordEvent($data);
        $this->dispatcher->dispatch(
                WsapiEvents::XCDR_RECORD, $recordEvent
        );
    }

}
