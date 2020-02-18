<?php
namespace App\Lib\Fastship;
class FS_Line extends FS_ApiResource
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
    	$url = "line/create";

    	list($response, $rcode) = $requestor->request('post', $url, $params);
    
    	//print_r($response);
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
        $url = "line/" . $id;
        
        list($response, $rcode) = $requestor->request('get', $url);
        
        print_r($response);
        if($rcode != 200){
            return false;
        }else{
            return $response;
        }
    }
    
}