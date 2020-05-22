<?php
namespace App\Lib\Fastship;
class FS_CreditBalance extends FS_ApiResource
{
    /**
     * @param array|null $params
     * @param string|null $apiToken
     *
     * @return Fastship_Shipment Create a Shipment.
     */
    public static function create($params = null, $apiToken = null)
    {
    	
    	$requestor = new FS_ApiRequestor($apiToken);
    	$url = "credit_balance/create";

    	list($response, $rcode) = $requestor->request('post', $url, $params);

    	if($rcode != 200){
    		return false;
    	}else{
    		return $response['data'];
    	}
    }
    
    /**
     * @param array|null $params
     * @param string|null $apiToken
     *
     * @return Fastship_Shipment Create a Shipment.
     */
    public static function requestCredit($params = null, $apiToken = null)
    {
    	 
    	$requestor = new FS_ApiRequestor($apiToken);
    	$url = "credit_request/create";
    
    	list($response, $rcode) = $requestor->request('post', $url, $params);
    
    	if($rcode != 200){
    		return false;
    	}else{
    		return $response;
    	}
    }
    
    /**
     * @param array|null $params
     * @param string|null $apiToken
     *
     * @return Fastship_Shipment Create a Shipment.
     */
    public static function withdraw($params = null, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "credit_balance/withdraw";
        
        list($response, $rcode) = $requestor->request('post', $url, $params);
        
        if($rcode != 200){
            return false;
        }else{
            return $response;
        }
    }
    
    /**
     * @param string $id
     * @param string|null $apiToken
     *
     * @return Fastship_Retrieve Get a Shipment.
     */
    public static function get_statements($params=array(),$apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "credit_balance/statement?".http_build_query($params);
        
        list($response, $rcode) = $requestor->request('get', $url);

        if($rcode != 200){
            if($rcode == 404){
                return false;
            }
            return false;
        }else{
            return $response;
        }
    }
    
    /**
     * @param string $id
     * @param string|null $apiToken
     *
     * @return Fastship_Retrieve Get a Shipment.
     */
    public static function get($apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
    	$url = "credit_balance/balance";

    	list($response, $rcode) = $requestor->request('get', $url);

    	if($rcode != 200){
            if($rcode == 404){
                return 0;
            }
    		return false;
        }else{
    		return $response;
    	}
    }
    
    /**
     * @param string $id
     * @param string|null $apiToken
     *
     * @return Fastship_Retrieve Get a Shipment.
     */
    public static function getUnpaid($apiToken = null)
    {
    	$requestor = new FS_ApiRequestor($apiToken);
    	$url = "credit_balance/unpaid";
    
    	list($response, $rcode) = $requestor->request('get', $url);
    
    	if($rcode != 200){
    		if($rcode == 404){
    			return 0;
    		}
    		return false;
    	}else{
    		return $response;
    	}
    }
    
    /**
     * @param array|null $params
     * @param string|null $apiToken
     *
     * @return Fastship_All Get all the Shipments.
     */
     public static function search($params = null, $apiToken = null)
     {

		$requestor = new FS_ApiRequestor($apiToken);
    	$url = "credit_balance/search";

    	list($response, $rcode) = $requestor->request('post', $url, $params);

     	if($rcode != 200){
    		return false;
    	}else{
    		return $response;
    	}
    	
     }

}