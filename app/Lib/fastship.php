<?php

// Tested on PHP 5.2, 5.3
if (!function_exists('curl_init')) {
    throw new Exception('Fastship needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
    throw new Exception('Fastship needs the JSON PHP extension.');
}
if (!function_exists('mb_detect_encoding')) {
    throw new Exception('Fastship needs the Multibyte String PHP extension.');
}

// Fastship singleton
require(dirname(__FILE__) . '/Fastship/Fastship.php');

// Utilities
require(dirname(__FILE__) . '/Fastship/Util.php');

// Errors
require(dirname(__FILE__) . '/Fastship/Error.php');

// Plumbing
// require(dirname(__FILE__) . '/Fastship/Object.php');
require(dirname(__FILE__) . '/Fastship/ApiRequestor.php');
require(dirname(__FILE__) . '/Fastship/ApiResource.php');
require(dirname(__FILE__) . '/Fastship/CurlClient.php');
require(dirname(__FILE__) . '/Fastship/Shipment.php');
require(dirname(__FILE__) . '/Fastship/Pickup.php');

