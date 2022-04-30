<?php
namespace Test\Braintree;

use Braintree;

class OAuthTestHelper
{
    public static function createGrant($gateway, $params)
    {
        $http = new Braintree\Http($gateway->config);
        $http->useClientCredentials();
        $response = $http->post('/oauth_testing/grants', ['grant' => $params]);
        return $response['grant']['code'];
    }

    public static function createCredentials($params)
    {
        $gateway = new Braintree\Gateway([
            'clientId' => $params['clientId'],
            'clientSecret' => $params['clientSecret']
        ]);

        $code = OAuthTestHelper::createGrant($gateway, [
            'merchant_public_id' => $params['merchantId'],
            'scope' => 'read_write'
        ]);

        $credentials = $gateway->oauth()->createTokenFromCode([
            'code' => $code,
            'scope' => 'read_write',
        ]);

        return $credentials;
    }
}
