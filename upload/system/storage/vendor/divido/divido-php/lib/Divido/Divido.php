<?php

abstract class Divido
{
  /**
   * @var string The Divido Mercahnt to be used for requests.
   */
  public static $merchant;
  
  /**
   * @var string The Divido API key to be used for requests.
   */
  public static $apiKey;

  /**
   * @var string The Divido shared secdret to be used for requests.
   */
  public static $sharedSecret;
  
  /**
   * @var string The Divido API key to be used for requests.
   */
  public static $sandboxMode;

  /**
   * @var string The base URL for the Divido API.
   */
  public static $apiBase = 'https://secure.divido.com';

  /**
   * @var string The base URL for the Divido API.
   */
  public static $devApiBase = 'http://secure.divido.net';

  /**
   * @var string The base URL for the Divido API.
   */
  public static $sandboxApiBase = 'https://secure.sandbox.divido.com';

  /**
   * @var string The base URL for the Divido API.
   */
  public static $stagingApiBase = 'https://staging.sandbox.divido.com';

  /**
   * @var string The base URL for the Divido API.
   */
  public static $testingApiBase = 'https://testing.sandbox.divido.com';

  /**
   * @var string|null The version of the Divido API to use for requests.
   */
  public static $apiVersion = null;
  /**
   * @var boolean Defaults to true.
   */
  public static $verifySslCerts = false;
  const VERSION = '0.1';

  /**
   * @return string The API key used for requests.
   */
  public static function getMerchant()
  {
    return self::$apiKey;
  }

  /**
   * Sets the API key to be used for requests.
   *
   * @param string $merchant
   */
  public static function setMerchant($merchant)
  {
    self::setApiKey($merchant);
  }

  /**
   * @return string The shared secret used for requests.
   */
  public static function getSharedSecret()
  {
    return self::$sharedSecret;
  }

  /**
   * Sets the shared secret to be used for requests.
   *
   * @param string $sharedSecret
   */
  public static function setSharedSecret($sharedSecret)
  {
    self::$sharedSecret = $sharedSecret;
  }
  
  /**
   * @return string The sandbox mode used for requests.
   */
  public static function getSandboxMode()
  {
    return self::$sandboxMode;
  }

  /**
   * Sets the sandbox mode to be used for requests.
   *
   * @param string $merchant
   */
  public static function setSandboxMode($sandboxMode)
  {
    self::$sandboxMode = $sandboxMode;
    
    if ($sandboxMode) {
    	self::$apiBase = self::$sandboxApiBase;
    }
  }

  /**
   * @return string The API key used for requests.
   */
  public static function getApiKey()
  {
    return self::$apiKey;
  }

  /**
   * Sets the API key to be used for requests.
   *
   * @param string $apiKey
   */
  public static function setApiKey($apiKey)
  {
    self::$apiKey = $apiKey;

    if (substr($apiKey,0,7) == 'testing') {
      self::$apiBase = self::$testingApiBase;
    } else if (substr($apiKey,0,7) == 'staging') {
      self::$apiBase = self::$sandboxApiBase;
    } else if (substr($apiKey,0,3) == 'dev') {
      self::$apiBase = self::$devApiBase;
    } else if (substr($apiKey,0,7) == 'sandbox') {
      self::$apiBase = self::$sandboxApiBase;
    }
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

  /**
   * @return boolean
   */
  public static function getVerifySslCerts()
  {
    return self::$verifySslCerts;
  }

  /**
   * @param boolean $verify
   */
  public static function setVerifySslCerts($verify)
  {
    self::$verifySslCerts = $verify;
  }
}
