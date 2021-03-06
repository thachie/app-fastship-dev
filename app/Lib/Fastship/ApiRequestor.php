<?php
namespace App\Lib\Fastship;
class FS_ApiRequestor
{
    /**
     * @var string $apiKey The API key that's to be used to make requests.
     */
    public $apiToken;
    private static $httpClient;
    
    public function __construct($apiToken = null)
    {
        $this->apiToken = $apiToken;
    }
    
    /**
     * @param string $url The path to the API endpoint.
     *
     * @returns string The full path.
     */
    public static function apiUrl($url = '')
    {
        $apiBase = Fastship::$apiBase;
        return "$apiBase$url";
    }
    
    private static function _encodeObjects($d)
    {
        if ($d instanceof Fastship_Object) {
            return FS_Util::utf8($d->object_id);
        } else if ($d === true) {
            return true;
        } else if ($d === false) {
            return false;
        } else if (is_array($d)) {
            $res = array();
            foreach ($d as $k => $v)
                $res[$k] = self::_encodeObjects($v);
            return $res;
        } else {
            return FS_Util::utf8($d);
        }
    }
    
    /**
     * @param string $method
     * @param string $url
     * @param array|null $params
     *
     * @return array An array whose first element is the response and second
     *    element is the API key used to make the request.
     */
    public function request($method, $url, $params = null)
    {
        if (!$params)
            $params = array();
        list($rbody, $rcode) = $this->_requestRaw($method, $url, $params);

        try{
        	$resp = $this->_interpretResponse($rbody, $rcode);
        }catch (Exception $e){
        	echo($e->getMessage());
        }

        $retResp = (isset($resp['data']))?$resp['data']:$resp;
        
        return array(
            $retResp,
            $rcode
        );
    }
    
    /**
     * @param string $rbody A JSON string.
     * @param int $rcode
     * @param array $resp
     *
     * @throws Fastship_InvalidRequestError if the error is caused by the user.
     * @throws Fastship_AuthenticationError if the error is caused by a lack of
     *    permissions.
     * @throws Fastship_ApiError otherwise.
     */
    public function handleApiError($rbody, $rcode, $resp)
    {
        // Array is not currently being returned by API, making the below N/A 
        // if (!is_array($resp) || !isset($resp['error'])) {
        //   $msg = "Invalid response object from API: $rbody "
        //        ."(HTTP response code was $rcode)";
        //   throw new Fastship_ApiError($msg, $rcode, $rbody, $resp);
        // }
        
        $msg = "message not set";
        $param = "parameters not set";
        $code = "code not set";
        
        // Temporary setting of msg to rbody
        $msg = $rbody;
        
        // Parameters necessary for error code construction are not provided
        // $error = $resp['error'];
        // $msg = isset($error['message']) ? $error['message'] : null;
        // $param = isset($error['param']) ? $error['param'] : null;
        // $code = isset($error['code']) ? $error['code'] : null;

        switch ($rcode) {
            case 400:
                throw new FS_Error($msg, $param, $rcode, $rbody, $resp);
            case 404:
                throw new FS_Error($msg, $param, $rcode, $rbody, $resp);
            case 401:
                throw new FS_Error($msg, $rcode, $rbody, $resp);
            default:
                throw new FS_Error($msg, $rcode, $rbody, $resp);
        }
    }
    
    private function _requestRaw($method, $url, $params)
    {
        $myApiToken = $this->apiToken;
        
        if (!$myApiToken)
        	$myApiToken = Fastship::$apiToken;

        if (!$myApiToken) {
        	$msg = 'No credentials provided.';
        	throw new FS_Error($msg);
        }
      
        $absUrl = $this->apiUrl($url);
        $params = FS_Util::_encodeObjects($params);
        $langVersion = phpversion();
        $uname = php_uname();
        $headers = array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Bearer ' . $myApiToken,
        	'User-ID: ' . Fastship::$apiUserId,
        	'Cust-ID: ' . Fastship::$customerId, 
            'Accept: application/json',
            'Debug: true', 
            'User-Agent: Fastship/v1 PHPBindings/' . Fastship::VERSION
        );
        if (Fastship::getApiVersion()){
            $headers[] = 'Fastship-API-Version: ' . Fastship::getApiVersion();
        }

        list($rbody, $rcode) = $this->httpClient()->request($method, $absUrl, $headers, $params);

        return array(
            $rbody,
            $rcode
        );
    }
    
    private function _interpretResponse($rbody, $rcode)
    {
        try {
            $resp = json_decode($rbody, true);
        }
        catch (Exception $e) {
            $msg = "Invalid response body from API: $rbody " . "(HTTP response code was $rcode)";
            throw new FS_Error($msg, $rcode, $rbody);
        }
        
        if (($rcode < 200 || $rcode >= 300) && $rcode != 404) {
            $this->handleApiError($rbody, $rcode, $resp);
        }
        
        return $resp;
    }
    
    public static function setHttpClient($client)
    {
        self::$httpClient = $client;
    }
    public static function httpClient()
    {
        if (!self::$httpClient) {
            self::$httpClient = CurlClient::instance();
        }
        return self::$httpClient;
    }
}