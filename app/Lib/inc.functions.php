<?php

function alert()
{
	$arg_list = func_get_args();
	foreach ($arg_list as $k => $v){
		print "<pre>";
		print_r( $v );
		print "</pre>";
	}
}

function callAPI_Kbank($method, $url, $data){
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
			'Content-Type: application/json',
			//'x-api-key: skey_test_20236vecNeVYD8bClNwrUlLixcv09884wmEPr', //sandbox
	        'x-api-key: skey_prod_321AICw7zbi6hEwiqz0dbM9qcJ5V75GohdL'
		)
	);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	// EXECUTE:
	$result = curl_exec($curl);
	/*if(curl_exec($curl) === false)
	{
	    echo 'Curl error: ' . curl_error($curl);
	}else{
	    //echo 'Operation completed without any errors';
	}*/
	if(!$result){
	   die("Connection Failure");
	}
	curl_close($curl);
	return $result;
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

/*
PUT /webservice/addMerchant HTTP/1.1
Host: r_dservice.thailandpost.com
Content-Type: application/json
Authorization: Basic Y2xvdWRjb206MTIzNDU=
Cache-Control: no-cache
Postman-Token: 998f489a-a796-1068-3dc9-fef6079dbfc7

{
    "merchantId": "TH0102",
    "merchantName": "บริษัท A สาขา 2",
    "merchantAddress": "211/1 หมู่ที่ 11ถ.แจ้งวัฒนะ",
    "merchantDistinct": "แขวงทุ่งสองห้อง",
    "merchantAmphur": "หลักสี่",
    "merchantProvince": "กรุงเทพฯ",
    "merchantPostcode": "10210",
    "merchantPhoneNumber": "0244557821",
    "merchantEmail": "thailandpost@thailandpost.com",
    "postcodeDrop": "10540"
}

*/

function callAPI_thaiPost($method, $url, $data){
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

function callAPI_Line($method, $url, $data){
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
        'Content-Type: application/x-www-form-urlencoded',
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
/*
function GEN_Logs($Detail, $File_name = '', $Wtype = '', $Chmode = ''){
    if($File_name == ''){
        //$File_name = "/home/web/library/logs/DEBUG-LOG-" . date("Y-m-d") . ".TXT";
        $File_name = "/opt/bitnami/frameworks/laravel/storage/logs/DEBUG-LOG-" . date("Y-m-d") . ".TXT";
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
*/
?>