<?php
if (!function_exists('curl_init')) {
  throw new Exception('Divido needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Divido needs the JSON PHP extension.');
}
if (!function_exists('mb_detect_encoding')) {
  throw new Exception('Divido needs the Multibyte String PHP extension.');
}

// Divido singleton
require_once(dirname(__FILE__) . '/Divido/Divido.php');

// Utilities
require_once(dirname(__FILE__) . '/Divido/Util.php');
require_once(dirname(__FILE__) . '/Divido/Util/Set.php');

// Errors
require_once(dirname(__FILE__) . '/Divido/Error.php');
require_once(dirname(__FILE__) . '/Divido/ApiError.php');
require_once(dirname(__FILE__) . '/Divido/ApiConnectionError.php');
require_once(dirname(__FILE__) . '/Divido/AuthenticationError.php');
require_once(dirname(__FILE__) . '/Divido/PaymentError.php');
require_once(dirname(__FILE__) . '/Divido/InvalidRequestError.php');
require_once(dirname(__FILE__) . '/Divido/RateLimitError.php');

// Plumbing
require_once(dirname(__FILE__) . '/Divido/Object.php');
require_once(dirname(__FILE__) . '/Divido/ApiRequestor.php');
require_once(dirname(__FILE__) . '/Divido/ApiResource.php');
require_once(dirname(__FILE__) . '/Divido/SingletonApiResource.php');
require_once(dirname(__FILE__) . '/Divido/AttachedObject.php');
require_once(dirname(__FILE__) . '/Divido/List.php');

// Divido API Resources
require_once(dirname(__FILE__) . '/Divido/SendApplication.php');
require_once(dirname(__FILE__) . '/Divido/CreditRequest.php');
require_once(dirname(__FILE__) . '/Divido/DealCalculator.php');
require_once(dirname(__FILE__) . '/Divido/Finances.php');
require_once(dirname(__FILE__) . '/Divido/Comments.php');
require_once(dirname(__FILE__) . '/Divido/Activation.php');
require_once(dirname(__FILE__) . '/Divido/Refund.php');
require_once(dirname(__FILE__) . '/Divido/Fulfillment.php');
require_once(dirname(__FILE__) . '/Divido/Cancellation.php');