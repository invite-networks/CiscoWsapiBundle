INVITE WEBSERVICES IOS UC GATEWAY API BUNDLE DOCUMENTATION
==========================================================

This bundle provides the logic to consume the Cisco IOS UC Gateway API thru SOAP based RPC calls.
It is paired with it's sister bundle "InviteIosRestBundle" to provide an full integration to INVITE WebServices.


OVERVIEW
========

    * The Cisco UC Gateway API "wsapi" has 3 listening providers for different services:
        1. XCC Provider:
        2. XSVC Provider:
        3. XCDR Prodiver:


BeSimple Sample SOAP Configurations
===================================
$soapClientBuilder = $this->get('besimple.soap.client.builder.myname');
$soapClient = $soapClientBuilder->build();
$sopaClient->WebServiceMethodName();

----------------------------------

use BeSimple\SoapBundle\Soap\SoapHeader;

$response = new SoapResponse();
$response->addSoapHeader(new SoapHeader($namespace, $name, $data));

return $response;

----------------------------------





SOAP API XML Format
===================

XSVC PROVIDER
+++++++++++++

http://97.75.170.77:8090/cisco_xsvc [POST]
Content-Type: application/soap+xml


Register Request
~~~~~~~~~~~~~~~~

<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://www.w3.org/2003/05/soap-envelope">
	<soapenv:Body>
		<RequestXsvcRegister xmlns="http://www.cisco.com/schema/cisco_xsvc/v1_0">
			<applicationData>
				<name>invite_cubemon_xsvc</name>
				<url>http://cube_mon.invitenetworks.com/services/ios/xsvc/monitor</url>
			</applicationData>
			<msgHeader>
				<transactionID>txID001</transactionID>
			</msgHeader>
			<providerData>
				<url>http://97.75.170.77:8090/cisco_xsvc</url>
 			</providerData>
			<routeEventsFilter>ROUTE_CONF_UPDATED ROUTE_STATUS_UPDATED</routeEventsFilter>
		</RequestXsvcRegister>
	</soapenv:Body>
</soapenv:Envelope>


Register Response
~~~~~~~~~~~~~~~~~

<?xml version="1.0" encoding="UTF-8"?>
<SOAP:Envelope xmlns:SOAP="http://www.w3.org/2003/05/soap-envelope">
	<SOAP:Body>
		<ResponseXsvcRegister xmlns="http://www.cisco.com/schema/cisco_xsvc/v1_0">
			<msgHeader>
				<transactionID>txID001</transactionID>
				<registrationID>BC53FA:XSVC:invite_cubemon_xsvc:1</registrationID>
			</msgHeader>
			<providerStatus>IN_SERVICE</providerStatus>
		</ResponseXsvcRegister>
	</SOAP:Body>
</SOAP:Envelope>


Route Snapshot Request
~~~~~~~~~~~~~~~~~~~~~~

<?xml version="1.0" encoding="UTF-8"?>
<soapenv:Envelope xmlns:soapenv="http://www.w3.org/2003/05/soap-envelope">
	<soapenv:Body>
		<RequestXsvcRouteSnapshot xmlns="http://www.cisco.com/schema/cisco_xsvc/v1_0">
			<msgHeader>
				<transactionID>txID002</transactionID>
                                <registrationID>2648078:XSVC:invite_cubemon_xsvc:2</registrationID>
			</msgHeader>
		</RequestXsvcRouteSnapshot>
	</soapenv:Body>
</soapenv:Envelope>


Route Snapshot Response
~~~~~~~~~~~~~~~~~~~~~~~

<?xml version="1.0" encoding="UTF-8"?>
<SOAP:Envelope xmlns:SOAP="http://www.w3.org/2003/05/soap-envelope">
    <SOAP:Body>
        <ResponseXsvcRouteSnapshot xmlns="http://www.cisco.com/schema/cisco_xsvc/v1_0">
            <msgHeader>
                <transactionID>txID002</transactionID>
                <registrationID>2648078:XSVC:invite_cubemon_xsvc:2</registrationID>
            </msgHeader>
            <routeList>
                <route>
                    <routeName>VERACITY</routeName>
                    <routeType>VOIP</routeType>
                    <trunkList>
                        <trunkData>
                            <name>66.7.123.157</name>
                            <type>SIPV2</type>
                            <status>UP</status>
                        </trunkData>
                    </trunkList>
                </route>
                <route>
                    <routeName>VIVINT_AVAYA</routeName>
                    <routeType>VOIP</routeType>
                    <trunkList>
                        <trunkData>
                            <name>172.16.254.156</name>
                            <type>SIPV2</type>
                            <status>UP</status>
                        </trunkData>
                    </trunkList>
                </route>
            </routeList>
        </ResponseXsvcRouteSnapshot>
    </SOAP:Body>
</SOAP:Envelope>


Xcdr Probing
~~~~~~~~~~~~~~~~~~~~~~~
POST /rest/ios/record/notify.xml HTTP/1.1
Host: 71.195.207.10:80
Content-Length: 539
Content-Type: application/soap+xml
Connection: Keep-Alive
Accept: text/vxml, text/x-vxml, application/vxml, application/x-vxml, application/voicexml, application/x-voicexml, text/plain, text/html, audio/basic, audio/wav, multipart/form-data, application/octet-stream
User-Agent: Cisco-IOS-C3900e/15.3

<?xml version="1.0" encoding="UTF-8"?><SOAP:Envelope xmlns:SOAP="http://www.w3.org/2003/05/soap-envelope"><SOAP:Body><NotifyXcdrProviderStatus xmlns="http://www.cisco.com/schema/cisco_xcdr/v1_0"><msgHeader><transactionID>D388222:325</transactionID></msgHeader><applicationData><url>http://71.195.207.10:80/rest/ios/record/notify.xml</url></applicationData><providerData><url>http://GigabitEthernet0/0.100:8090/cisco_xcdr</url></providerData><providerStatus>IN_SERVICE</providerStatus></NotifyXcdrProviderStatus></SOAP:Body></SOAP:Envelope>HTTP/1.1 404 Not Found
Date: Fri, 21 Jun 2013 04:10:24 GMT
Server: Apache/2.2.23 (Unix) mod_ssl/2.2.23 OpenSSL/1.0.1c DAV/2 PHP/5.4.11
X-Powered-By: PHP/5.4.11
Cache-Control: no-cache
Keep-Alive: timeout=5, max=100
Connection: Keep-Alive
Transfer-Encoding: chunked
Content-Type: text/html; charset=UTF-8

229
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>An Error Occurred: Not Found</title>
    </head>
    <body>
        <h1>Oops! An Error Occurred</h1>
        <h2>The server returned a "404 Not Found".</h2>

        <div>
            Something is broken. Please e-mail us at [email] and let us know
            what you were doing when this error occurred. We will fix it as soon
            as possible. Sorry for any inconvenience caused.
        </div>
    </body>
</html>

Xcdr NotifyXcdrRecord
~~~~~~~~~~~~~~~~~~~~~~~
POST /services/ios/soap/xcdr HTTP/1.1
Host: 71.195.207.10:80
Content-Length: 828
Content-Type: application/soap+xml
Connection: Keep-Alive
Accept: text/vxml, text/x-vxml, application/vxml, application/x-vxml, application/voicexml, application/x-voicexml, text/plain, text/html, audio/basic, audio/wav, multipart/form-data, application/octet-stream
User-Agent: Cisco-IOS-C3900e/15.3

<?xml version="1.0" encoding="UTF-8"?><SOAP:Envelope xmlns:SOAP="http://www.w3.org/2003/05/soap-envelope">
<SOAP:Body>
<NotifyXcdrRecord xmlns="http://www.cisco.com/schema/cisco_xcdr/v1_0">
<msgHeader><transactionID>D62A8DC:346</transactionID><registrationID>D61E1BC:XCDR:invite_cubemon_xcdr:10</registrationID></msgHeader>
<format>COMPACT</format>
<type>STOP</type>
<cdr><![CDATA[1371789223,4299,1,2,"9364550F D96211E2 8F0D9C9C 52CD806F","8016804400","","*04:33:37.734 UTC Fri Jun 21 2013","","*04:33:39.264 UTC Fri Jun 21 2013","*04:33:43.584 UTC Fri Jun 21 2013","10  ","normal call clearing (16)","answer",0,"",283,45280,273,43680,"8016804400","8016804400","8809","TWC","06/21/2013 04:33:37.738","8016804400","8809",0,189,9364550F D96211E2 8F0D9C9C 52CD806F,10CB,"","","",""]]></cdr>
</NotifyXcdrRecord>
</SOAP:Body>
</SOAP:Envelope>


HTTP/1.1 500 Internal Service Error
Date: Fri, 21 Jun 2013 04:56:27 GMT
Server: Apache/2.2.23 (Unix) mod_ssl/2.2.23 OpenSSL/1.0.1c DAV/2 PHP/5.4.11
X-Powered-By: PHP/5.4.11
Content-Length: 308
Connection: close
Content-Type: application/soap+xml; charset=utf-8

<?xml version="1.0" encoding="UTF-8"?>
<env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope">
<env:Body>
<env:Fault>
<env:Code>
<env:Value>env:Receiver</env:Value>
</env:Code>
<env:Reason><env:Text>Function 'NotifyXcdrRecord' doesn't exist</env:Text></env:Reason>
</env:Fault>
</env:Body>
</env:Envelope>