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

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Implementing class must provide method to
     * process probing updates.
     * 
     * @param array $data
     */
    public function processProbing($data)
    {
        $probingEvent = new XcdrProbingEvent($data);
        $this->dispatcher->dispatch(
                WsapiEvents::XCDR_PROBING, $probingEvent)
        ;
    }

    /**
     * Implementing class must provide method to
     * process status update.
     * 
     * @param array $data
     */
    public function processStatus($data)
    {
        $statusEvent = new XcdrStatusEvent($data);
        $this->dispatcher->dispatch(
                WsapiEvents::XCDR_STATUS, $statusEvent)
        ;
    }

    /**
     * Implementing class must provide method to
     * process unregister msg.
     * 
     * @param array $data Must be md array with csv key.
     */
    public function processUnRegister($data)
    {
        $unregisterEvent = new XcdrUnregisterEvent($data);
        $this->dispatcher->dispatch(
                WsapiEvents::XCDR_UNREGISTER, $unregisterEvent)
        ;
    }

    /**
     * Implementing class must provide method to
     * set csv data in class.
     * 
     * @param array $data Must be md array with csv key.
     */
    public function processCdrRecord($data)
    {
        $recordEvent = new XcdrRecordEvent($data);
        $this->dispatcher->dispatch(
                WsapiEvents::XCDR_RECORD, $recordEvent)
        ;
    }

}
