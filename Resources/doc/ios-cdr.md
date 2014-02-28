INVITE WEBSERVICES IOS UC GATEWAY API CDR RECORD DOCUMENTATION
==============================================================

This bundle provides the logic to consume the Cisco IOS UC Gateway API thru SOAP based RPC calls.
It is paired with it's sister bundle "InviteIosRestBundle" to provide an full integration to INVITE WebServices.


OVERVIEW
========

    * The Cisco UC Gateway API URL:
        http://www.cisco.com/en/US/docs/ios/voice/cdr/developer/guide/cdrcsv.html#wp1172378


XCDR Register Console Command:
    php app/console ios_xcdr:register


IOS CDR COMPACT VALUES
======================

    [0] =>  [unix_time]              - Long, System time stamp when CDR is captured. ex. 1371793181
    [1] =>  [call_id]                - Long, Value of the Call-ID header. ex. 4320
    [2] =>  [cdr-type]*              - Long, Template used: 0=None, 1=Call history detail, 2=Custom template. ex. 1
    [3] =>  [leg-type]*              - Long, Call leg type: 1= Telephony, 2=VoIP, 3=MMOIP, 4=Frame Relay, 5=ATM. ex. 2
    [4] =>  [h323-conf-id]           - String, Unique callId to bill seperate events in same call. ex. CA615B46 D96B11E2 8F499C9C 52CD806F
    [5] =>  [peer-address]           - String, Number that this call was connected to in E.164 format. ex. 8809
    [6] =>  [peer-sub-address]       - String, Subaddress configured under a dial peer. ex. LOOKUP
    [7] =>  [h323-setup-time]        - String, Setup time in Network Time Protocol (NTP) format: hour, minutes, seconds, microseconds, time_zone, day, month, day_of_month, year. ex. *05:39:35.468 UTC Fri Jun 21 2013
    [8] =>  [alert-time]             - String, Time at which call is alerting. ex. *05:39:36.868 UTC Fri Jun 21 2013
    [9] =>  [h323-connect-time]      - String, Connect time in NTP format: hour, minutes, seconds, microseconds, time_zone, day, month, day_of_month, year. ex. *05:39:36.978 UTC Fri Jun 21 2013
    [10] => [h323-disconnect-time]   - String, Disconnect time in NTP format: hour, minutes, seconds, microseconds, time_zone, day, month, day_of_month, year. ex. *05:39:41.548 UTC Fri Jun 21 2013
    [11] => [h323-disconnect-cause]* - String, Q.931 disconnect cause code retrieved from Cisco IOS call-control application programming interface (Cisco IOS CCAPI). ex. 10
    [12] => [disconnect-text]        - String, ASCII text describing the reason for call termination. ex. normal call clearing (16)
    [13] => [h323-call-origin]       - String, Gateway's connection that is active for this leg. answer=Legs 1&3, originate=Legs 2&4, callback=Legs 1&3. ex. originate
    [14] => [charged-units]*         - Long, Number of charged units for this connection. If incoming or null is zero. ex. 0
    [15] => [info-type]*             - String, Type of information carried by media. 1=Other 9 not described, 2=Speech, 3=UnrestrictedDigital, 4=UnrestrictedDigital56, 5=RestrictedDigital, 6=audio31, 7=Audio7, 8=Video, 9=PacketSwitched ex. LOOKUP
    [16] => [paks-out]*              - Long, Total number of transmitted packets. ex. 281
    [17] => [bytes-out]*             - Long, Total number of transmitted bytes. ex. 44960
    [18] => [paks-in]*               - Long, Total number of packets received. ex. 295
    [19] => [bytes-in]*              - Long, Total number of bytes received. ex. 47200
    [20] => [username]*              - String, Username for authentication. Usually this is the same as the calling number. ex. 8016804400
    [21] => [clid]                   - String, Calling number. ex. 8016804400
    [22] => [dnis]                   - String, Called number. ex. 8809
    <<<<< Feature VSA Codes >>>>>
    [23] => [fn:]                    - String, Feature name. CFA = Call fwd all, CFBY = Call fwd busy, CFNA = Call fwd no answer, BXFER = Blind transfer, CXFER = Consult transfer, HOLD = Call hold, RESUME = Call resume, TWC = Two-way call.
    [24] => [ft:]                    - Feature operation time. Time stamp of the operation start and stop time, if applicable for a specific feature. ex. 06/21/2013 05:39:35.466
    [25] => [calling number]         - 8016804400
    [26] => [called number]          - 8809
    [27] => [frs:]                   - Feature status. Success (0) or failure (1). Always set to 0 for Hold and Resume. ex. 0
    [28] => [fid:]                   - Integer, Feature ID of the invocation. Identifies a unique instance of a feature. This number is incremented. ex. 206
    [29] => [fcid:]                  - Feature correlation ID. ex. CA615B46 D96B11E2 8F499C9C 52CD806F
    [30] => [legID:]                 - Integer, Call leg ID. Each feature VSA is added to a call leg and it captures the call leg ID. ex. 10E0
    [31] =>
    [32] =>
    [33] =>
    [34] =>

    * Not used in INVITE API.