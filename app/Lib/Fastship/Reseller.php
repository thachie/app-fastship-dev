<?php
namespace App\Lib\Fastship;
class FS_Reseller extends FS_ApiResource
{
    /**
     * @param array|null $params
     * @param string|null $apiToken
     *
     * @return Fastship_Customer Create a Customer.
     */
//     public static function create($params = null, $apiToken = null)
//     {
    	
//     	$requestor = new FS_ApiRequestor($apiToken);
//     	$url = "customer/create";

//     	list($response, $rcode) = $requestor->request('post', $url, $params);

//     	if($rcode != 200){
//     		return false;
//     	}else{
//     		return $response;
//     	}
//     }

    /**
     * @param string $id
     * @param string|null $apiToken
     *
     * @return Fastship_Retrieve Get a Customer.
     */
    public static function login($params = null,$apiToken = null)
    {
    	$requestor = new FS_ApiRequestor($apiToken);
    	$url = "reseller/login";
    
    	list($response, $rcode) = $requestor->request('post', $url,$params);
    
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
     * @return Fastship_Retrieve Get a Customer.
     */
    public static function get($id, $apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
    	$url = "customer/" . $id;

    	list($response, $rcode) = $requestor->request('get', $url);

    	if($rcode != 200){
    		return false;
    	}else{
    		return $response;
    	}
    }

}