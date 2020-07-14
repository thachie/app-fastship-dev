<?php
namespace App\Lib\Fastship;
use App\Lib\Fastship\FS_Util;
abstract class Fastship
{
	/**
	 * @var string The Fastship API secret key to be used for requests.
	 */
	public static $apiUserId = 'sandbox@fastship.co';
    /**
     * @var string The Fastship API secret key to be used for requests. 
     */
    public static $apiSecretKey = 'DeliveryAtUrFingerTips';
    /**
     * @var string The base URL for the Fastship API.
     */
    public static $apiBase = 'http://api.fastship.co/api/'; //sandbox url
    //public static $apiBase = 'http://api.fastship.co/api/'; //live url
    /**
     * @var string|null The version of the Fastship API to use for requests.
     */
    public static $apiToken;
    /**
     * @var string|null The version of the Fastship API to use for requests.
     */
    public static $apiVersion = null;
    /**
     * @var boolean Defaults to true.
     */
    public static $verifySslCerts = false;
    const VERSION = '1.0.1';
    
    public static $customerId = ""; //for app.fastship.co only
    
    /**
     * @return string The API key used for requests.
     */
    public static function getToken($customerId="")
    {

    	$headers = array(
    		'Content-Type: application/json',
    		'Accept: application/json',
    		'User-Agent: Fastship/v1 PHPBindings/' . self::VERSION
    	);
    	if (self::getApiVersion()){
    		$headers[] = 'Fastship-API-Version: ' . self::getApiVersion();
    	}

    	$authenticationDetails = array(
    		'username' => self::$apiUserId,
    		'secretKey' => self::$apiSecretKey,
    	);
    	//$encodedAuthenticationDetails = json_encode($authenticationDetails);
    	
    	$absUrl = self::$apiBase . "login";

    	$encodedAuthenticationDetails = FS_Util::_encodeObjects($authenticationDetails);

    	$langVersion = phpversion();

    	$uname = php_uname();

    	$curl = CurlClient::instance();
    	$method = 'post';
    	list($rbody, $rcode) = $curl->request($method, $absUrl, $headers, $encodedAuthenticationDetails);
    	$data = json_decode($rbody);

    	if(isset($data->data)){
    	   $msg = $data->data;
    	}else{
    	    $msg = "Fail: " . $rcode;
    	}

    	if($rcode != 200){
    	    throw new FS_Error($msg,$rcode);
    	}else{
    		self::$apiToken = $msg;
    		self::$customerId = $customerId;
    	}

    	
    }
    
    /**
     * Sets the API key to be used for requests.
     *
     * @param string $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }
    
    /**
     * @return string The API version used for requests. null if we're using the
     *    latest version.
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }
    
    /**
     * @param string $apiVersion The API version to use for requests.
     */
    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }
    
}