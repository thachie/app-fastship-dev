<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include(app_path() . '/Lib/ebay.restfulapi.config.php');
function eBayRESTfulAPIs($params)
{ 
	try
	{
		extract($params);
		$curl = curl_init();
		switch ($method){
			case "POST":
				curl_setopt($curl, CURLOPT_POST, true);
				if ($jsonData)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if ($jsonData)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);	 					
				break;
			default:
				if ($jsonData)
				//$url = sprintf("%s?%s", $url, http_build_query($data));
				$url = $url.'?'.$jsonData; //sprintf("%s?%s", $url, http_build_query($data));
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//$time = gmdate("YmdHis");
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Accept: application/json',
				'Accept-Charset: utf-8',
				//'Accept-Encoding: application/gzip',
				'Content-Language: en-US',
				'Content-type: application/json; charset=utf-8',
				'Authorization: Bearer '.$userToken,
				'X-EBAY-C-MARKETPLACE-ID: EBAY_US',
			)
		);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		
		// EXECUTE:
		$response = curl_exec($curl);

		/*if(curl_errno($curl)){
			throw new Exception(curl_error($curl));
		}*/

		// Check if any error occurred
		/*if(curl_exec($curl) === false)
		{
		    echo 'Curl error: ' . curl_error($curl);
		}else{
		    //echo 'Operation completed without any errors';
		}*/
		
		if(!$response){
		   die("Connection Failure");
		}
		curl_close($curl);

		//$responseBody = json_decode($response);
		return $response;
	}catch(Exception $e){
		echo 'Error -- '. $e->getMessage();
	}
}

function eBayAPIsUpdateTracking($params)
{ 
	try
	{
		extract($params);
		$curl = curl_init();
		switch ($method){
			case "POST":
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
				if ($jsonData)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if ($jsonData)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);	 					
				break;
			default:
				if ($jsonData)
				//$url = sprintf("%s?%s", $url, http_build_query($data));
				$url = $url.'?'.$jsonData; //sprintf("%s?%s", $url, http_build_query($data));
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//$time = gmdate("YmdHis");
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Accept: application/json',
				'Accept-Charset: utf-8',
				'Content-Language: en-US',
				//'Content-type: application/json; charset=utf-8',
				'Content-type: application/json',
				'Authorization: Bearer '.$userToken,
				'X-EBAY-C-MARKETPLACE-ID: EBAY_US',
			)
		);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		
		// EXECUTE:
		$response = curl_exec($curl);
		//alert($response);
		if(curl_errno($curl)){
			throw new Exception(curl_error($curl));
		}

		// Check if any error occurred
		/*if(curl_exec($curl) === false)
		{
		    echo 'Curl error: ' . curl_error($curl);
		}else{
		    //echo 'Operation completed without any errors';
		}*/
		
		if(!$response){
		   die("Connection Failure");
		}
		curl_close($curl);

		//$responseBody = json_decode($response);
		return $response;
	}catch(Exception $e){
		echo 'Error -- '. $e->getMessage();
	}
}

function eBayAccessToken($params)
{ 
	try
	{
		extract($params);
		$curl = curl_init();
		switch ($method){
			case "POST":
				curl_setopt($curl, CURLOPT_POST, true);
				if ($jsonData)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if ($jsonData)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);	 					
				break;
			default:
				if ($jsonData)
				//$url = sprintf("%s?%s", $url, http_build_query($data));
				$url = $url.'?'.$jsonData; //sprintf("%s?%s", $url, http_build_query($data));
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//$time = gmdate("YmdHis");
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Content-type: application/x-www-form-urlencoded',
				'Authorization: Basic '.Authorization,
			)
		);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		
		// EXECUTE:
		$response = curl_exec($curl);

		/*if(curl_errno($curl)){
			throw new Exception(curl_error($curl));
		}*/

		// Check if any error occurred
		/*if(curl_exec($curl) === false)
		{
		    echo 'Curl error: ' . curl_error($curl);
		}else{
		    //echo 'Operation completed without any errors';
		}*/
		
		if(!$response){
		   die("Connection Failure");
		}
		curl_close($curl);

		//$responseBody = json_decode($response);
		return $response;
	}catch(Exception $e){
		echo 'Error -- '. $e->getMessage();
	}
}

function eBayRefreshToken($params)
{ 
	try
	{
		extract($params);
		$curl = curl_init();
		switch ($method){
			case "POST":
				curl_setopt($curl, CURLOPT_POST, true);
				if ($jsonData)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if ($jsonData)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);	 					
				break;
			default:
				if ($jsonData)
				//$url = sprintf("%s?%s", $url, http_build_query($data));
				$url = $url.'?'.$jsonData; //sprintf("%s?%s", $url, http_build_query($data));
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//$time = gmdate("YmdHis");
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Content-type: application/x-www-form-urlencoded',
				'Authorization: Basic '.Authorization,
			)
		);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		
		// EXECUTE:
		$response = curl_exec($curl);

		/*if(curl_errno($curl)){
			throw new Exception(curl_error($curl));
		}*/

		// Check if any error occurred
		/*if(curl_exec($curl) === false)
		{
		    echo 'Curl error: ' . curl_error($curl);
		}else{
		    //echo 'Operation completed without any errors';
		}*/
		
		if(!$response){
		   die("Connection Failure");
		}
		curl_close($curl);

		return $response;
	}catch(Exception $e){
		echo 'Error -- '. $e->getMessage();
	}
}

function FS_RESTfulAPIs($params)
{ 
	try
	{
		extract($params);
		//$curl = curl_init('http://example.org/someredirect');
		$curl = curl_init($call_back_url);
		//$curl = curl_init();
		switch ($method){
			case "POST":
				curl_setopt($curl, CURLOPT_POST, true);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
				if ($jsonData)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if ($jsonData)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);	 					
				break;
			default:
				if ($jsonData)
				//$url = sprintf("%s?%s", $url, http_build_query($data));
				$url = $url.'?'.$jsonData; //sprintf("%s?%s", $url, http_build_query($data));
		}

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
			)
		);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		
		// EXECUTE:
		$response = curl_exec($curl);
		//alert($response);

		// Check if any error occurred
		/*if(curl_exec($curl) === false)
		{
		    echo 'Curl error: ' . curl_error($curl);
		}else{
		    //echo 'Operation completed without any errors';
		}*/
		
		if(!$response){
		   die("Connection Failure");
		}
		curl_close($curl);

		return $response;
	}catch(Exception $e){
		echo 'Error -- '. $e->getMessage();
	}
}

function FSRESTfulAPIs($params)
{ 
	try
	{
		extract($params);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		    	'Content-Type: application/json',
		    	//'Content-Length: ' . strlen($jsonData)
			)
		);

		// EXECUTE:
		$response = curl_exec($curl);
		//alert($response);
		// Check if any error occurred
		/*if(curl_exec($curl) === false)
		{
		    echo 'Curl error: ' . curl_error($curl);
		}else{
		    //echo 'Operation completed without any errors';
		}*/

		if(!$response){
		   die("Connection Failure");
		}
		curl_close($curl);
		return $response;
	}catch(Exception $e){
		echo 'Error -- '. $e->getMessage();
	}
}

function FS_PullOrder($params)
{ 
	try
	{
		extract($params);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		    	'Content-Type: application/json',
			)
		);

		// EXECUTE:
		$response = curl_exec($curl);
		//alert($response);
		
		if(!$response){
		   die("Connection Failure");
		}
		curl_close($curl);
		return $response;
	}catch(Exception $e){
		echo 'Error -- '. $e->getMessage();
	}
}


/* duplicate with inc.functions.php -> cause error while create shipment
function alert()
{
	$arg_list = func_get_args();
	foreach ($arg_list as $k => $v){
		print "<pre>";
		print_r( $v );
		print "</pre>";
	}
}
*/
/*
function callAPI($method, $url, $data){
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

		// OPTIONS:
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				'Authorization: Basic Y2xvdWRjb206MTIzNDU=',
				'Content-Type: application/json',
			)
		);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

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
*/
function base_url()
{
	$base_url	= "https://".$_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
	return $base_url;
}

function GEN_Logs($Detail, $File_name = '', $Wtype = '', $Chmode = ''){

	if($File_name == ''){
		//$File_name = "/home/web/library/logs/DEBUG-LOG-" . date("Y-m-d") . ".TXT";
		$File_name = "/opt/bitnami/apache2/htdocs/api_marketplace/ebay_api/logs/DEBUG-LOG-" . date("Y-m-d") . ".TXT";
	}
	if($Wtype == ''){
		$Wtype = "a";
	}
	
	if ($Wtype == 'l'){
		$Wtype = "a";
	}else{
		if($Chmode == '' and !eregi("w", $Wtype)){
			$Detail = "(" . date("Y-m-d H:i:s") . ") - " . $Detail;	
		}
	}
	
	$fp = @fopen($File_name, $Wtype);
	@fwrite($fp, $Detail . "\r\n");
	@fclose($fp);

	if($Chmode != ''){
		$command = "chmod $Chmode $File_name";
		exec($command, $status);
	}
}

function get_status_message(){
	$status = array(
		100 => 'Continue',  
		101 => 'Switching Protocols',  
		200 => 'OK',
		201 => 'Created',  
		202 => 'Accepted',  
		203 => 'Non-Authoritative Information',  
		204 => 'No Content',  
		205 => 'Reset Content',  
		206 => 'Partial Content',  
		300 => 'Multiple Choices',  
		301 => 'Moved Permanently',  
		302 => 'Found',  
		303 => 'See Other',  
		304 => 'Not Modified',  
		305 => 'Use Proxy',  
		306 => '(Unused)',  
		307 => 'Temporary Redirect',  
		400 => 'Bad Request',  
		401 => 'Unauthorized',  
		402 => 'Payment Required',  
		403 => 'Forbidden',  
		404 => 'Not Found',  
		405 => 'Method Not Allowed',  
		406 => 'Not Acceptable',  
		407 => 'Proxy Authentication Required',  
		408 => 'Request Timeout',  
		409 => 'Conflict',  
		410 => 'Gone',  
		411 => 'Length Required',  
		412 => 'Precondition Failed',  
		413 => 'Request Entity Too Large',  
		414 => 'Request-URI Too Long',  
		415 => 'Unsupported Media Type',  
		416 => 'Requested Range Not Satisfiable',  
		417 => 'Expectation Failed',  
		500 => 'Internal Server Error',  
		501 => 'Not Implemented',  
		502 => 'Bad Gateway',  
		503 => 'Service Unavailable',  
		504 => 'Gateway Timeout',  
		505 => 'HTTP Version Not Supported');
	return ($status[$code])?$status[$code]:$status[500];
}

?>