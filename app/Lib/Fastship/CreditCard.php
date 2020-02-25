<?php
namespace App\Lib\Fastship;
class FS_CreditCard extends FS_ApiResource
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
    	$url = "credit_card/create";

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
    public static function delete($id, $apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
    	$url = "credit_card/delete/".$id;

    	list($response, $rcode) = $requestor->request('put', $url);

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
     * @return Fastship_Get_Credit_Cards Get the rates for a Pickup.
     */
    public static function get_credit_cards($params = null, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "credit_card/getcards";
        
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
     * @return Fastship_Get_Credit_Cards Get the rates for a Pickup.
     */
    public static function get($id, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "credit_card/get/" . $id;
        
        list($response, $rcode) = $requestor->request('get', $url);
        
        if($rcode != 200){
            return false;
        }else{
            return $response;
        }
    }
    
}