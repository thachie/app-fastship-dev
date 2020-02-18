<?php

function peak_api($method, $url, $data, $clientToken=null, $userToken=null)
{
	try
	{
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
	
function alert()
{
	$arg_list = func_get_args();
	foreach ($arg_list as $k => $v){
		print "<pre>";
		print_r( $v );
		print "</pre>";
	}
}

function callAPI($method, $url, $data){
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
			$url = sprintf("%s?%s", $url, http_build_query($data));
	}

	// OPTIONS:
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			//'APIKEY: 111111111111111111111',
			'Content-Type: application/json',
		)
	);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	// EXECUTE:
	$result = curl_exec($curl);
	if(!$result){
	   die("Connection Failure");
	}
	curl_close($curl);
	return $result;
}

function callAPI_thaiPost($method, $url, $data){
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

function callAPI_thaiPost_BK($method, $url, $data){
	//alert($data);
	try
	{
		$curl = curl_init();
		switch ($method){
			case "POST":
				//echo 'POST';
				curl_setopt($curl, CURLOPT_POST, 1);
				if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				//echo 'PUT';
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
				break;
			default:
				//echo 'GET';
				if ($data)
				//$url = sprintf("%s?%s", $url, http_build_query($data));
				$url = $url.'?'.$data; //sprintf("%s?%s", $url, http_build_query($data));
		}

		// OPTIONS:
		//'Authorization', 'Basic ' + btoa(unescape(encodeURIComponent(YOUR_USERNAME + ':' + YOUR_PASSWORD))
		$USERNAME = 'cloudcom';
		$PASSWORD = '12345';
		$en = base64_encode('cloudcom:12345');
		//alert($url);
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
				//'APIKEY: 111111111111111111111',
				
				//'Authorization: Basic [cloudcom/12345]',
				//'Authorization: ' . $TOKEN
				//'Authorization', 'Basic ' + btoa(unescape(encodeURIComponent($USERNAME + '/' + $PASSWORD)))
				//'Authorization: Basic Authentication [cloudcom/12345]',
				//'Authorization: Basic Authentication ['.$en.']',
				//'Authorization: Basic Authentication '.$en,
				//'Authorization: Basic' . base64_encode("cloudcom:12345"),
				//'Authorization: Basic '.$en,
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

function thaipost($url,$postData)
{
	$USERNAME = 'cloudcom';
	$PASSWORD = '12345';
	//Y2xvdWRjb206MTIzNDU=
	$en = base64_encode('cloudcom:12345');
	//echo $en;die();
	$ch = curl_init();
	$headers[] = 'Authorization: Basic Y2xvdWRjb206MTIzNDU=';
	//$headers[] = "Accept: */*"; 
	//$headers[] = "Connection: Keep-Alive"; 
	$headers[] = 'Content-type: application/json';
	//$headers[] = 'Authorization: Basic '.$en;
	
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	//curl_setopt($ch, CURLOPT_MAXREDIR, 5);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	alert(9999);
	alert($response);
	$ret = curl_close($ch);
	
	return $response;
}

function callAPI_Kerry($method, $url, $data){
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
				'Content-type: application/json; charset=utf-8',
				//'app_id: ',
				//'app_key: ',
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

function test($url, $data)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	    'Content-Type: application/json',
	    'Authorization: Basic Y2xvdWRjb206MTIzNDU=')
	);

	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

	//execute post
	$result = curl_exec($ch);

	//close connection
	curl_close($ch);

	echo $result;
}


function curl_api($url,$postData)
{
	$ch = curl_init();
	$header = array("Content-Type" => "text/html");
	$headers[] = "Accept: */*"; 
	$headers[] = "Connection: Keep-Alive"; 
	$headers[] = "Content-type: text/xml"; 
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	//curl_setopt($ch, CURLOPT_MAXREDIR, 5);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);

	$ret = curl_close($ch);
	
	return $response;
}

function call_xml($url,$postData)
{
	$ch = curl_init();
	$header = array("Content-Type" => "text/xml");
	//$headers[] = "Accept: */*"; 
	//$headers[] = "Connection: Keep-Alive"; 
	//$headers[] = "Content-type: text/xml; charset=UTF-8"; 
	$headers[] = "Content-Type: application/xml; charset=utf-8"; 
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	//curl_setopt($ch, CURLOPT_MAXREDIR, 5);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);

	$ret = curl_close($ch);
	
	return $response;
}

function curl_json($url,$postData)
{
	$ch = curl_init();
	$headers[] = 'Content-type: text/json';
	$headers[] = 'Content-type: application/json; charset=utf-8';
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	//curl_setopt($ch, CURLOPT_MAXREDIR, 5);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	echo $response;
	$ret = curl_close($ch);
	
	return $response;
} 

function curl_transection($url,$postData)
{
	$ch = curl_init();
	$headers[] = 'Content-type: text/json';
	$headers[] = 'Content-type: application/json';
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	//curl_setopt($ch, CURLOPT_MAXREDIR, 5);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	echo $response;
	$ret = curl_close($ch);
	
	return $response;
}

function call_api($url, $post = null, $timeout=25)
{
	$curl = curl_init($url);
	if (is_resource($curl) === true)
	{
	  curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
	  curl_setopt($curl, CURLOPT_FAILONERROR, true);
	  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	  if (isset($post) === true)
	  {
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, (is_array($post) === true) ? http_build_query($post, '', '&') : $post);
	  }
	  $result = curl_exec($curl);
	  curl_close($curl);
	  return $result;
	}
	return FALSE;
}

function ussd_service_shortcode($shortcode)
{
	if($shortcode != ''){
		$ussdCodePost = $shortcode;
		$codeExplode = explode('*',$ussdCodePost);
		if($codeExplode[0] == ""){
			$key = count($codeExplode);
			$code = $codeExplode[$key-1];
			$service_shortcode = $codeExplode[1];
			
			$num = $key-1;
			$sub_shortcode = "";
			//หา sub short code
			for ($i = 2; $i < $num; $i++) {
				$sub_shortcode .= "*".$codeExplode[$i];
			}
			if($sub_shortcode != ""){
				$sub_shortcode .= "*";
			}
		}
		$result['service_shortcode'] = $service_shortcode;
		$result['code'] = $code;
		$result['sub_shortcode'] = $sub_shortcode;
		return $result;
	}else{
		$result['service_shortcode'] = '';
		$result['code'] = '';
		$result['sub_shortcode'] = '';
		return $result;
	}
} 
function generateRandomString($length = null) {
	//$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$characters = '0123456789';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

?>