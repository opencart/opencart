<?php

namespace Braintree;

/**
 * CredentialsParser registry
 */

class CredentialsParser
{
    private $_clientId;
    private $_clientSecret;
    private $_accessToken;
    private $_environment;
    private $_merchantId;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attribs)
    {
        foreach ($attribs as $kind => $value) {
            if ($kind == 'clientId') {
                $this->_clientId = $value;
            }
            if ($kind == 'clientSecret') {
                $this->_clientSecret = $value;
            }
            if ($kind == 'accessToken') {
                $this->_accessToken = $value;
            }
        }
        $this->parse();
    }

    private static $_validEnvironments = [
        'development',
        'integration',
        'sandbox',
        'production',
        'qa',
    ];

    /*
     * Parses environment credentials and sets the _environment variable
     *
     * @return object|Exception\Configuration
     */
    public function parse()
    {
        $environments = [];
        if (!empty($this->_clientId)) {
            $environments[] = ['clientId', $this->_parseClientCredential('clientId', $this->_clientId, 'client_id')];
        }
        if (!empty($this->_clientSecret)) {
            // phpcs:ignore Generic.Files.LineLength
            $environments[] = ['clientSecret', $this->_parseClientCredential('clientSecret', $this->_clientSecret, 'client_secret')];
        }
        if (!empty($this->_accessToken)) {
            $environments[] = ['accessToken', $this->_parseAccessToken()];
        }

        $checkEnv = $environments[0];
        foreach ($environments as $env) {
            if ($env[1] !== $checkEnv[1]) {
                throw new Exception\Configuration(
                    'Mismatched credential environments: ' . $checkEnv[0] . ' environment is ' . $checkEnv[1] .
                    ' and ' . $env[0] . ' environment is ' . $env[1]
                );
            }
        }

        self::assertValidEnvironment($checkEnv[1]);
        $this->_environment = $checkEnv[1];
    }

    /*
     * Checks that the environment passed is valid
     *
     * @param string $environment
     *
     * @return self|Exception\Configuration
     */
    public static function assertValidEnvironment($environment)
    {
        if (!in_array($environment, self::$_validEnvironments)) {
            throw new Exception\Configuration('"' .
                                    $environment . '" is not a valid environment.');
        }
    }

    private function _parseClientCredential($credentialType, $value, $expectedValuePrefix)
    {
        $explodedCredential = explode('$', $value);
        if (sizeof($explodedCredential) != 3) {
            $message = 'Incorrect ' . $credentialType . ' format. Expected: type$environment$token';
            throw new Exception\Configuration($message);
        }

        $gotValuePrefix = $explodedCredential[0];
        $environment = $explodedCredential[1];
        $token = $explodedCredential[2];

        if ($gotValuePrefix != $expectedValuePrefix) {
            throw new Exception\Configuration('Value passed for ' . $credentialType . ' is not a ' . $credentialType);
        }

        return $environment;
    }

    private function _parseAccessToken()
    {
        $accessTokenExploded = explode('$', $this->_accessToken);
        if (sizeof($accessTokenExploded) != 4) {
            $message = 'Incorrect accessToken syntax. Expected: type$environment$merchant_id$token';
            throw new Exception\Configuration($message);
        }

        $gotValuePrefix = $accessTokenExploded[0];
        $environment = $accessTokenExploded[1];
        $merchantId = $accessTokenExploded[2];
        $token = $accessTokenExploded[3];

        if ($gotValuePrefix != 'access_token') {
            throw new Exception\Configuration('Value passed for accessToken is not an accessToken');
        }

        $this->_merchantId = $merchantId;
        return $environment;
    }

    /*
     * Getter methid to retrieve the ClientId
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->_clientId;
    }

    /*
     * Getter methid to retrieve the ClientSecret
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->_clientSecret;
    }

    /*
     * Getter methid to retrieve the AccessToken
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->_accessToken;
    }

    /*
     * Getter methid to retrieve the Environment
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->_environment;
    }

    /*
     * Getter methid to retrieve the Merchant Id
     *
     * @return string
     */
    public function getMerchantId()
    {
        return $this->_merchantId;
    }
}
