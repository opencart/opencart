<?php // phpcs:ignore

namespace Braintree;

/**
 * Configuration registry
 */
class Configuration
{
    public static $global;

    private $_environment = null;
    private $_merchantId = null;
    private $_publicKey = null;
    private $_privateKey = null;
    private $_clientId = null;
    private $_clientSecret = null;
    private $_accessToken = null;
    private $_proxyHost = null;
    private $_proxyPort = null;
    private $_proxyType = null;
    private $_proxyUser = null;
    private $_proxyPassword = null;
    private $_timeout = 60;
    private $_sslVersion = null;
    private $_acceptGzipEncoding = true;

    /**
     * Braintree API version to use
     */
    const API_VERSION =  6;
    const GRAPHQL_API_VERSION = '2018-09-10';

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attribs = [])
    {
        foreach ($attribs as $kind => $value) {
            if ($kind == 'environment') {
                CredentialsParser::assertValidEnvironment($value);
                $this->_environment = $value;
            }
            if ($kind == 'merchantId') {
                $this->_merchantId = $value;
            }
            if ($kind == 'publicKey') {
                $this->_publicKey = $value;
            }
            if ($kind == 'privateKey') {
                $this->_privateKey = $value;
            }
            if ($kind == 'proxyHost') {
                $this->_proxyHost = $value;
            }
            if ($kind == 'proxyPort') {
                $this->_proxyPort = $value;
            }
            if ($kind == 'proxyType') {
                $this->_proxyType = $value;
            }
            if ($kind == 'proxyUser') {
                $this->_proxyUser = $value;
            }
            if ($kind == 'proxyPassword') {
                $this->_proxyPassword = $value;
            }
            if ($kind == 'timeout') {
                $this->_timeout = $value;
            }
            if ($kind == 'sslVersion') {
                $this->_sslVersion = $value;
            }
            if ($kind == 'acceptGzipEncoding') {
                $this->_acceptGzipEncoding = $value;
            }
        }

        if (isset($attribs['clientId']) || isset($attribs['accessToken'])) {
            // phpcs:ignore Generic.Files.LineLength
            if (isset($attribs['environment']) || isset($attribs['merchantId']) || isset($attribs['publicKey']) || isset($attribs['privateKey'])) {
                // phpcs:ignore Generic.Files.LineLength
                throw new Exception\Configuration('Cannot mix OAuth credentials (clientId, clientSecret, accessToken) with key credentials (publicKey, privateKey, environment, merchantId).');
            }
            $parsedCredentials = new CredentialsParser($attribs);

            $this->_environment = $parsedCredentials->getEnvironment();
            $this->_merchantId = $parsedCredentials->getMerchantId();
            $this->_clientId = $parsedCredentials->getClientId();
            $this->_clientSecret = $parsedCredentials->getClientSecret();
            $this->_accessToken = $parsedCredentials->getAccessToken();
        }
    }

    /**
     * resets configuration to default
     *
     * @return Configuration
     */
    public static function reset()
    {
        self::$global = new Configuration();
    }

    /**
     * Create new gateway
     *
     * @return Gateway
     */
    public static function gateway()
    {
        return new Gateway(self::$global);
    }

    /**
     * Getter/Setter for configuration environment
     *
     * @param string $value If provided, sets the environment
     *
     * @return self
     */
    public static function environment($value = null)
    {
        if (empty($value)) {
            return self::$global->getEnvironment();
        }
        CredentialsParser::assertValidEnvironment($value);
        self::$global->setEnvironment($value);
    }

    /**
     * Getter/Setter for configuration merchant ID
     *
     * @param string $value If provided, sets the merchantId
     *
     * @return self
     */
    public static function merchantId($value = null)
    {
        if (empty($value)) {
            return self::$global->getMerchantId();
        }
        self::$global->setMerchantId($value);
    }

    /**
     * Getter/Setter for configuration public key
     *
     * @param string $value If provided, sets the public key
     *
     * @return self
     */
    public static function publicKey($value = null)
    {
        if (empty($value)) {
            return self::$global->getPublicKey();
        }
        self::$global->setPublicKey($value);
    }

    /**
     * Getter/Setter for configuration private key
     *
     * @param string $value If provided, sets the private key
     *
     * @return self
     */
    public static function privateKey($value = null)
    {
        if (empty($value)) {
            return self::$global->getPrivateKey();
        }
        self::$global->setPrivateKey($value);
    }

    /**
     * Sets or gets the read timeout to use for making requests.
     *
     * @param integer $value If provided, sets the read timeout
     *
     * @return integer The read timeout used for connecting to Braintree
     */
    public static function timeout($value = null)
    {
        if (empty($value)) {
            return self::$global->getTimeout();
        }
        self::$global->setTimeout($value);
    }

    /**
     * Sets or gets the SSL version to use for making requests. See
     * https://php.net/manual/en/function.curl-setopt.php for possible
     * CURLOPT_SSLVERSION values.
     *
     * @param integer $value If provided, sets the SSL version
     *
     * @return integer The SSL version used for connecting to Braintree
     */
    public static function sslVersion($value = null)
    {
        if (empty($value)) {
            return self::$global->getSslVersion();
        }
        self::$global->setSslVersion($value);
    }

    /**
     * Sets or gets the proxy host to use for connecting to Braintree
     *
     * @param string $value If provided, sets the proxy host
     *
     * @return string The proxy host used for connecting to Braintree
     */
    public static function proxyHost($value = null)
    {
        if (empty($value)) {
            return self::$global->getProxyHost();
        }
        self::$global->setProxyHost($value);
    }

    /**
     * Sets or gets the port of the proxy to use for connecting to Braintree
     *
     * @param string $value If provided, sets the port of the proxy
     *
     * @return string The port of the proxy used for connecting to Braintree
     */
    public static function proxyPort($value = null)
    {
        if (empty($value)) {
            return self::$global->getProxyPort();
        }
        self::$global->setProxyPort($value);
    }

    /**
     * Sets or gets the proxy type to use for connecting to Braintree. This value
     * can be any of the CURLOPT_PROXYTYPE options in PHP cURL.
     *
     * @param string $value If provided, sets the proxy type
     *
     * @return string The proxy type used for connecting to Braintree
     */
    public static function proxyType($value = null)
    {
        if (empty($value)) {
            return self::$global->getProxyType();
        }
        self::$global->setProxyType($value);
    }

    /**
     * Sets or gets the proxy user to use for connecting to Braintree.
     *
     * @param string $value If provided, sets the proxy username
     *
     * @return string The proxy type used for connecting to Braintree
     */
    public static function proxyUser($value = null)
    {
        if (empty($value)) {
            return self::$global->getProxyUser();
        }
        self::$global->setProxyUser($value);
    }

    /**
     * Sets or gets the proxy password to use for connecting to Braintree. This value
     * can be any of the CURLOPT_PROXYUSERPWD  options in PHP cURL.
     *
     * @param string $value If provided, sets the proxy password
     *
     * @return string The proxy type used for connecting to Braintree
     */
    public static function proxyPassword($value = null)
    {
        if (empty($value)) {
            return self::$global->getProxyPassword();
        }
        self::$global->setProxyPassword($value);
    }

    /**
     * Specify if the HTTP client is able to decode gzipped responses.
     *
     * // phpcs:ignore Generic.Files.LineLength
     *
     * @param boolean $value If true, will send an Accept-Encoding header with a gzip value. If false, will not send an Accept-Encoding header with a gzip value.
     *
     * @return boolean true if an Accept-Encoding header with a gzip value will be sent, false if not
     */
    public static function acceptGzipEncoding($value = null)
    {
        if (is_null($value)) {
            return self::$global->getAcceptGzipEncoding();
        }
        self::$global->setAcceptGzipEncoding($value);
    }

    //phpcs:ignore
    public static function assertGlobalHasAccessTokenOrKeys()
    {
        self::$global->assertHasAccessTokenOrKeys();
    }

    /*
     * Check if configuration has an access token, or api keys
     *
     * @throws Exception\Configuration
     *
     * @return self
     */
    public function assertHasAccessTokenOrKeys()
    {
        if (empty($this->_accessToken)) {
            if (empty($this->_merchantId)) {
                // phpcs:ignore Generic.Files.LineLength
                throw new Exception\Configuration('Braintree\\Configuration::merchantId needs to be set (or accessToken needs to be passed to Braintree\\Gateway).');
            } elseif (empty($this->_environment)) {
                throw new Exception\Configuration('Braintree\\Configuration::environment needs to be set.');
            } elseif (empty($this->_publicKey)) {
                throw new Exception\Configuration('Braintree\\Configuration::publicKey needs to be set.');
            } elseif (empty($this->_privateKey)) {
                throw new Exception\Configuration('Braintree\\Configuration::privateKey needs to be set.');
            }
        }
    }

    /*
     * Check if configuration has a client id and secret
     *
     * @throws Exception\Configuration
     *
     * @return self
     */
    public function assertHasClientCredentials()
    {
        $this->assertHasClientId();
        $this->assertHasClientSecret();
    }

    /*
     * Check if configuration has a client id
     *
     * @throws Exception\Configuration
     *
     * @return self
     */
    public function assertHasClientId()
    {
        if (empty($this->_clientId)) {
            throw new Exception\Configuration('clientId needs to be passed to Braintree\\Gateway.');
        }
    }

    /*
     * Check if configuration has a client secret
     *
     * @throws Exception\Configuration
     *
     * @return self
     */
    public function assertHasClientSecret()
    {
        if (empty($this->_clientSecret)) {
            throw new Exception\Configuration('clientSecret needs to be passed to Braintree\\Gateway.');
        }
    }

    /*
     * Getter function
     *
     * @return string environment
     */
    public function getEnvironment()
    {
        return $this->_environment;
    }

    /*
     * Getter function
     *
     * @return string merchant id
     */
    public function getMerchantId()
    {
        return $this->_merchantId;
    }

    /*
     * Getter function
     *
     * @return string public key
     */
    public function getPublicKey()
    {
        return $this->_publicKey;
    }

    /*
     * Getter function
     *
     * @return string client id
     */
    public function getClientId()
    {
        return $this->_clientId;
    }

    /*
     * Getter function
     *
     * @return string private key
     */
    public function getPrivateKey()
    {
        return $this->_privateKey;
    }

    /*
     * Getter function
     *
     * @return string client secret
     */
    public function getClientSecret()
    {
        return $this->_clientSecret;
    }

    /*
     * Getter function
     *
     * @return string proxy host
     */
    public function getProxyHost()
    {
        return $this->_proxyHost;
    }
    /*
     * Getter function
     *
     * @return string proxy port
     */
    public function getProxyPort()
    {
        return $this->_proxyPort;
    }


    /*
     * Getter function
     *
     * @return string proxy type
     */
    public function getProxyType()
    {
        return $this->_proxyType;
    }

    /*
     * Getter function
     *
     * @return string proxy username
     */
    public function getProxyUser()
    {
        return $this->_proxyUser;
    }

    /*
     * Getter function
     *
     * @return string proxy password
     */
    public function getProxyPassword()
    {
        return $this->_proxyPassword;
    }

    /*
     * Getter function
     *
     * @return int timeout
     */
    public function getTimeout()
    {
        return $this->_timeout;
    }

    /*
     * Getter function
     *
     * @return string ssl version
     */
    public function getSslVersion()
    {
        return $this->_sslVersion;
    }

    /*
     * Getter function
     *
     * @return bool
     */
    public function getAcceptGzipEncoding()
    {
        return $this->_acceptGzipEncoding;
    }

    //phpcs:disable PEAR.Commenting
    /**
     * Do not use this method directly. Pass in the environment to the constructor.
     */
    public function setEnvironment($value)
    {
        $this->_environment = $value;
    }

    /**
     * Do not use this method directly. Pass in the merchantId to the constructor.
     */
    public function setMerchantId($value)
    {
        $this->_merchantId = $value;
    }

    /**
     * Do not use this method directly. Pass in the publicKey to the constructor.
     */
    public function setPublicKey($value)
    {
        $this->_publicKey = $value;
    }

    /**
     * Do not use this method directly. Pass in the privateKey to the constructor.
     */
    public function setPrivateKey($value)
    {
        $this->_privateKey = $value;
    }

    private function setProxyHost($value)
    {
        $this->_proxyHost = $value;
    }

    private function setProxyPort($value)
    {
        $this->_proxyPort = $value;
    }

    private function setProxyType($value)
    {
        $this->_proxyType = $value;
    }

    private function setProxyUser($value)
    {
        $this->_proxyUser = $value;
    }

    private function setProxyPassword($value)
    {
        $this->_proxyPassword = $value;
    }

    private function setTimeout($value)
    {
        $this->_timeout = $value;
    }

    private function setSslVersion($value)
    {
        $this->_sslVersion = $value;
    }

    private function setAcceptGzipEncoding($value)
    {
        $this->_acceptGzipEncoding = $value;
    }
    //phpcs:enable PEAR.Commenting

    /*
     * Getter function
     *
     * @return string access token
     */
    public function getAccessToken()
    {
        return $this->_accessToken;
    }

    /*
     * Checks for presence of access token
     *
     * @return bool
     */
    public function isAccessToken()
    {
        return !empty($this->_accessToken);
    }

    /*
     * Checks for presence of client credentials
     *
     * @return bool
     */
    public function isClientCredentials()
    {
        return !empty($this->_clientId);
    }

    /**
     * returns the base braintree gateway URL based on config values
     *
     * @return string braintree gateway URL
     */
    public function baseUrl()
    {
        return sprintf('%s://%s:%d', $this->protocol(), $this->serverName(), $this->portNumber());
    }

    /**
     * returns the base URL for Braintree's GraphQL endpoint based on config values
     *
     * @return string Braintree GraphQL URL
     */
    public function graphQLBaseUrl()
    {
        return sprintf('%s://%s:%d/graphql', $this->protocol(), $this->graphQLServerName(), $this->graphQLPortNumber());
    }

    /**
     * sets the merchant path based on merchant ID
     *
     * @return string merchant path uri
     */
    public function merchantPath()
    {
        return '/merchants/' . $this->_merchantId;
    }

    /**
     * sets the physical path for the location of the CA certs
     *
     * @param string $sslPath optional
     *
     * @return string filepath
     */
    public function caFile($sslPath = null)
    {
        $sslPath = $sslPath ? $sslPath : DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                   'ssl' . DIRECTORY_SEPARATOR;
        $caPath = __DIR__ . $sslPath . 'api_braintreegateway_com.ca.crt';

        if (!file_exists($caPath)) {
            throw new Exception\SSLCaFileNotFound();
        }

        return $caPath;
    }

    /**
     * returns the port number depending on environment
     *
     * @return int portnumber
     */
    public function portNumber()
    {
        if ($this->sslOn()) {
            return 443;
        }
        return getenv("GATEWAY_PORT") ? getenv("GATEWAY_PORT") : 3000;
    }

    /**
     * returns the port number depending on environment
     *
     * @return integer graphql portnumber
     */
    public function graphQLPortNumber()
    {
        if ($this->sslOn()) {
            return 443;
        }
        return getenv("GRAPHQL_PORT") ?: 8080;
    }

    /**
     * Specifies whether or not a proxy is properly configured
     *
     * @return boolean true if a proxy is configured properly, false if not
     */
    public function isUsingProxy()
    {
        $proxyHost = $this->getProxyHost();
        $proxyPort = $this->getProxyPort();
        return !empty($proxyHost) && !empty($proxyPort);
    }

    /**
     * Specified whether or not a username and password have been provided for
     * use with an authenticated proxy
     *
     * @return boolean true if both proxyUser and proxyPassword are present
     */
    public function isAuthenticatedProxy()
    {
        $proxyUser = $this->getProxyUser();
        $proxyPwd = $this->getProxyPassword();
        return !empty($proxyUser) && !empty($proxyPwd);
    }

    /**
     * returns http protocol depending on environment
     *
     * @return string http || https
     */
    public function protocol()
    {
        return $this->sslOn() ? 'https' : 'http';
    }

    /**
     * returns gateway server name depending on environment
     *
     * @return string server domain name
     */
    public function serverName()
    {
        switch ($this->_environment) {
            case 'production':
                $serverName = 'api.braintreegateway.com';
                break;
            case 'qa':
                $serverName = 'gateway.qa.braintreepayments.com';
                break;
            case 'sandbox':
                $serverName = 'api.sandbox.braintreegateway.com';
                break;
            case 'development':
            case 'integration':
            default:
                $serverName = 'localhost';
                break;
        }

        return $serverName;
    }

    /**
     * returns Braintree GraphQL server name depending on environment
     *
     * @return string graphql domain name
     */
    public function graphQLServerName()
    {
        switch ($this->_environment) {
            case 'production':
                $graphQLServerName = 'payments.braintree-api.com';
                break;
            case 'qa':
                $graphQLServerName = 'payments-qa.dev.braintree-api.com';
                break;
            case 'sandbox':
                $graphQLServerName = 'payments.sandbox.braintree-api.com';
                break;
            case 'development':
            case 'integration':
            default:
                $graphQLServerName = 'graphql.bt.local';
                break;
        }

        return $graphQLServerName;
    }

    /**
     * returns boolean indicating SSL is on or off for this session,
     * depending on environment
     *
     * @return boolean
     */
    public function sslOn()
    {
        switch ($this->_environment) {
            case 'integration':
            case 'development':
                $ssl = false;
                break;
            case 'production':
            case 'qa':
            case 'sandbox':
            default:
                $ssl = true;
                break;
        }

        return $ssl;
    }

    /**
     * log message to default logger
     *
     * @param string $message to log
     *
     * @return string
     */
    public function logMessage($message)
    {
        error_log('[Braintree] ' . $message);
    }
}
Configuration::reset();
