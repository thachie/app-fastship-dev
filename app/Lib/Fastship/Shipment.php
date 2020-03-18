<?php
namespace App\Lib\Fastship;
class FS_Shipment extends FS_ApiResource
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
    	$url = "shipment/create";

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
    public static function get($id, $apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
    	$url = "shipment/" . $id;

    	list($response, $rcode) = $requestor->request('get', $url);

    	if($rcode != 200 && $rcode != 404){
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
    	$url = "shipment/search";

    	list($response, $rcode) = $requestor->request('post', $url, $params);

     	if($rcode != 200 && $rcode != 404){
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
     public static function fullsearch($params = null, $apiToken = null)
     {
         
         $requestor = new FS_ApiRequestor($apiToken);
         $url = "shipment/fullsearch";
         
         list($response, $rcode) = $requestor->request('post', $url, $params);
         
         if($rcode != 200 && $rcode != 404){
             return false;
         }else{
             return $response;
         }
         
     }
    
    /**
     * @param array|null $params
     * @param string|null $apiToken
     *
     * @return Fastship_Get_Shipping_Rates Get the rates for a Shipment.
     */
    public static function get_shipping_rates($params = null, $apiToken = null)
    {

        $requestor = new FS_ApiRequestor($apiToken);
    	$url = "shipment/rates";

    	list($response, $rcode) = $requestor->request('post', $url, $params);

    	if($rcode != 200 && $rcode != 404){
    		return $rcode;
    	}else{
    		return $response;
    	}
    }
    
    /**
     * @param string $tracking
     * @param string|null $apiToken
     *
     * @return Fastship_Retrieve Get a Shipment.
     */
    public static function track($tracking, $apiToken = null)
    {
    	$requestor = new FS_ApiRequestor($apiToken);
    	$url = "track/" . $tracking;
    
    	list($response, $rcode) = $requestor->request('get', $url);

    	if($rcode != 200 && $rcode != 404){
    		return false;
    	}else{
    		return $response;
    	}
    }
    
    /**
     * @param string $tracking
     * @param string|null $apiToken
     *
     * @return Fastship_Retrieve Get a Shipment.
     */
    public static function trackid($id, $apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "trackid/" . $id;
        
        list($response, $rcode) = $requestor->request('get', $url);
        
        if($rcode != 200 && $rcode != 404){
            return false;
        }else{
            return $response;
        }
    }
    
    /**
     * @param array|null $params
     * @param string|null $apiToken
     *
     * @return Fastship_Shipment Update a Customer.
     */
    public static function updateZoho($params = null, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "shipment/update_zoho";
        
        list($response, $rcode) = $requestor->request('post', $url, $params);
        
        //print_r($response);
        if($rcode != 200){
            return false;
        }else{
            return true;
        }
    }
    
    /**
     * @param string $id
     * @param string|null $apiToken
     *
     * @return Fastship_Retrieve Cancel Shipment.
     */
    public static function cancel($id, $apiToken = null)
    {
    	$requestor = new FS_ApiRequestor($apiToken);
    	$url = "shipment/cancel/" . $id;
    
    	list($response, $rcode) = $requestor->request('put', $url);

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
     * @return Fastship_Get_Shipping_Rates Get the rates for a Shipment.
     */
    public static function get_declarations($declare, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "shipment/get_declare/" . $declare;
        
        list($response, $rcode) = $requestor->request('get', $url);
        
        if($rcode != 200 && $rcode != 404){
            return $rcode;
        }else{
            return $response;
        }
    }
}