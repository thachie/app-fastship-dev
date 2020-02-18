<?php
namespace App\Lib\LandedCostIO;

use Exception;

class LandedCostManager
{

	public static $securityKey = "Kqi5Jg5fXTDn5I9kyHSOseca03SWp0BtyZ3pMHtD4VbHmHhPHao5lnnWyKuNbnZN";

	public static function getHS($params)
	{
		try
		{
			extract($params);

			$ch = curl_init();
		
			$url = "https://api.landedcost.io/hscodesearch";
			
			$request = array(
				'description' => $description,
			    'securityKey' => self::$securityKey,
			    'name' => $name,
			    'category' => $category,
			    'sku' => $sku,
			);
			$encodedRequest = json_encode($request);

			$headers = LandedCostManager::buildHeader();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedRequest);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
			$response = curl_exec($ch);
			if(curl_errno($ch)){
				throw new Exception(curl_error($ch));
			}
		
			if (false === $response) {
				echo 'Request unsuccessful' . PHP_EOL;
				curl_close($ch);
				exit(1);
			}
			$responseCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$responseBody = json_decode($response);
			curl_close($ch);

			if (200 !== $responseCode) {
				echo 'API failed' . PHP_EOL;
				foreach ($responseBody->errors as $error) {
					echo $error->code . ': ' . $error->message . PHP_EOL;
				}
				exit(1);
			}

			return $responseBody;
			die();

		}catch(Exception $e){
			echo 'Error -- '. $e->getMessage();
		}
	}

	public static function getLandedCost($params)
	{
	    try
	    {
	        extract($params);
	        
	        $ch = curl_init();
	        
	        $url = "https://api.landedcost.io/calculator";	        
	        
	        $items = array();
	        if(sizeof($products)>0){
	            foreach($products as $product){
	                $items[] = array(
	                    'sku' => $product['sku'],
	                    'description' => $product['description'],
	                    'name' => $product['name'],
	                    'price' => $product['price'],
	                    'quantity' => $product['quantity'],
	                    'category' => $product['category'],
	                    'hsCode' => $product['hsCode'],
	                    'weight' => $product['weight'],
	                    'uom' => $product['uom'],
	                    'countryOfOrigin' => $product['countryOfOrigin'],
	                    'autoClassify' => $product['autoClassify'],
	                );
	            }
	        }
	        
	        $request = array(
	            'securityKey' => self::$securityKey,
	            'carrier' => $carrier,
	            'shippingMethod' => $shippingMethod,
	            'shippingCostTotal' => $shippingCostTotal,
	            'sourceCurrencyCode' => $sourceCurrencyCode,
	            'targetCurrencyCode' => $targetCurrencyCode,
	            'discountTotal' => $discountTotal,
	            'additionalInsuranceTotal' => $additionalInsuranceTotal,
	            'languageCode' => $languageCode,
	            'addresses' => array(
	                0 => array(
	                    'firstName' => $senderFirstname,
	                    'lastName' => $senderLastname,
	                    'address1' => $senderAddress1,
	                    'address2' => $senderAddress2,
	                    'city' => $senderCity,
	                    'regionCode' => $senderState,
	                    'countryCode' => $senderCountry,
	                    'postalCode' => $senderPostcode,
	                    'emailAddress' => $senderEmail,
	                    //'nationalIdentificationNumber' => $senderNin,
	                    'addressType' => "shipFrom",
	                ),
	                1 => array(
	                    'firstName' => $receiverFirstname,
	                    'lastName' => $receiverLastname,
	                    'address1' => $receiverAddress1,
	                    'address2' => $receiverAddress2,
	                    'city' => $receiverCity,
	                    'regionCode' => $receiverState,
	                    'countryCode' => $receiverCountry,
	                    'postalCode' => $receiverPostcode,
	                    'emailAddress' => $receiverEmail,
	                    //'nationalIdentificationNumber' => $receiverNin,
	                    'addressType' => "shipTo",
	                ),
	            ),
	            'items' => $items,
	        );
	        $encodedRequest = json_encode($request);
	        
	        $headers = LandedCostManager::buildHeader();
	        
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_HTTP_VERSION,  CURL_HTTP_VERSION_1_1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedRequest);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        
	        $response = curl_exec($ch);
	        if(curl_errno($ch)){
	            throw new Exception(curl_error($ch));
	        }
	        
	        if (false === $response) {
	            echo 'Request unsuccessful' . PHP_EOL;
	            curl_close($ch);
	            exit(1);
	        }
	        $responseCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

	        $responseCode = 200;
	        $responseBody = json_decode($response);
	        //$responseBody = json_decode($response);
	        curl_close($ch);

	        if (200 !== $responseCode && 201 !== $responseCode) {
	            echo 'API failed' . PHP_EOL;
	            foreach ($responseBody->errors as $error) {
	                echo $error->code . ': ' . $error->message . PHP_EOL;
	            }
	            exit(1);
	        }
	        
	        return $responseBody;
	        die();
	        
	    }catch(Exception $e){
	        echo 'Error -- '. $e->getMessage();
	    }
	}

	
	/**
	 * Build and return the header require for calling lalamove API
	 * @return {Object} an associative aray of lalamove header
	 */
	public static function buildHeader($clientToken=null)
	{
		$ret = array(
			//"Cache-Control: no-cache",
			"Content-type: application/json; charset=utf-8",
		);

		return  $ret;
	}
	
}