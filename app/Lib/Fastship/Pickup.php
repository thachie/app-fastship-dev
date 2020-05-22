<?php
namespace App\Lib\Fastship;
class FS_Pickup extends FS_ApiResource
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
    	$url = "pickup/create";

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
    	$url = "pickup/" . $id;

    	list($response, $rcode) = $requestor->request('get', $url);

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
    public static function getUnpaid($id, $apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "pickup/unpaid/" . $id;
        
        list($response, $rcode) = $requestor->request('get', $url);
        
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
    public static function getLabels($id, $apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "pickup/labels/" . $id;
        
        list($response, $rcode) = $requestor->request('get', $url);
        
        if($rcode != 200){
            return array();
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
    public static function getLabel($id, $apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "pickup/label/" . $id;
        
        list($response, $rcode) = $requestor->request('get', $url);
        
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
    public static function getCoupon($params,$apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "pickup/coupon";
        
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
     * @return Fastship_All Get all the Shipments.
     */
     public static function search($params = null, $apiToken = null)
     {

		$requestor = new FS_ApiRequestor($apiToken);
    	$url = "pickup/search";

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
     * @return Fastship_Get_Pickup_Rates Get the rates for a Pickup.
     */
    public static function get_pickup_rates($params = null, $apiToken = null)
    {

        $requestor = new FS_ApiRequestor($apiToken);
    	$url = "pickup/rates";

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
     * @return Fastship_Pickup Update a Customer.
     */
    public static function updateStatus($params = null, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "pickup/update_status";
        
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
     * @return Fastship_Retrieve Cancel Pickup.
     */
    public static function cancel($id, $apiToken = null)
    {
    	$requestor = new FS_ApiRequestor($apiToken);
    	$url = "pickup/cancel/" . $id;
    
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
     * @return Fastship_Shipment Create a Shipment.
     */
    public static function createThaipost($params = null, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "thaipost/create";
        
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
     * Get a Thaipost Tracking.
     * @return
     */
    public static function track($id, $apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "pickup/tracks/" . $id;
        
        list($response, $rcode) = $requestor->request('get', $url);
        
        if($rcode != 200){
            return false;
        }else{
            return $response;
        }
    }

}