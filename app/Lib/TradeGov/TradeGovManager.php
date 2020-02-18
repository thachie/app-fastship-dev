<?php
namespace App\Lib\TradeGov;
class TradeGovManager{

	public static $apikey = "vFHr7yABxgHR3B-r-DJcbHdY";
		
	public static function search($keyword){
		
		//$url = 'http://scgyamatodev.flare.works/api/authentication';
		$url = 'https://api.trade.gov/v1/de_minimis/search?api_key=' . self::$apikey . '&countries=' . $keyword;

		$ch = curl_init();

		//$headers = array('Content-Type: application/json');
		$headers = array();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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
		//print_r($responseBody);
		
		return isset($responseBody->results[0])?$responseBody->results[0]:null;
		
	}
	
	public static function tariff_rates($keyword){
	    
	    //$url = 'http://scgyamatodev.flare.works/api/authentication';
	    $url = 'https://api.trade.gov/v1/tariff_rates/search?api_key=' . self::$apikey . '&q=' . $keyword;
	    
	    $ch = curl_init();
	    
	    //$headers = array('Content-Type: application/json');
	    $headers = array();
	    
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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
	    //print_r($responseBody);
	    return $responseBody->results;
	    
	    //return isset($responseBody->results[0])?$responseBody->results[0]:null;
	    
	}
}
?>