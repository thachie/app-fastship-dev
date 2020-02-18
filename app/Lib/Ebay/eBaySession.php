<?php
namespace App\Lib\Ebay;

use DOMDocument;

class eBaySession
{
	
	private $requestToken;
	private $siteID;
	private $verb;
	
	// these keys can be obtained by registering at http://developer.ebay.com
    private $production  = true;   // toggle to true if going against production
    private $compatLevel = 991;    // eBay API version

    private $devID = '35eeac4e-6006-41d9-adee-c4becc11ba46';   // these prod keys are different from sandbox keys
    private $appID = 'TUFFComp-CloudCom-PRD-1ab9522e2-1a463403';
    private $certID = 'PRD-ab9522e246d1-1a9b-4fc5-b633-aad6';
    //set the Server to use (Sandbox or Production)
    private $serverUrl = 'https://api.ebay.com/ws/api.dll';      // server URL different for prod and sandbox

    //the token representing the eBay user to assign the call with
    //run name
    private $ruName = 'TUFF_Company-TUFFComp-CloudC-qjqlrnfod';
    
	/**	__construct
		Constructor to make a new instance of eBaySession with the details needed to make a call
		Input:	$userRequestToken - the authentication token fir the user making the call
				$developerID - Developer key obtained when registered at http://developer.ebay.com
				$applicationID - Application key obtained when registered at http://developer.ebay.com
				$certificateID - Certificate key obtained when registered at http://developer.ebay.com
				$useTestServer - Boolean, if true then Sandbox server is used, otherwise production server is used
				$compatabilityLevel - API version this is compatable with
				$siteToUseID - the Id of the eBay site to associate the call iwht (0 = US, 2 = Canada, 3 = UK, ...)
				$callName  - The name of the call being made (e.g. 'GeteBayOfficialTime')
		Output:	Response string returned by the server
	*/
	public function __construct($userRequestToken,$siteToUseID,$callName)
	{
		$this->requestToken = $userRequestToken;
		$this->siteID = $siteToUseID;
		$this->verb = $callName;
	}
	
	public function getSessionId(){
		
		$this->verb = 'GetSessionID';
		$this->siteID = 15;
		
		///Build the request Xml string
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestXmlBody .= '<GetSessionIDRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= '<RuName>' . $this->ruName . '</RuName>';
		$requestXmlBody .= '</GetSessionIDRequest>';
		
		$responseXml = $this->sendHttpRequest($requestXmlBody);
		if (stristr($responseXml, 'HTTP 404') || $responseXml == ''){
			die('<P>Error sending request');
		}

		$responseDoc = new DOMDocument();
		$responseDoc->loadXML($responseXml);
		$response = simplexml_import_dom($responseDoc);
		
		return $response->SessionID;
	}
	
	/**	sendHttpRequest
		Sends a HTTP request to the server for this session
		Input:	$requestBody
		Output:	The HTTP Response as a String
	*/
	public function sendHttpRequest($requestBody)
	{
		//build eBay headers using variables passed via constructor
		$headers = $this->buildEbayHeaders();
		
		//initialise a CURL session
		$connection = curl_init();
		//set the server we are using (could be Sandbox or Production server)
		curl_setopt($connection, CURLOPT_URL, $this->serverUrl);
		
		//stop CURL from verifying the peer's certificate
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
		
		//set the headers using the array of headers
		curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
		
		//set method as POST
		curl_setopt($connection, CURLOPT_POST, 1);
		
		//set the XML body of the request
		curl_setopt($connection, CURLOPT_POSTFIELDS, $requestBody);
		
		//set it to return the transfer as a string from curl_exec
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		
		//Send the Request
		$response = curl_exec($connection);
		
		//close the connection
		curl_close($connection);

		//return the response
		return $response;
		
		
	}
	
	
	
	/**	buildEbayHeaders
		Generates an array of string to be used as the headers for the HTTP request to eBay
		Output:	String Array of Headers applicable for this call
	*/
	private function buildEbayHeaders()
	{
		$headers = array (
			//Regulates versioning of the XML interface for the API
			'X-EBAY-API-COMPATIBILITY-LEVEL: ' . $this->compatLevel,
			
			//set the keys
			'X-EBAY-API-DEV-NAME: ' . $this->devID,
			'X-EBAY-API-APP-NAME: ' . $this->appID,
			'X-EBAY-API-CERT-NAME: ' . $this->certID,
			
			//the name of the call we are requesting
			'X-EBAY-API-CALL-NAME: ' . $this->verb,			
			
			//SiteID must also be set in the Request's XML
			//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
			//SiteID Indicates the eBay site to associate the call with
			'X-EBAY-API-SITEID: ' . $this->siteID,
		);
		
		return $headers;
	}
}
?>