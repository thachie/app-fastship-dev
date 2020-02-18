<?php
namespace App\Lib\Fastship;

class FS_Payment extends FS_ApiResource
{
    /**
     * @param array|null $params
     * @param string|null $apiToken
     *
     * @return FS_Payment Create a Shipment.
     */
    public static function create($params = null, $apiToken = null)
    {
    	
    	$requestor = new FS_ApiRequestor($apiToken);
    	$url = "payment/create";

    	list($response, $rcode) = $requestor->request('post', $url, $params);
    
    	if($rcode != 200){
    		return false;
    	}else{
    		return $response;
    	}
    }

}