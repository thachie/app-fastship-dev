<?php
namespace App\Lib\Fastship;
class FS_Address extends FS_ApiResource
{
    /**
     * @param string $id
     * @param string|null $apiToken
     *
     * @return Fastship_Retrieve Get a Countries.
     */
    public static function get_countries($apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
    	$url = "address/country";

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
     * @return Fastship_Retrieve Get a Countries.
     */
    public static function get_country($country,$apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "address/get_country/" . $country;
        
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
     * @return Fastship_Retrieve Get a States.
     */
    public static function get_states($country,$apiToken = null)
    {
    	$requestor = new FS_ApiRequestor($apiToken);
    	$url = "address/" . $country . "/state";
    
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
     * @return Fastship_Retrieve Get a States.
     */
    public static function get_states_query($country,$query="",$apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "address_query/" . $country . "/state/" . $query;

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
     * @return Fastship_Retrieve Get a States.
     */
    public static function get_cities($country,$state,$apiToken = null)
    {
    	$requestor = new FS_ApiRequestor($apiToken);
    	$url = "address/" . $country . "/" . $state . "/city";
    
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
     * @return Fastship_Retrieve Get a States.
     */
    public static function get_cities_query($country,$state,$query="",$apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "address_query/" . $country . "/" . $state . "/city/" . $query;
        
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
     * @return Fastship_Retrieve Get a States.
     */
    public static function get_postcodes($country,$city,$apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "address/" . $country . "/" . $city . "/postcode";
        
        list($response, $rcode) = $requestor->request('get', $url);
        
        if($rcode != 200){
            return false;
        }else{
            return $response;
        }
    }

}