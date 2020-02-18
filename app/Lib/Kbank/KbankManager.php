<?php
namespace App\Lib\Kbank;

use Exception;
class KbankManager
{
    public static $partnerId = "PTR6530390";
	public static $clientSecret = "54b279b2a41c4077a76b06411d3f8331";
	public static $merchantId = "KB518368480835";
	public static $terminalId = "09000107";

    /*
     * Generate dynamic QR Code for Thai QR Payment
     */
	public static function getQr($params)
	{
	    
	    $url = 'https://APIPORTAL.kasikornbank.com:12002/pos/qr_request/'; //sandbox
	    
	    $ch = curl_init();

	    $headers = KbankManager::buildHeader($token);
	    
	    $transactionId = date("YmdHis");
	    $requestDate = date("c", time()); //2018-04-05T12:30:00+07:00
	    $qrType = 3;
	    $amount = isset($params['amount']) ? $params['amount']:0;
	    $currency = "THB";
	    $pickupId = isset($params['pickupId']) ? $params['pickupId']:"";
	    $customerId = isset($params['customerId']) ? $params['customerId']:"";
	    
	    $meta = "";
	    if(isset($params['shipments']) && is_array($params['shipments'])){
	        $cnt = 1;
	        foreach($params['shipments'] as $id => $rate){
	            $meta .= $id . " " . $rate;
	            if($cnt < sizeof($params['shipments'])) $meta .= ",";
	            $cnt++;
	        }
	    }

	    $requestDetails = array(
	        'partnerTxnUid' => $transactionId,     //ID to uniquely define each request from partner
	        'partnerId' => self::$partnerId,       //Partner identifier. This ID will be provided to each partner by KBank
	        'partnerSecret' => self::$clientSecret,//Secret key to identify each partner
	        'requestDt' => $requestDate,           //Timestamp when partner send this request to KBank in ISO 8601 format
	        'merchantId' => self::$merchantId,     //Shop's merchant ID in KBank's system
	        'terminalId' => self::$terminalId,     //Shop's terminal ID in KBank's system
	        'qrType' => $qrType,                   //Type of QR payment. Please refer to Appendix for possible values
	        'txnAmount' => $amount,                //Amount of transaction. Must be positive number. Number of digits depends on each currency.
	        'txnCurrencyCode' => $currency,        //Currency code of transaction amount in ISO 4217. Currently support only "THB"
	        'reference1' => $pickupId,             //Transaction reference 1
	        'reference2' => $customerId,           //Transaction reference 2
	        'reference3' => null,                  //Transaction reference 3
	        'reference4' => null,                  //Transaction reference 4
	        'metadata' => $meta                    //Item details in following format
	    );

	    $encodedRequestDetails = json_encode($requestDetails);
	    print_r($encodedRequestDetails);
	    
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
	    $status = $responseBody['statusCode'];
	    $data = $responseBody;
	    
	    //print_r($responseBody);
	    
	    if($status){
	        //print_r($data);
	        return $data;
	    }else{
	        echo $data;
	        return false;
	    }
	    
	}
	
	/*
	 * Inquiry QR Code Status Whether it is paid or not.
	 */
	public static function inquirePayment($params)
	{
	    
	    $url = 'https://APIPORTAL.kasikornbank.com:12002/pos/inquire_payment/v2/'; //sandbox
	    
	    $ch = curl_init();
	    
	    $headers = KbankManager::buildHeader($token);
	    
	    $transactionId = date("YmdHis");
	    $requestDate = date("c", time()); //2018-04-05T12:30:00+07:00
	    $qrType = 3;
	    $origPartnerTxnUid = isset($params['originId']) ? $params['originId']:"";

	    $requestDetails = array(
	        'partnerTxnUid' => $transactionId,     //ID to uniquely define each request from partner
	        'partnerId' => self::$partnerId,       //Partner identifier. This ID will be provided to each partner by KBank
	        'partnerSecret' => self::$clientSecret,//Secret key to identify each partner
	        'requestDt' => $requestDate,           //Timestamp when partner send this request to KBank in ISO 8601 format
	        'merchantId' => self::$merchantId,     //Shop's merchant ID in KBank's system
	        'terminalId' => self::$terminalId,     //Shop's terminal ID in KBank's system
	        'qrType' => $qrType,                   //Type of QR payment. Please refer to Appendix for possible values
	        'origPartnerTxnUid' => $origPartnerTxnUid, //Amount of transaction. Must be positive number. Number of digits depends on each currency.
	    );
	    
	    $encodedRequestDetails = json_encode($requestDetails);
	    print_r($encodedRequestDetails);
	    
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
	    $status = $responseBody['statusCode'];
	    $data = $responseBody['qrCode'];
	    
	    //print_r($responseBody);
	    
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

		$ret = array(
		    "cache-control: no-cache",
			"Content-Type: application/json; charset=utf-8",
		);

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