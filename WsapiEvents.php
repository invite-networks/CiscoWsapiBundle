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
     * The WSAPI_PROBING event occurs when an SolicitxxxProbing is recieved 
     * from the cisco wsapi.
     *
     * This event allows you take actions in your app based on the status type.
     * Receives an Invite\Bundle\Cisco\WsapiBundle\Event\WsapiProbingEvent instance.
     */

    const WSAPI_PROBING = 'cisco_wsapi.probing.event';

    /**
     * The WSAPI_STATUS event occurs when an NotifyxxxStatus is recieved from the cisco wsapi.
     *
     * This event allows you take actions in your app based on the status type.
     * Receives an Invite\Bundle\Cisco\WsapiBundle\Event\WsapiStatusEvent instance.
     */
    const WSAPI_STATUS = 'cisco_wsapi.status.event';

    /**
     * The WSAPI_UNREGISTER event occurs when an SolicitxxxProviderUnRegister is recieved 
     * from the cisco wsapi.
     *
     * This event allows you take actions in your app based on the status type.
     * Receives an Invite\Bundle\Cisco\WsapiBundle\Event\WsapiUnregisterEvent instance.
     */
    const WSAPI_UNREGISTER = 'cisco_wsapi.unregister.event';

    /**
     * The XCDR_RECORD event occurs when an NotifyXcdrRecord is recieved for the cisco wsapi.
     *
     * This event allows you take actions in your app based on the status type.
     * Receives an Invite\Bundle\Cisco\WsapiBundle\Event\XcdrRecordEvent instance.
     */
    const XCDR_RECORD = 'cisco_wsapi.xcdr.record.event';

}
