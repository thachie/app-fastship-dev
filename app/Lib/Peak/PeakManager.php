<?php
namespace App\Lib\Peak;
class PeakManager
{
	public static $username = "fastship_peakapi_uat";
	public static $password = "oTy5fKTt7qbIgD8q09mB";
	public static $userToken = "046342fe-2391-4ab8-a0d1-87ed6deac0b5";
	public static $firstname = "Wootinun";
	public static $lastname = "Sungong";
	public static $email = "oak@fastship.co";
	/*public static $typeId = 1;
	public static $taxNumber = "0105557123422";
	public static $isVatRegistered = 1;
	public static $branchCode = "00000";
	public static $addressNumber = "850/13";
	public static $alley = "ลาดพร้าว";
	public static $road = "ลาดพร้าว";
	public static $subDistrict = "วังทองหลาง";
	public static $district = "วังทองหลาง";
	public static $province = "กรุงเทพมหานคร";
	public static $country = "thailand";
	public static $postCode = "10310";
	public static $callCenterNumber = "029532780";
	public static $faxNumber = "027751038";
	public static $website = "www.peakengine.com";*/

	public static function authentication(){
		
		$url = 'http://peakengineapidev.azurewebsites.net/api/v1/clienttoken';
		
		$ch = curl_init();
		
		$authenticationDetails = array(
			'PeakClientToken' => array(
				'connectId' => self::$username,
			    'password' => self::$password,
			),
		);
		$encodedAuthenticationDetails = json_encode($authenticationDetails);
		//$authenticationUrl = $url;
		
        $headers = PeakManager::buildHeader();
        
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedAuthenticationDetails);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($ch);

		if (false === $response) {
		    echo 'Request unsuccessful' . PHP_EOL;
		    curl_close($ch);
		    exit(1);
		}
		$responseCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$responseBody = json_decode($response);
		curl_close($ch);

		if (200 !== $responseCode) {
		    echo 'Authentication failed' . PHP_EOL;
		    foreach ($responseBody->errors as $error) {
		        echo $error->code . ': ' . $error->message . PHP_EOL;
		    }
		    exit(1);
		}

		$authorisationToken = $responseBody->PeakClientToken->token;
		//echo 'Authentication success with token ' . $authorisationToken . PHP_EOL;

		return $authorisationToken;
		
	}

	public static function userToken($clientToken){

		$url = 'http://peakengineapidev.azurewebsites.net/api/v1/usertoken';
	
		$ch = curl_init();
	
		$callback = "http://ship.cloudcommerce.co/peak/callback";
		$requestDetails = array(
			'PeakUserToken' => array(
				'callBackUrl' => $callback,
				'user' => array(
					'userEmail' => self::$email,
					'firstName' => self::$firstname,
					'lastName' => self::$lastname,
				),
				'organization' => array(
					'name' => self::$firstname."".self::$lastname,
					'typeId' => self::$typeId,
					'taxNumber' => self::$taxNumber,
					'isVatRegistered' => self::$isVatRegistered,
					'branchCode' => self::$branchCode,
					'addressNumber' => self::$addressNumber,
					'alley' => self::$alley,
					'road' => self::$road,
					'subDistrict' => self::$subDistrict,
					'district' => self::$district,
					'province' => self::$province,
					'country' => self::$country,
					'postCode' => self::$postCode,
					'callCenterNumber' => self::$callCenterNumber,
					'faxNumber' => self::$faxNumber,
					'email' => self::$email,
					'website' => self::$website,
				),
			),
		);
		
		
		
		$encodedRequestDetails = json_encode($requestDetails);
		//$authenticationUrl = $url;
		
		$headers = PeakManager::buildHeader($clientToken);

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedRequestDetails);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
		$response = curl_exec($ch);
	
		if (false === $response) {
			echo 'Request unsuccessful' . PHP_EOL;
			curl_close($ch);
			exit(1);
		}
		$responseCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$responseBody = json_decode($response);
		curl_close($ch);
	
		if (200 !== $responseCode) {
			echo 'Authentication failed' . PHP_EOL;
			foreach ($responseBody->errors as $error) {
				echo $error->code . ': ' . $error->message . PHP_EOL;
			}
			exit(1);
		}

		alert($responseBody);
		echo "<hr />";
		$authorisationToken = $responseBody->PeakUserToken->authorizedId;
		//echo 'Authentication success with token ' . $authorisationToken . PHP_EOL;
	
		return $authorisationToken;
	
	}

	public static function authorize($params){
		try
		{
			extract($params);
			
			$url = 'http://peakengineapidev.azurewebsites.net/api/v1/authorizedusertoken';
		
			$ch = curl_init();
			
			$headers = PeakManager::buildHeader($clientToken);
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
			$response = curl_exec($ch);
			// EXECUTE:
			if(curl_errno($ch)){
				throw new Exception(curl_error($ch));
			}
			
			alert($response);
			$responseCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$responseBody = json_decode($response);
			//alert($responseBody);
			curl_close($ch);
		
			
			$authorisationToken = $responseBody->PeakAuthorizedUserToken->userToken;
			//echo 'Authentication success with token ' . $authorisationToken . PHP_EOL;
		
			return $authorisationToken;
		}catch(Exception $e){
			echo 'Error -- '. $e->getMessage();
		}
	}
	
	public static function authorize_BK($params){
		try
		{
			extract($params);
			
			/* Array
			(
				[authenticationCode] => c34ecab500064ad2a50dcfdc64b02d93
				[authenticationId] => 9a4946d690734ecca1b496a0713f2be2
				[clientToken] => 4bfc7742-d465-4084-9aab-6d13c0125a63
			) */
			//alert($params['authenticationCode']);
			//alert($params['authenticationId']);
			//alert($params['clientToken']);
			alert('params');
			alert($params);
			$url = 'http://peakengineapidev.azurewebsites.net/api/v1/authorizedusertoken';
		
			$ch = curl_init();

			/* $requestDetails = array(
				'PeakAuthorizedUserToken' => array(
					'authorizedCode' => $authenticationCode,
					'authorizedId' => $authenticationId,		
				),
			); */
			$requestDetails = array(
				'PeakAuthorizedUserToken' => array(
					'authorizedCode' => $params['authenticationCode'],
					'authorizedId' => $params['authenticationId'],		
				),
			);
		
			echo "<h4>Request</h4>";
			//alert($requestDetails);
		
			//$encodedRequestDetails = json_encode($requestDetails);
			//$encodedRequestDetails = $requestDetails;
			//$authenticationUrl = $url;
			//alert($encodedRequestDetails);
			//die();
			//{"PeakAuthorizedUserToken":{"authorizedCode":"c34ecab500064ad2a50dcfdc64b02d93","authorizedId":"9a4946d690734ecca1b496a0713f2be2"}}
			
			$json = '{
				"PeakAuthorizedUserToken":{
					"authorizedId":"'.$params['authenticationId'].'",
					"authorizedCode":"'.$params['authenticationCode'].'"
				}
			}';
			
			
			//$headers = PeakManager::buildHeader($clientToken,$userToken=null);
			$headers = PeakManager::buildHeader($params['clientToken']);
			
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POST, 1);
			//curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
			$response = curl_exec($ch);
			// EXECUTE:
			if(curl_errno($ch)){
				throw new Exception(curl_error($ch));
			}
			
			echo "<h4>Response</h4>";
			alert($response);
			if (false === $response) {
				echo 'Request unsuccessful' . PHP_EOL;
				curl_close($ch);
				exit(1);
			}
			$responseCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$responseBody = json_decode($response);
			//alert($responseBody);
			curl_close($ch);
		
			if (200 !== $responseCode) {
				echo 'Authentication failed' . PHP_EOL;
				foreach ($responseBody->errors as $error) {
					echo $error->code . ': ' . $error->message . PHP_EOL;
				}
				exit(1);
			}
		
			
			
			$authorisationToken = $responseBody->PeakAuthorizedUserToken->userToken;
			//echo 'Authentication success with token ' . $authorisationToken . PHP_EOL;
		
			return $authorisationToken;
		}catch(Exception $e){
			echo 'Error -- '. $e->getMessage();
		}
	}
	
	public static function createContacts($params)
	{
		try
		{
			extract($params);
			//$url = 'http://peakengineapidev.azurewebsites.net/api/v1/contacts';

			$ch = curl_init();
		
			$requestContacts = array(
				'PeakContacts' => array(
					'contacts' => $contacts,
				),
			);

			$encodedRequestContacts = json_encode($requestContacts);
			$headers = PeakManager::buildHeader($clientToken);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedRequestContacts);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
			$response = curl_exec($ch);
			if(curl_errno($ch)){
				throw new Exception(curl_error($ch));
			}
			
			if(!$response){
			   die("Connection Failure");
			}
			curl_close($ch);
			
			$responseBody = json_decode($response);
			return $responseBody;

			//$authorisationToken = $responseBody->PeakClientToken->token;
			//echo 'Authentication success with token ' . $authorisationToken . PHP_EOL;
			//return $authorisationToken;
		}catch(Exception $e){
			echo 'Error -- '. $e->getMessage();
		}
	}

	public static function curlInvoice($params)
	{
		try
		{
			extract($params);
			//$url = 'http://peakengineapidev.azurewebsites.net/api/v1/invoices';

			$ch = curl_init();
		
			$requestInvoices = array(
				'PeakInvoices' => array(
					'invoices' => $invoices,
				),
			);
			//alert($requestInvoices);
			$encodedInvoices = json_encode($requestInvoices);
			
			$headers = PeakManager::buildHeader($clientToken);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedInvoices);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
			$response = curl_exec($ch);
			if(curl_errno($ch)){
				throw new Exception(curl_error($ch));
			}
			
			if(!$response){
			   die("Connection Failure");
			}
			curl_close($ch);

			$responseBody = json_decode($response);
			return $responseBody;
		}catch(Exception $e){
			echo 'Error -- '. $e->getMessage();
		}
	}

	public static function callAPI_test($params)
	{
		try
		{
			
			extract($params);
			alert($data);die();
			$username = "fastship_peakapi_uat";
			$curl = curl_init();
			switch ($method){
				case "POST":
					curl_setopt($curl, CURLOPT_POST, 1);
					if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
					break;
				case "PUT":
					curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
					if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
					break;
				default:
					if ($data)
					//$url = sprintf("%s?%s", $url, http_build_query($data));
					$url = $url.'?'.$data; //sprintf("%s?%s", $url, http_build_query($data));
			}

			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$time = gmdate("YmdHis");
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
					'Cache-Control: no-cache',
					'Content-type: application/json; charset=utf-8',
					'Time-Stamp: ' . $time ,
					'Time-Signature: ' .  hash_hmac("sha1", $time, $username),
					'Client-Token: ' . $clientToken,
				)
			);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			//alert($url);
			// EXECUTE:
			$result = curl_exec($curl);

			if(curl_errno($curl)){
				throw new Exception(curl_error($curl));
			}
			
			if(!$result){
			   die("Connection Failure");
			}
			curl_close($curl);
			return $result;
		}catch(Exception $e){
			echo 'Error -- '. $e->getMessage();
		}
	}
	
	public static function createProduct($params){
	
		extract($params);
		//alert($clientToken);die();
		//$url = 'http://peakengineapidev.azurewebsites.net/api/v1/products';

		$ch = curl_init();
	
		$requestDetails = array(
			'PeakProducts' => array(
				'products' => $products,
			),
		);

		$encodedRequestDetails = json_encode($requestDetails);
		//$authenticationUrl = $url;
	
		$headers = PeakManager::buildHeader($clientToken,self::$userToken);
	
		//print_r($encodedRequestDetails);
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedRequestDetails);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
		$response = curl_exec($ch);
		if(curl_errno($ch)){
			throw new Exception(curl_error($ch));
		}
		
		if(!$response){
		   die("Connection Failure");
		}
		curl_close($ch);

		$responseBody = json_decode($response);
		return $responseBody;
	}


	public static function curl_peak_api($params)
	{
		try
		{
			extract($params);
			$ch = curl_init();
			$headers = PeakManager::buildHeader($clientToken);
			alert($params);die();
			switch ($method){
				case "POST":
					curl_setopt($ch, CURLOPT_POST, true);
					if ($jsonData)
					curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
					break;
				case "PUT":
					curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
					if ($jsonData)
					curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);	 					
					break;
				default:
					if ($jsonData)
					//$url = sprintf("%s?%s", $url, http_build_query($data));
					$url = $url.'?'.$jsonData; //sprintf("%s?%s", $url, http_build_query($data));
			}
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
			$response = curl_exec($ch);
			if(curl_errno($ch)){
				throw new Exception(curl_error($ch));
			}
			
			if(!$response){
			   die("Connection Failure");
			}
			curl_close($ch);

			$responseBody = json_decode($response);
			return $responseBody;
		}catch(Exception $e){
			echo 'Error -- '. $e->getMessage();
		}
	}

	public static function buildHeader($clientToken=null)
	{
		$time = gmdate("YmdHis");
		$ret = array(
			//"Cache-Control: no-cache",
			"Content-type: application/json; charset=utf-8",
			"Time-Stamp: ". $time,
			"Time-Signature: ". hash_hmac("sha1", $time, self::$username),
		);
		if(isset($clientToken)){
			$ret[] = "Client-Token: ".$clientToken;
		}
		$ret[] = "User-Token: ".self::$userToken;
		//$ret[] = "User-Token: 452b066b-0b1b-4116-82e4-9f4d66db739c";
		return  $ret;
	}
	
	public static function callApi($method, $url, $data)
	{
		try
		{
			$curl = curl_init();
			switch ($method){
				case "POST":
					curl_setopt($curl, CURLOPT_POST, 1);
					if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
					break;
				case "PUT":
					curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
					if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
					break;
				default:
					if ($data)
					//$url = sprintf("%s?%s", $url, http_build_query($data));
					$url = $url.'?'.$data; //sprintf("%s?%s", $url, http_build_query($data));
			}

			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$time = gmdate("YmdHis");
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
					'Cache-Control: no-cache',
					'Content-type: application/json; charset=utf-8',
					'Time-Stamp: ' . $time ,
					'Time-Signature: ' .  hash_hmac("sha1", $time, self::$username)
				)
			);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			alert($url);
			// EXECUTE:
			$result = curl_exec($curl);

			if(curl_errno($curl)){
				throw new Exception(curl_error($curl));
			}
			
			if(!$result){
			   die("Connection Failure");
			}
			curl_close($curl);
			return $result;
		}catch(Exception $e){
			echo 'Error -- '. $e->getMessage();
		}
	}


}