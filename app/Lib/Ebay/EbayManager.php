<?php
namespace App\Lib\Ebay;

use DOMDocument;

class EbayManager{

	public static function GetSessionId(){
	
// 		$session = new eBaySession();
// 		return $session->getSessionId();
		
// 		exit();
		//SiteID must also be set in the Request's XML
		//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
		//SiteID Indicates the eBay site to associate the call with
		$siteID = 15;
		//the call being made:
		$verb = 'GetSessionID';
	
		$compatLevel = 991;
		$devID = '35eeac4e-6006-41d9-adee-c4becc11ba46';   // these prod keys are different from sandbox keys
		$appID = 'TUFFComp-CloudCom-PRD-1ab9522e2-1a463403';
		$certID = 'PRD-ab9522e246d1-1a9b-4fc5-b633-aad6';
		$ruName = "TUFF_Company-TUFFComp-CloudC-qjqlrnfod";
	
		$serverUrl = 'https://api.ebay.com/ws/api.dll';
	
		///Build the request Xml string
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestXmlBody .= '<GetSessionIDRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= '<RuName>' . $ruName . '</RuName>';
		$requestXmlBody .= '</GetSessionIDRequest>';
	
		//build eBay headers using variables passed via constructor
		$headers = array (
			//Regulates versioning of the XML interface for the API
			'X-EBAY-API-COMPATIBILITY-LEVEL: ' . $compatLevel,
			
			//set the keys
			'X-EBAY-API-DEV-NAME: ' . $devID,
			'X-EBAY-API-APP-NAME: ' . $appID,
			'X-EBAY-API-CERT-NAME: ' . $certID,
			
			//the name of the call we are requesting
			'X-EBAY-API-CALL-NAME: ' . $verb,			
			
			//SiteID must also be set in the Request's XML
			//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
			//SiteID Indicates the eBay site to associate the call with
			'X-EBAY-API-SITEID: ' . $siteID,
		);

		//initialise a CURL session
		$connection = curl_init();
		//set the server we are using (could be Sandbox or Production server)
		curl_setopt($connection, CURLOPT_URL, $serverUrl);
		
		//stop CURL from verifying the peer's certificate
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
		
		//set the headers using the array of headers
		curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
		
		//set method as POST
		curl_setopt($connection, CURLOPT_POST, 1);
		
		//set the XML body of the request
		curl_setopt($connection, CURLOPT_POSTFIELDS, $requestXmlBody);
		
		//set it to return the transfer as a string from curl_exec
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		
		//Send the Request
		$response = curl_exec($connection);
		
		//close the connection
		curl_close($connection);
		
		//print_r($requestBody);echo "<br />";
		$session = new eBaySession("",$siteID, $verb);
	
		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		if (stristr($responseXml, 'HTTP 404') || $responseXml == '')
			die('<P>Error sending request');
	
			//Xml string is parsed and creates a DOM Document object
			$responseDoc = new DOMDocument();
			$responseDoc->loadXML($responseXml);
	
			//get any error nodes
			$errors = $responseDoc->getElementsByTagName('Errors');
			$response = simplexml_import_dom($responseDoc);
			$entries = $response->PaginationResult->TotalNumberOfEntries;
	
			//if there are error nodes
			if ($errors->length > 0) {
	
				echo '<P><B>eBay returned the following error(s):</B>';
				//display each error
				//Get error code, ShortMesaage and LongMessage
				$code = $errors->item(0)->getElementsByTagName('ErrorCode');
				$shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
				$longMsg = $errors->item(0)->getElementsByTagName('LongMessage');
	
				//Display code and shortmessage
				echo '<P>', $code->item(0)->nodeValue, ' : ', str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
	
				//if there is a long message (ie ErrorLevel=1), display it
				if (count($longMsg) > 0)
					echo '<BR>', str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
			}else { //If there are no errors, continue
				if(isset($_GET['debug']))
				{
					header("Content-type: text/xml");
					//print_r($responseXml);
				}else{
					//$response = simplexml_import_dom($responseDoc);
					if ($entries == 0) {
						// echo "Sorry No entries found in the Time period requested. Change CreateTimeFrom/CreateTimeTo and Try again";
					} else {
						//echo $entries;
						//print_r($response);
	
					}
				}
			}
			
			return $response->SessionID[0];
	
	}
	
	public static function GetOrders($params){
		
		extract($params);
		
		//SiteID must also be set in the Request's XML
		//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
		//SiteID Indicates the eBay site to associate the call with
		$siteID = 0;
		//the call being made:
		$verb = 'GetOrders';

		///Build the request Xml string
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestXmlBody .= '<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= '<OrderStatus>Completed</OrderStatus>';
		//$requestXmlBody .= "<CreateTimeFrom>$CreateTimeFrom</CreateTimeFrom><CreateTimeTo>$CreateTimeTo</CreateTimeTo>";
		$requestXmlBody .= "<NumberOfDays>7</NumberOfDays>";
		$requestXmlBody .= '<OrderRole>Seller</OrderRole>';
		$requestXmlBody .= '<SortingOrder>Descending</SortingOrder>';
		$requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';
		$requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$userToken</eBayAuthToken></RequesterCredentials>";
		$requestXmlBody .= '</GetOrdersRequest>';
		
		//Create a new eBay session with all details pulled in from included keys.php
		//$session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);
		$session = new eBaySession($userToken,$siteID, $verb);

		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		if (stristr($responseXml, 'HTTP 404') || $responseXml == '')
		    die('<P>Error sending request');
		
		//Xml string is parsed and creates a DOM Document object
		$responseDoc = new DOMDocument();
		$responseDoc->loadXML($responseXml);
		print_r($responseDoc);
		//get any error nodes
		$errors = $responseDoc->getElementsByTagName('Errors');
		$response = simplexml_import_dom($responseDoc);
		
		$entries = $response->PaginationResult->TotalNumberOfEntries;

		//if there are error nodes
		if ($errors->length > 0) {
		    
			echo '<P><B>eBay returned the following error(s):</B>';
		    //display each error
		    //Get error code, ShortMesaage and LongMessage
		    $code = $errors->item(0)->getElementsByTagName('ErrorCode');
		    $shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
		    $longMsg = $errors->item(0)->getElementsByTagName('LongMessage');
		    
		    //Display code and shortmessage
		    echo '<P>', $code->item(0)->nodeValue, ' : ', str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
		    
		    //if there is a long message (ie ErrorLevel=1), display it
		    if (count($longMsg) > 0)
		        echo '<BR>', str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
		}else { //If there are no errors, continue
		    if(isset($_GET['debug']))
		    {  
		       header("Content-type: text/xml");
		       print_r($responseXml);
		    }else{
		    	//$response = simplexml_import_dom($responseDoc);
				if ($entries == 0) {
				    echo "Sorry No entries found in the Time period requested. Change CreateTimeFrom/CreateTimeTo and Try again";
				} else {
					//echo $entries;
					print_r($response->OrderArray);exit();
				    //$orders = $response->OrderArray->Order;
				    $orders = $response->OrderArray->Order;
				    return $orders;
				}
		    }
		}
		
	}
	
	public static function GetOrder($params){
		
		extract($params);
		
		//SiteID must also be set in the Request's XML
		//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
		//SiteID Indicates the eBay site to associate the call with
		$siteID = 0;
		//the call being made:
		$verb = 'GetOrderTransactions';
		//$userToken = 'AgAAAA**AQAAAA**aAAAAA**W5VCVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFkoGjC5SBpQmdj6x9nY+seQ**FUwDAA**AAMAAA**fH9Q/KR6cvLHrieYU/y91ISc/thXLlX1gXB6UEL7BcUpcIsv00KK+9msaAO5k0qTSdq284XJ5xqTkkBe9bn6OZ94V9Nncb8PoRububB5ryf4bwgmUFgmjNCX79AswEaaRGs50GWMrTZrCU+kmrU5UFTtLIBNmk11Dg2Q2BZ3NyPdTwnCosQfWTmHr3/35/24hTdstuGPajRlMkMgvhVyhePw5O57a90bjJheidmJuVmMuLGsEhojSLzWrD4YccIeDPy69OM33nmWe3vsKTdRVI998aXXsomIH9tFA2FamN6c18HZCB99ANxhPnqAf5LX6AChVG/IDfRAbekYOC7Ci+fLOPAZEmkU49YQVijW2PPfm34y/qzS9QdZQUAggzVGblZmsHNO06dSnrsYPImvf9hhCiw3yNbpLpN+Bj/bDtALn5rb5Q4fa4kCDJ7UJ1FDG7vH/9XsyqQssELtmVMGPN0pBU92+nAbKWWyLy/PIojS2fhzHa+d0qqqLrhZ3MR/EsiQ6FHPh6VCynxba1ZeTza6lY+TVxqr16tLUaaIJlZVJVGTGZnUYKGlaTx6a7jhQQWEur2R2RDA5ymIecXfxGqOgSWVjI4N/jzYlxhAZFQJ691LaRv9652Jp6XXC+nDO2OGqGUTF8AVeLPG1iSa2a8G8cvPj/UHciKTUtiWkUqMqmd9/ERzQ83XhpQycorMJi30hOv/L9e3EOAIGnCjtJTUtpjBzof71yO1nYfr+ecCtNCqb/MaeoeRwdr5Sxpe';          
    	//au
		//$userToken = 'AgAAAA**AQAAAA**aAAAAA**nKhCVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AFkIKnCpCGog+dj6x9nY+seQ**FUwDAA**AAMAAA**5Nf8c8UF7xvNoOBcs5VAC7Q7laTk6RK8hqGDgXkI739V5/Ib3xlTFJkvigqJStBfQd90jCNIyAfHpaGrkYm00Uq5+k2K+QufQidtZmNYINohISUXQgCDJAQvipHlksNmNlL8eMZWWFlsSTRwQLgwEfPkS6VM3bUuuM0lnZalKzZCSm/tQWxVvzSkySzdcMqZbd6Hz22ZLe95zK3X5UFdwGF8VwBOFXV/BW4HHkFYpIAvkMKmi4TPkhA9cscodY1F3L8iC1vehQnRWUiGZdpdn21KPH7CchVrSI6ymmmkYyvPFODAWOGvtgkob7xPkmlaXUA0pd1ZIbtcpRp9aSL/z87lz5sZhok7UK6zCsXA+o759r45nCYE5hp+DEW3EiI19zqVsU3Hn6ECLo7mx75dAr1idF2rYpmMVJpwsdVDxgwGxnzexIF9vVfh5OFCwLTenEfMVFnxByls9DZPQL/p09wd4FgtbWrTkJ8JDT4QxUSR3st/oEx4Oeo+mzSREp1ajtdeEL0gkSX6k1Hy8HJ+4lZ3sq77mVdiNOe3dwQrNSb4gwHLfke3rW2Rve8d42swfF3a4V6mkUfRHL36U/HGCrQq76b4d8Ymm/KOjG/zb5vB3OLoOo0Q6+5yEPxenzXP+8Sxx/v7OT9w4rEEvy8x3r4Q1fDXUJVQ5wmj29VA9KE3XSLCnyEtJfokKC3BZ+KVswrB2wYtZGm4bT/peabnZ/SBFH+uPy3I2wIX1ao2zX0vH4z3KJuBLk4A4TNyduRz';
				
		///Build the request Xml string
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestXmlBody .= '<GetOrderTransactionsRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= "<OrderIDArray><OrderID>" . $OrderID . "</OrderID></OrderIDArray>";
		$requestXmlBody .= "<Platform>eBay</Platform>";
		$requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';
		$requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$userToken</eBayAuthToken></RequesterCredentials>";
		$requestXmlBody .= '</GetOrderTransactionsRequest>';

		//Create a new eBay session with all details pulled in from included keys.php
		//$session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);
		$session = new eBaySession($userToken,$siteID, $verb);

		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		if (stristr($responseXml, 'HTTP 404') || $responseXml == '')
		    die('<P>Error sending request');
		
		//Xml string is parsed and creates a DOM Document object
		$responseDoc = new DOMDocument();
		$responseDoc->loadXML($responseXml);
		
		//get any error nodes
		$errors = $responseDoc->getElementsByTagName('Errors');
		$response = simplexml_import_dom($responseDoc);
		//$entries = $response->PaginationResult->TotalNumberOfEntries;
		
		//if there are error nodes
		if ($errors->length > 0) {
		    
			echo '<P><B>eBay returned the following error(s):</B>';
		    //display each error
		    //Get error code, ShortMesaage and LongMessage
		    $code = $errors->item(0)->getElementsByTagName('ErrorCode');
		    $shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
		    $longMsg = $errors->item(0)->getElementsByTagName('LongMessage');
		    
		    //Display code and shortmessage
		    echo '<P>', $code->item(0)->nodeValue, ' : ', str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
		    
		    //if there is a long message (ie ErrorLevel=1), display it
		    if (count($longMsg) > 0)
		        echo '<BR>', str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
		}else { //If there are no errors, continue
		    if(isset($_GET['debug']))
		    {  
		       header("Content-type: text/xml");
		       print_r($responseXml);
		    }else{
		    	//$response = simplexml_import_dom($responseDoc);
				//if ($entries == 0) {
				//    echo "Sorry No entries found in the Time period requested. Change CreateTimeFrom/CreateTimeTo and Try again";
				//} else {
					//echo $entries;
					print_r($response->OrderArray->Order);exit();
				    //$orders = $response->OrderArray->Order;
				    $order = $response->OrderArray->Order;
				    return $order;
				//}
		    }
		}
		
	}
	
	public static function GetItems($params){
		
		extract($params);
		
		//SiteID must also be set in the Request's XML
		//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
		//SiteID Indicates the eBay site to associate the call with
		$siteID = 0;
		//the call being made:
		$verb = 'GetItemTransactions';
		//$userToken = 'AgAAAA**AQAAAA**aAAAAA**W5VCVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFkoGjC5SBpQmdj6x9nY+seQ**FUwDAA**AAMAAA**fH9Q/KR6cvLHrieYU/y91ISc/thXLlX1gXB6UEL7BcUpcIsv00KK+9msaAO5k0qTSdq284XJ5xqTkkBe9bn6OZ94V9Nncb8PoRububB5ryf4bwgmUFgmjNCX79AswEaaRGs50GWMrTZrCU+kmrU5UFTtLIBNmk11Dg2Q2BZ3NyPdTwnCosQfWTmHr3/35/24hTdstuGPajRlMkMgvhVyhePw5O57a90bjJheidmJuVmMuLGsEhojSLzWrD4YccIeDPy69OM33nmWe3vsKTdRVI998aXXsomIH9tFA2FamN6c18HZCB99ANxhPnqAf5LX6AChVG/IDfRAbekYOC7Ci+fLOPAZEmkU49YQVijW2PPfm34y/qzS9QdZQUAggzVGblZmsHNO06dSnrsYPImvf9hhCiw3yNbpLpN+Bj/bDtALn5rb5Q4fa4kCDJ7UJ1FDG7vH/9XsyqQssELtmVMGPN0pBU92+nAbKWWyLy/PIojS2fhzHa+d0qqqLrhZ3MR/EsiQ6FHPh6VCynxba1ZeTza6lY+TVxqr16tLUaaIJlZVJVGTGZnUYKGlaTx6a7jhQQWEur2R2RDA5ymIecXfxGqOgSWVjI4N/jzYlxhAZFQJ691LaRv9652Jp6XXC+nDO2OGqGUTF8AVeLPG1iSa2a8G8cvPj/UHciKTUtiWkUqMqmd9/ERzQ83XhpQycorMJi30hOv/L9e3EOAIGnCjtJTUtpjBzof71yO1nYfr+ecCtNCqb/MaeoeRwdr5Sxpe';          
    	//au
		//$userToken = 'AgAAAA**AQAAAA**aAAAAA**nKhCVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AFkIKnCpCGog+dj6x9nY+seQ**FUwDAA**AAMAAA**5Nf8c8UF7xvNoOBcs5VAC7Q7laTk6RK8hqGDgXkI739V5/Ib3xlTFJkvigqJStBfQd90jCNIyAfHpaGrkYm00Uq5+k2K+QufQidtZmNYINohISUXQgCDJAQvipHlksNmNlL8eMZWWFlsSTRwQLgwEfPkS6VM3bUuuM0lnZalKzZCSm/tQWxVvzSkySzdcMqZbd6Hz22ZLe95zK3X5UFdwGF8VwBOFXV/BW4HHkFYpIAvkMKmi4TPkhA9cscodY1F3L8iC1vehQnRWUiGZdpdn21KPH7CchVrSI6ymmmkYyvPFODAWOGvtgkob7xPkmlaXUA0pd1ZIbtcpRp9aSL/z87lz5sZhok7UK6zCsXA+o759r45nCYE5hp+DEW3EiI19zqVsU3Hn6ECLo7mx75dAr1idF2rYpmMVJpwsdVDxgwGxnzexIF9vVfh5OFCwLTenEfMVFnxByls9DZPQL/p09wd4FgtbWrTkJ8JDT4QxUSR3st/oEx4Oeo+mzSREp1ajtdeEL0gkSX6k1Hy8HJ+4lZ3sq77mVdiNOe3dwQrNSb4gwHLfke3rW2Rve8d42swfF3a4V6mkUfRHL36U/HGCrQq76b4d8Ymm/KOjG/zb5vB3OLoOo0Q6+5yEPxenzXP+8Sxx/v7OT9w4rEEvy8x3r4Q1fDXUJVQ5wmj29VA9KE3XSLCnyEtJfokKC3BZ+KVswrB2wYtZGm4bT/peabnZ/SBFH+uPy3I2wIX1ao2zX0vH4z3KJuBLk4A4TNyduRz';
				
		///Build the request Xml string
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestXmlBody .= '<GetItemTransactionsRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= "<ItemID>" . $ItemID . "</ItemID>";
		if($IncludeContainingOrder != ""){
			$requestXmlBody .= "<IncludeContainingOrder>" . $IncludeContainingOrder . "</IncludeContainingOrder>";
		}
		if($OrderID != ""){
			$requestXmlBody .= "<OrderLineItemID>". $OrderID ."</OrderLineItemID>";
		}
		$requestXmlBody .= "<Platform>eBay</Platform>";
		if($TransactionID != ""){
			$requestXmlBody .= "<TransactionID>" . $TransactionID . "</TransactionID>";
		}
		$requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';
		$requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$userToken</eBayAuthToken></RequesterCredentials>";
		$requestXmlBody .= '</GetItemTransactionsRequest>';

		//Create a new eBay session with all details pulled in from included keys.php
		//$session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);
		$session = new eBaySession($userToken,$siteID, $verb);

		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		if (stristr($responseXml, 'HTTP 404') || $responseXml == '')
		    die('<P>Error sending request');
		
		//Xml string is parsed and creates a DOM Document object
		$responseDoc = new DOMDocument();
		$responseDoc->loadXML($responseXml);
		
		//get any error nodes
		$errors = $responseDoc->getElementsByTagName('Errors');
		$response = simplexml_import_dom($responseDoc);
		//$entries = $response->PaginationResult->TotalNumberOfEntries;
		
		//if there are error nodes
		if ($errors->length > 0) {
		    
			echo '<P><B>eBay returned the following error(s):</B>';
		    //display each error
		    //Get error code, ShortMesaage and LongMessage
		    $code = $errors->item(0)->getElementsByTagName('ErrorCode');
		    $shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
		    $longMsg = $errors->item(0)->getElementsByTagName('LongMessage');
		    
		    //Display code and shortmessage
		    echo '<P>', $code->item(0)->nodeValue, ' : ', str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
		    
		    //if there is a long message (ie ErrorLevel=1), display it
		    if (count($longMsg) > 0)
		        echo '<BR>', str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
		}else { //If there are no errors, continue
		    if(isset($_GET['debug']))
		    {  
		       header("Content-type: text/xml");
		       print_r($responseXml);
		    }else{
		    	//$response = simplexml_import_dom($responseDoc);
				//if ($entries == 0) {
				//    echo "Sorry No entries found in the Time period requested. Change CreateTimeFrom/CreateTimeTo and Try again";
				//} else {
					//echo $entries;
				    //$orders = $response->OrderArray->Order;
				    $order = $response->Item;
				    return $order;
				//}
		    }
		}
		
	}
	
	public static function ReviseInventoryStatus($params){
	
		extract($params);
	
		//SiteID must also be set in the Request's XML
		//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
		//SiteID Indicates the eBay site to associate the call with
		$siteID = 0;
		//the call being made:
		$verb = 'ReviseInventoryStatus';
		//$userToken = 'AgAAAA**AQAAAA**aAAAAA**W5VCVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFkoGjC5SBpQmdj6x9nY+seQ**FUwDAA**AAMAAA**fH9Q/KR6cvLHrieYU/y91ISc/thXLlX1gXB6UEL7BcUpcIsv00KK+9msaAO5k0qTSdq284XJ5xqTkkBe9bn6OZ94V9Nncb8PoRububB5ryf4bwgmUFgmjNCX79AswEaaRGs50GWMrTZrCU+kmrU5UFTtLIBNmk11Dg2Q2BZ3NyPdTwnCosQfWTmHr3/35/24hTdstuGPajRlMkMgvhVyhePw5O57a90bjJheidmJuVmMuLGsEhojSLzWrD4YccIeDPy69OM33nmWe3vsKTdRVI998aXXsomIH9tFA2FamN6c18HZCB99ANxhPnqAf5LX6AChVG/IDfRAbekYOC7Ci+fLOPAZEmkU49YQVijW2PPfm34y/qzS9QdZQUAggzVGblZmsHNO06dSnrsYPImvf9hhCiw3yNbpLpN+Bj/bDtALn5rb5Q4fa4kCDJ7UJ1FDG7vH/9XsyqQssELtmVMGPN0pBU92+nAbKWWyLy/PIojS2fhzHa+d0qqqLrhZ3MR/EsiQ6FHPh6VCynxba1ZeTza6lY+TVxqr16tLUaaIJlZVJVGTGZnUYKGlaTx6a7jhQQWEur2R2RDA5ymIecXfxGqOgSWVjI4N/jzYlxhAZFQJ691LaRv9652Jp6XXC+nDO2OGqGUTF8AVeLPG1iSa2a8G8cvPj/UHciKTUtiWkUqMqmd9/ERzQ83XhpQycorMJi30hOv/L9e3EOAIGnCjtJTUtpjBzof71yO1nYfr+ecCtNCqb/MaeoeRwdr5Sxpe';
		//au
		//$userToken = 'AgAAAA**AQAAAA**aAAAAA**nKhCVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AFkIKnCpCGog+dj6x9nY+seQ**FUwDAA**AAMAAA**5Nf8c8UF7xvNoOBcs5VAC7Q7laTk6RK8hqGDgXkI739V5/Ib3xlTFJkvigqJStBfQd90jCNIyAfHpaGrkYm00Uq5+k2K+QufQidtZmNYINohISUXQgCDJAQvipHlksNmNlL8eMZWWFlsSTRwQLgwEfPkS6VM3bUuuM0lnZalKzZCSm/tQWxVvzSkySzdcMqZbd6Hz22ZLe95zK3X5UFdwGF8VwBOFXV/BW4HHkFYpIAvkMKmi4TPkhA9cscodY1F3L8iC1vehQnRWUiGZdpdn21KPH7CchVrSI6ymmmkYyvPFODAWOGvtgkob7xPkmlaXUA0pd1ZIbtcpRp9aSL/z87lz5sZhok7UK6zCsXA+o759r45nCYE5hp+DEW3EiI19zqVsU3Hn6ECLo7mx75dAr1idF2rYpmMVJpwsdVDxgwGxnzexIF9vVfh5OFCwLTenEfMVFnxByls9DZPQL/p09wd4FgtbWrTkJ8JDT4QxUSR3st/oEx4Oeo+mzSREp1ajtdeEL0gkSX6k1Hy8HJ+4lZ3sq77mVdiNOe3dwQrNSb4gwHLfke3rW2Rve8d42swfF3a4V6mkUfRHL36U/HGCrQq76b4d8Ymm/KOjG/zb5vB3OLoOo0Q6+5yEPxenzXP+8Sxx/v7OT9w4rEEvy8x3r4Q1fDXUJVQ5wmj29VA9KE3XSLCnyEtJfokKC3BZ+KVswrB2wYtZGm4bT/peabnZ/SBFH+uPy3I2wIX1ao2zX0vH4z3KJuBLk4A4TNyduRz';
	
		///Build the request Xml string
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestXmlBody .= '<ReviseInventoryStatusRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= "<InventoryStatus>";
		$requestXmlBody .= "<Quantity>" . $Quantity . "</Quantity>";
		$requestXmlBody .= "<SKU>" . $SKU . "</SKU>";
		if($StartPrice != ""){
			$requestXmlBody .= "<StartPrice>" . $StartPrice . "</StartPrice>";
		}
		$requestXmlBody .= "</InventoryStatus>";
		$requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$userToken</eBayAuthToken></RequesterCredentials>";
		$requestXmlBody .= '</ReviseInventoryStatusRequest>';
	
		//Create a new eBay session with all details pulled in from included keys.php
		//$session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);
		$session = new eBaySession($userToken,$siteID, $verb);
	
		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		if (stristr($responseXml, 'HTTP 404') || $responseXml == '')
			die('<P>Error sending request');
	
			//Xml string is parsed and creates a DOM Document object
			$responseDoc = new DOMDocument();
			$responseDoc->loadXML($responseXml);
	
			//get any error nodes
			$errors = $responseDoc->getElementsByTagName('Errors');
			$response = simplexml_import_dom($responseDoc);
			//$entries = $response->PaginationResult->TotalNumberOfEntries;
	
			//if there are error nodes
			if ($errors->length > 0) {
	
				echo '<P><B>eBay returned the following error(s):</B>';
				//display each error
				//Get error code, ShortMesaage and LongMessage
				$code = $errors->item(0)->getElementsByTagName('ErrorCode');
				$shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
				$longMsg = $errors->item(0)->getElementsByTagName('LongMessage');
	
				//Display code and shortmessage
				echo '<P>', $code->item(0)->nodeValue, ' : ', str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
	
				//if there is a long message (ie ErrorLevel=1), display it
				if (count($longMsg) > 0)
					echo '<BR>', str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
			}else { //If there are no errors, continue
				if(isset($_GET['debug']))
				{
					header("Content-type: text/xml");
					print_r($responseXml);
				}else{
					//$response = simplexml_import_dom($responseDoc);
					//if ($entries == 0) {
					//    echo "Sorry No entries found in the Time period requested. Change CreateTimeFrom/CreateTimeTo and Try again";
					//} else {
					//echo $entries;
					print_r($response->OrderArray->Order);exit();
					//$orders = $response->OrderArray->Order;
					$order = $response->OrderArray->Order;
					return $order;
					//}
				}
			}
	
	}
	
	public static function GeteBayOfficialTime($params){
		
		extract($params);
		
		//SiteID must also be set in the Request's XML
		//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
		//SiteID Indicates the eBay site to associate the call with
		$siteID = 0;
		//the call being made:
		$verb = 'GeteBayOfficialTime';
		
		///Build the request Xml string
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestXmlBody .= '<GeteBayOfficialTimeRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		$requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$userToken</eBayAuthToken></RequesterCredentials>";
		$requestXmlBody .= '</GeteBayOfficialTimeRequest>';
		
		//Create a new eBay session with all details pulled in from included keys.php
		//$session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);
		$session = new eBaySession($userToken,$siteID, $verb);

		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		if (stristr($responseXml, 'HTTP 404') || $responseXml == '')
		    die('<P>Error sending request');
		
		//Xml string is parsed and creates a DOM Document object
		$responseDoc = new DOMDocument();
		$responseDoc->loadXML($responseXml);

		//get any error nodes
		$errors = $responseDoc->getElementsByTagName('Errors');
		$response = simplexml_import_dom($responseDoc);
		
		//if there are error nodes
		if ($errors->length > 0) {
		    
			echo '<P><B>eBay returned the following error(s):</B>';
		    //display each error
		    //Get error code, ShortMesaage and LongMessage
		    $code = $errors->item(0)->getElementsByTagName('ErrorCode');
		    $shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
		    $longMsg = $errors->item(0)->getElementsByTagName('LongMessage');
		    
		    //Display code and shortmessage
		    echo '<P>', $code->item(0)->nodeValue, ' : ', str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
		    
		    //if there is a long message (ie ErrorLevel=1), display it
		    if (count($longMsg) > 0)
		        echo '<BR>', str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
		}else { //If there are no errors, continue
		    if(isset($_GET['debug']))
		    {  
		       header("Content-type: text/xml");
		       print_r($responseXml);
		    }else{
		    	//$response = simplexml_import_dom($responseDoc);
				
					//echo $entries;
					//print_r($response->OrderArray);exit();
				    //$orders = $response->OrderArray->Order;
				    $result = $response->Timestamp;
				    return $result;
				
		    }
		}
		
	}
	
	public static function CompleteSale($params){
	
		extract($params);
	
		//SiteID must also be set in the Request's XML
		//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
		//SiteID Indicates the eBay site to associate the call with
		$siteID = 0;
		//the call being made:
		$verb = 'CompleteSale';
		//$userToken = 'AgAAAA**AQAAAA**aAAAAA**W5VCVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFkoGjC5SBpQmdj6x9nY+seQ**FUwDAA**AAMAAA**fH9Q/KR6cvLHrieYU/y91ISc/thXLlX1gXB6UEL7BcUpcIsv00KK+9msaAO5k0qTSdq284XJ5xqTkkBe9bn6OZ94V9Nncb8PoRububB5ryf4bwgmUFgmjNCX79AswEaaRGs50GWMrTZrCU+kmrU5UFTtLIBNmk11Dg2Q2BZ3NyPdTwnCosQfWTmHr3/35/24hTdstuGPajRlMkMgvhVyhePw5O57a90bjJheidmJuVmMuLGsEhojSLzWrD4YccIeDPy69OM33nmWe3vsKTdRVI998aXXsomIH9tFA2FamN6c18HZCB99ANxhPnqAf5LX6AChVG/IDfRAbekYOC7Ci+fLOPAZEmkU49YQVijW2PPfm34y/qzS9QdZQUAggzVGblZmsHNO06dSnrsYPImvf9hhCiw3yNbpLpN+Bj/bDtALn5rb5Q4fa4kCDJ7UJ1FDG7vH/9XsyqQssELtmVMGPN0pBU92+nAbKWWyLy/PIojS2fhzHa+d0qqqLrhZ3MR/EsiQ6FHPh6VCynxba1ZeTza6lY+TVxqr16tLUaaIJlZVJVGTGZnUYKGlaTx6a7jhQQWEur2R2RDA5ymIecXfxGqOgSWVjI4N/jzYlxhAZFQJ691LaRv9652Jp6XXC+nDO2OGqGUTF8AVeLPG1iSa2a8G8cvPj/UHciKTUtiWkUqMqmd9/ERzQ83XhpQycorMJi30hOv/L9e3EOAIGnCjtJTUtpjBzof71yO1nYfr+ecCtNCqb/MaeoeRwdr5Sxpe';
		//au
		//$userToken = 'AgAAAA**AQAAAA**aAAAAA**nKhCVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AFkIKnCpCGog+dj6x9nY+seQ**FUwDAA**AAMAAA**5Nf8c8UF7xvNoOBcs5VAC7Q7laTk6RK8hqGDgXkI739V5/Ib3xlTFJkvigqJStBfQd90jCNIyAfHpaGrkYm00Uq5+k2K+QufQidtZmNYINohISUXQgCDJAQvipHlksNmNlL8eMZWWFlsSTRwQLgwEfPkS6VM3bUuuM0lnZalKzZCSm/tQWxVvzSkySzdcMqZbd6Hz22ZLe95zK3X5UFdwGF8VwBOFXV/BW4HHkFYpIAvkMKmi4TPkhA9cscodY1F3L8iC1vehQnRWUiGZdpdn21KPH7CchVrSI6ymmmkYyvPFODAWOGvtgkob7xPkmlaXUA0pd1ZIbtcpRp9aSL/z87lz5sZhok7UK6zCsXA+o759r45nCYE5hp+DEW3EiI19zqVsU3Hn6ECLo7mx75dAr1idF2rYpmMVJpwsdVDxgwGxnzexIF9vVfh5OFCwLTenEfMVFnxByls9DZPQL/p09wd4FgtbWrTkJ8JDT4QxUSR3st/oEx4Oeo+mzSREp1ajtdeEL0gkSX6k1Hy8HJ+4lZ3sq77mVdiNOe3dwQrNSb4gwHLfke3rW2Rve8d42swfF3a4V6mkUfRHL36U/HGCrQq76b4d8Ymm/KOjG/zb5vB3OLoOo0Q6+5yEPxenzXP+8Sxx/v7OT9w4rEEvy8x3r4Q1fDXUJVQ5wmj29VA9KE3XSLCnyEtJfokKC3BZ+KVswrB2wYtZGm4bT/peabnZ/SBFH+uPy3I2wIX1ao2zX0vH4z3KJuBLk4A4TNyduRz';

		///Build the request Xml string
		$requestXmlBody = '<?xml version="1.0" encoding="utf-8" ?>';
		$requestXmlBody .= '<CompleteSaleRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
		//$requestXmlBody .= "<ItemID>" . $ItemID . "</ItemID>";
		$requestXmlBody .= "<OrderID>" . $OrderID . "</OrderID>";
		//$requestXmlBody .= "<OrderLineItemID>" . $OrderLineItemID . "</OrderLineItemID>";
		//$requestXmlBody .= "<Paid>" . $Paid . "</Paid>";
		$requestXmlBody .= "<Shipment>";
		$requestXmlBody .= "	<Notes>" . $Notes . "</Notes>";
		$requestXmlBody .= "	<ShipmentTrackingDetails>";
		$requestXmlBody .= "		<ShipmentTrackingNumber>" . $ShipmentTrackingNumber . "</ShipmentTrackingNumber>";
		$requestXmlBody .= "		<ShippingCarrierUsed>" . $ShippingCarrierUsed . "</ShippingCarrierUsed>";
		$requestXmlBody .= "	</ShipmentTrackingDetails>";
		$requestXmlBody .= "	<ShippedTime>" . $ShippedTime . "</ShippedTime>";
		$requestXmlBody .= "</Shipment>";
		$requestXmlBody .= "<Shipped>" . $Shipped . "</Shipped>";
		//$requestXmlBody .= "<TransactionID>" . $TransactionID . "</TransactionID>";
		$requestXmlBody .= '<DetailLevel>ReturnAll</DetailLevel>';
		$requestXmlBody .= "<RequesterCredentials><eBayAuthToken>$userToken</eBayAuthToken></RequesterCredentials>";
		$requestXmlBody .= '</CompleteSaleRequest>';
	
		//Create a new eBay session with all details pulled in from included keys.php
		//$session = new eBaySession($userToken, $devID, $appID, $certID, $serverUrl, $compatabilityLevel, $siteID, $verb);
		$session = new eBaySession($userToken,$siteID, $verb);
	
		//send the request and get response
		$responseXml = $session->sendHttpRequest($requestXmlBody);
		if (stristr($responseXml, 'HTTP 404') || $responseXml == '')
			die('<P>Error sending request');
	
			//Xml string is parsed and creates a DOM Document object
			$responseDoc = new DOMDocument();
			$responseDoc->loadXML($responseXml);
	
			//get any error nodes
			$errors = $responseDoc->getElementsByTagName('Errors');
			$response = simplexml_import_dom($responseDoc);
			//$entries = $response->PaginationResult->TotalNumberOfEntries;

			//if there are error nodes
			if ($errors->length > 0) {
	
				echo '<P><B>eBay returned the following error(s):</B>';
				//display each error
				//Get error code, ShortMesaage and LongMessage
				$code = $errors->item(0)->getElementsByTagName('ErrorCode');
				$shortMsg = $errors->item(0)->getElementsByTagName('ShortMessage');
				$longMsg = $errors->item(0)->getElementsByTagName('LongMessage');
	
				//Display code and shortmessage
				echo '<P>', $code->item(0)->nodeValue, ' : ', str_replace(">", "&gt;", str_replace("<", "&lt;", $shortMsg->item(0)->nodeValue));
	
				//if there is a long message (ie ErrorLevel=1), display it
				if (count($longMsg) > 0)
					echo '<BR>', str_replace(">", "&gt;", str_replace("<", "&lt;", $longMsg->item(0)->nodeValue));
			}else { //If there are no errors, continue
				if(isset($_GET['debug']))
				{
					header("Content-type: text/xml");
					print_r($responseXml);
				}else{
					//$response = simplexml_import_dom($responseDoc);
					//if ($entries == 0) {
					//    echo "Sorry No entries found in the Time period requested. Change CreateTimeFrom/CreateTimeTo and Try again";
					//} else {
					//echo $entries;
					print_r($response->OrderArray->Order);exit();
					//$orders = $response->OrderArray->Order;
					$order = $response->OrderArray->Order;
					return $order;
					//}
				}
			}
	
	}

}
?>