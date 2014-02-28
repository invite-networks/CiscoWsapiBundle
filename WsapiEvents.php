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
namespace Invite\Bundle\Cisco\WsapiBundle;

/**
 * Contains all events thrown in the WsapiBundle
 */
final class WsapiEvents
{
    /**
     * The XCDR_PROBING event occurs when an SolicitXcdrProbing is recieved 
     * from the cisco wsapi.
     *
     * This event allows you take actions in your app based on the status type.
     * Receives an Invite\Bundle\Cisco\WsapiBundle\Event\XcdrProbingEvent instance.
     */

    const XCDR_PROBING = 'cisco_wsapi.xcdr.probing';

    /**
     * The XCDR_STATUS event occurs when an NotifyXcdrStatus is recieved from the cisco wsapi.
     *
     * This event allows you take actions in your app based on the status type.
     * Receives an Invite\Bundle\Cisco\WsapiBundle\Event\XcdrStatusEvent instance.
     */
    const XCDR_STATUS = 'cisco_wsapi.xcdr.status';

    /**
     * The XCDR_UNREGISTER event occurs when an SolicitXcdrProviderUnRegister is recieved 
     * from the cisco wsapi.
     *
     * This event allows you take actions in your app based on the status type.
     * Receives an Invite\Bundle\Cisco\WsapiBundle\Event\XcdrUnregisterEvent instance.
     */
    const XCDR_UNREGISTER = 'cisco_wsapi.xcdr.unregister';

    /**
     * The XCDR_RECORD event occurs when an NotifyXcdrRecord is recieved for the cisco wsapi.
     *
     * This event allows you take actions in your app based on the status type.
     * Receives an Invite\Bundle\Cisco\WsapiBundle\Event\XcdrRecordEvent instance.
     */
    const XCDR_RECORD = 'cisco_wsapi.xcdr.record';

}
