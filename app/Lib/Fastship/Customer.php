<?php
namespace App\Lib\Fastship;
class FS_Customer extends FS_ApiResource
{
    /**
     * @param array|null $params
     * @param string|null $apiToken
     *
     * @return Fastship_Customer Create a Customer.
     */
    public static function create($params = null, $apiToken = null)
    {
    	
    	$requestor = new FS_ApiRequestor($apiToken);
    	$url = "customer/create";

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
     * @return Fastship_Customer Create a Customer.
     */
    public static function create_line($params = null, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "customer/create_line";
        
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
     * @return Fastship_Customer Create a Customer.
     */
    public static function addChannel($params = null, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "customer/addchannel";
        
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
     * @return Fastship_Customer Create a Customer.
     */
    public static function updateChannel($params = null, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "customer/updatechannel";
        
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
     * @return Fastship_Customer Remove a Customer Channel.
     */
    public static function removeChannel($id, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "customer/removechannel/" . $id;
        
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
     * @return Fastship_Customer Remove a Customer Channel.
     */
    public static function getChannel($id, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "customer/channels/" . $id;
        
        list($response, $rcode) = $requestor->request('get', $url);
        
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
     * @return Fastship_Customer Update a Customer.
     */
    public static function update($params = null, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "customer/update";
        
        list($response, $rcode) = $requestor->request('post', $url, $params);

        //print_r($response);
        if($rcode != 200){
            return false;
        }else{
            return true;
        }
    }
    
    /**
     * @param array|null $params
     * @param string|null $apiToken
     *
     * @return Fastship_Customer Update a Customer Password.
     */
    public static function changePassword($params = null, $apiToken = null)
    {
        
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "customer/change_password";
        
        list($response, $rcode) = $requestor->request('post', $url, $params);

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
     * @return Fastship_Retrieve Get a Customer.
     */
    public static function login($params = null,$apiToken = null)
    {
    	$requestor = new FS_ApiRequestor($apiToken);
    	$url = "customer/login";

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
    
    /**
     * @param string $id
     * @param string|null $apiToken
     *
     * @return Fastship_Retrieve Get a Customer.
     */
    public static function checkEmail($email, $apiToken = null)
    {
    	$requestor = new FS_ApiRequestor($apiToken);
    	$url = "customer/check_email/" . $email;
    
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
     * @return Fastship_Retrieve Get a Customer.
     */
    public static function checkTel($tel, $apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "customer/check_tel/" . $tel;
        
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
     * @return Fastship_Retrieve Get a Customer.
     */
    public static function checkLineUserId($id, $apiToken = null)
    {
        $requestor = new FS_ApiRequestor($apiToken);
        $url = "customer/check_line/" . $id;
        
        list($response, $rcode) = $requestor->request('get', $url);
        
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
     * @return Fastship_All Get all the Customers.
     */
     public static function search($params = null, $apiToken = null)
     {

		$requestor = new FS_ApiRequestor($apiToken);
    	$url = "customer/search";

    	list($response, $rcode) = $requestor->request('post', $url, $params);

     	if($rcode != 200){
    		return false;
    	}else{
    		return $response;
    	}
    	
     }
    
}