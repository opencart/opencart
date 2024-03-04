<?php

define('OAUTH_SIG_METHOD_RSASHA1', 'RSA-SHA1');
define('OAUTH_SIG_METHOD_HMACSHA1', 'HMAC-SHA1');
define('OAUTH_SIG_METHOD_HMACSHA256', 'HMAC-SHA256');

define('OAUTH_AUTH_TYPE_AUTHORIZATION', 3);
define('OAUTH_AUTH_TYPE_NONE', 2);
define('OAUTH_AUTH_TYPE_URI', 1);
define('OAUTH_AUTH_TYPE_FORM', 2);

define('OAUTH_HTTP_METHOD_GET', 'GET');
define('OAUTH_HTTP_METHOD_POST', 'POST');
define('OAUTH_HTTP_METHOD_PUT', 'PUT');
define('OAUTH_HTTP_METHOD_HEAD', 'HEAD');
define('OAUTH_HTTP_METHOD_DELETE', 'DELETE');

define('OAUTH_REQENGINE_STREAMS', 1);
define('OAUTH_REQENGINE_CURL', 2);

define('OAUTH_OK', 0);
define('OAUTH_BAD_NONCE', 4);
define('OAUTH_BAD_TIMESTAMP', 8);
define('OAUTH_CONSUMER_KEY_UNKNOWN', 16);
define('OAUTH_CONSUMER_KEY_REFUSED', 32);
define('OAUTH_INVALID_SIGNATURE', 64);
define('OAUTH_TOKEN_USED', 128);
define('OAUTH_TOKEN_EXPIRED', 256);
define('OAUTH_TOKEN_REJECTED', 1024);
define('OAUTH_VERIFIER_INVALID', 2048);
define('OAUTH_PARAMETER_ABSENT', 4096);
define('OAUTH_SIGNATURE_METHOD_REJECTED', 8192);

/**
 * Generate a Signature Base String
 *
 * @param string $http_method
 * @param string $uri
 * @param array $request_parameters
 * @return string
 */
function oauth_get_sbs($http_method, $uri, $request_parameters = []) {}

/**
 * Encode a URI to RFC 3986
 *
 * @param string $uri
 * @return string
 */
function oauth_urlencode($uri) {}

/**
 * The OAuth extension provides a simple interface to interact with data providers using the OAuth HTTP specification to protect private resources.
 */
class OAuth
{
    /**
     * @var bool
     */
    public $debug;

    /**
     * @var bool
     */
    public $sslChecks;

    /**
     * @var array
     */
    public $debugInfo;

    /**
     * Create a new OAuth object
     * @param string $consumer_key
     * @param string $consumer_secret
     * @param string $signature_method
     * @param int $auth_type
     * @throws \OAuthException
     */
    public function __construct($consumer_key, $consumer_secret, $signature_method = OAUTH_SIG_METHOD_HMACSHA1, $auth_type = OAUTH_AUTH_TYPE_AUTHORIZATION) {}

    /**
     * Turn off verbose debugging
     * @return bool
     */
    public function disableDebug() {}

    /**
     * Turn off redirects
     * @return void
     */
    public function disableRedirects() {}

    /**
     * Turn off SSL checks
     * @return bool
     */
    public function disableSSLChecks() {}

    /**
     * Turn on verbose debugging
     * @return bool
     */
    public function enableDebug() {}

    /**
     * Turn on redirects
     * @return bool
     */
    public function enableRedirects() {}

    /**
     * Turn on SSL checks
     * @return bool
     */
    public function enableSSLChecks() {}

    /**
     * Set the timeout
     * @param int $timeout Time in milliseconds
     * @return void
     */
    public function setTimeout($timeout) {}

    /**
     * Fetch an OAuth-protected resource
     * @param string $protected_resource_url
     * @param array $extra_parameters
     * @param string $http_method
     * @param array $http_headers
     * @throws \OAuthException
     * @return mixed
     */
    public function fetch($protected_resource_url, $extra_parameters = [], $http_method = null, $http_headers = []) {}

    /**
     * Fetch an access token
     * @param string $access_token_url
     * @param string $auth_session_handle
     * @param string $verifier_token
     * @throws \OAuthException
     * @return array
     */
    public function getAccessToken($access_token_url, $auth_session_handle = null, $verifier_token = null) {}

    /**
     * Get CA information
     * @return array
     */
    public function getCAPath() {}

    /**
     * Get the last response
     * @return string
     */
    public function getLastResponse() {}

    /**
     * Get headers for last response
     * @return string|false
     */
    public function getLastResponseHeaders() {}

    /**
     * Get HTTP information about the last response
     * @return array
     */
    public function getLastResponseInfo() {}

    /**
     * Generate OAuth header string signature
     * @param string $http_method
     * @param string $url
     * @param mixed  $extra_parameters
     * @return string|false
     */
    public function getRequestHeader($http_method, $url, $extra_parameters = '') {}

    /**
     * Fetch a request token
     * @param string $request_token_url
     * @param string $callback_url
     * @param string $http_method
     * @throws \OAuthException
     * @return array
     */
    public function getRequestToken($request_token_url, $callback_url = null, $http_method = 'GET') {}

    /**
     * Set authorization type
     * @param int $auth_type
     * @return bool
     */
    public function setAuthType($auth_type) {}

    /**
     * Set CA path and info
     * @param string $ca_path
     * @param string $ca_info
     * @return mixed
     */
    public function setCAPath($ca_path = null, $ca_info = null) {}

    /**
     * Set the nonce for subsequent requests
     * @param string $nonce
     * @return mixed
     */
    public function setNonce($nonce) {}

    /**
     * @param int $reqengine
     * @return void
     */
    public function setRequestEngine($reqengine) {}

    /**
     * Set the RSA certificate
     * @param string $cert
     * @return mixed
     */
    public function setRSACertificate($cert) {}

    /**
     * Set the timestamp
     * @param string $timestamp
     * @return mixed
     */
    public function setTimestamp($timestamp) {}

    /**
     * Set the token and secret
     * @param string $token
     * @param string $token_secret
     * @return bool
     */
    public function setToken($token, $token_secret) {}

    /**
     * Set the OAuth version
     * @param string $version
     * @return bool
     */
    public function setVersion($version) {}
}

class OAuthException extends Exception
{
    /**
     * The response of the exception which occurred, if any
     * @var string
     */
    public $lastResponse;

    /**
     * @var array
     */
    public $debugInfo;
}

;

/**
 * Manages an OAuth provider class.
 */
class OAuthProvider
{
    /**
     * @param string $req_params
     * @return bool
     */
    final public function addRequiredParameter($req_params) {}

    /**
     * @return void
     */
    public function callconsumerHandler() {}

    /**
     * @return void
     */
    public function callTimestampNonceHandler() {}

    /**
     * @return void
     */
    public function calltokenHandler() {}

    /**
     * @param string $uri
     * @param string $method
     * @return void
     */
    public function checkOAuthRequest($uri = '', $method = '') {}

    /**
     * @param array $params_array
     */
    public function __construct($params_array) {}

    /**
     * @param callable $callback_function
     * @return void
     */
    public function consumerHandler($callback_function) {}

    /**
     * @param int $size
     * @param bool $strong
     * @return string
     */
    final public static function generateToken($size, $strong = false) {}

    /**
     * @param mixed $params_array
     * @return void
     */
    public function is2LeggedEndpoint($params_array) {}

    /**
     * @param bool $will_issue_request_token
     * @return void
     */
    public function isRequestTokenEndpoint($will_issue_request_token) {}

    /**
     * @param string $req_params
     * @return bool
     */
    final public function removeRequiredParameter($req_params) {}

    /**
     * @param string $oauthexception
     * @param bool $send_headers
     * @return string
     */
    final public static function reportProblem($oauthexception, $send_headers = true) {}

    /**
     * @param string $param_key
     * @param mixed $param_val
     * @return bool
     */
    final public function setParam($param_key, $param_val = null) {}

    /**
     * @param string $path
     * @return bool
     */
    final public function setRequestTokenPath($path) {}

    /**
     * @param callable $callback_function
     * @return void
     */
    public function timestampNonceHandler($callback_function) {}

    /**
     * @param callable $callback_function
     * @return void
     */
    public function tokenHandler($callback_function) {}
}
