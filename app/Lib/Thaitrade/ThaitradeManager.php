<?php
namespace App\Lib\Thaitrade;
use Exception;
class ThaitradeManager
{
	public static $username = "fastship";
	public static $password = "G]kZzc84K'7pQ\$U<";
	public static $userToken = "046342fe-2391-4ab8-a0d1-87ed6deac0b5";
	public static $firstname = "Wootinun";
	public static $lastname = "Sungong";
	public static $email = "oak@fastship.co";

	//get orders
	public static function getSoldOrders($sellerId=193933){
		
	    //$sellerId=193933;
		//$url = 'https://ditp-uat.thaitrade.com/rest/V1/Thaitrade/fastship/' . $sellerId; //sandbox
		$url = 'https://www.thaitrade.com/rest/V1/Thaitrade/fastship/' . $sellerId; //production
	    
		$ch = curl_init();
		
		$token = self::userToken();
		//print_r($token);
		$headers = ThaitradeManager::buildHeader($token);
        
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($ch);

		if (false === $response) {
		    echo 'Request unsuccessful' . PHP_EOL;
		    curl_close($ch);
		    exit(1);
		}
		$responseCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$responseBody = json_decode($response,true);
		curl_close($ch);
		//print_r($headers);
		
		if (200 !== $responseCode) {
		    echo $responseCode;
		    echo 'Authentication failed' . PHP_EOL;
		    foreach ($responseBody->errors as $error) {
		        echo $error->code . ': ' . $error->message . PHP_EOL;
		    }
		    exit(1);
		}
		$status = $responseBody[0]['success'];
		$data = $responseBody[0]['body'];

		return $data;
		/*
		if($status){
		    //print_r($data);
		    return $data;
		}else{
		    echo "API failed";
		    return false;
		}
*/
	}

	public static function userToken(){

		//$url = 'https://ditp-uat.thaitrade.com/rest/V1/integration/admin/token'; //sandbox
		$url = 'https://www.thaitrade.com/rest/V1/integration/admin/token'; //production
	
		$ch = curl_init();

		$requestDetails = array(
		    'username' => self::$username,
		    'password' => self::$password,
		);

		$encodedRequestDetails = json_encode($requestDetails);
		//$authenticationUrl = $url;
		
		$headers = ThaitradeManager::buildHeader();

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

		return $responseBody;
	
	}
	
	
	
	public static function createContacts($refId,$sellerId,$registerDate)
	{
	    
	    //$url = 'https://ditp-uat.thaitrade.com/rest/V1/Thaitrade/fastship/customer/'; //sandbox
	    $url = 'https://www.thaitrade.com/rest/V1/Thaitrade/fastship/customer/'; //production
	    
	    $ch = curl_init();
	    
	    $token = self::userToken();

	    $headers = ThaitradeManager::buildHeader($token);
	    
	    $requestDetails = array(
	       'customerData' => array(
    	        'ref_id' => "$refId",
    	        'seller_id' => "$sellerId",
    	        'register_date' => "$registerDate",
	       ),
	    );
	    
	    $encodedRequestDetails = json_encode($requestDetails);
	    
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedRequestDetails);
	    curl_setopt($ch, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    
	    $response = curl_exec($ch);
	    
	    if (false === $response) {
	        echo 'Request unsuccessful' . PHP_EOL;
	        curl_close($ch);
	        exit(1);
	    }
	    $responseCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    $responseBody = json_decode($response,true);
	    curl_close($ch);

	    //
	    if (200 !== $responseCode) {
	        echo $responseCode;
	        echo 'Authentication failed' . PHP_EOL;
	        foreach ($responseBody->errors as $error) {
	            echo $error->code . ': ' . $error->message . PHP_EOL;
	        }
	        exit(1);
	    }
	    $status = $responseBody[0]['success'];
	    $data = $responseBody[0]['body'];
	    print_r($responseBody);
	    
	    if($status){
	        //print_r($data);
	        return $data;
	    }else{
	        echo $data;
	        return false;
	    }
	    
	}

	/**
	 * Build and return the header require for calling lalamove API
	 * @return {Object} an associative aray of lalamove header
	 */
	public static function buildHeader($clientToken=null)
	{
		$time = gmdate("YmdHis");
		$ret = array(
			//"Cache-Control: no-cache",
			"Content-type: application/json; charset=utf-8",
			//"Time-Stamp: ". $time,
			//"Time-Signature: ". hash_hmac("sha1", $time, self::$username),
		);
		if(isset($clientToken)){
			$ret[] = "Authorization: Bearer ".$clientToken;
		}
		//$ret[] = "User-Token: ".self::$userToken;
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
			print_r($url);
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