<?php

namespace Braintree;

/**
 * Braintree OAuthGateway module
 * Creates and manages Braintree Addresses
 */
class OAuthGateway
{
    private $_gateway;
    private $_config;
    private $_http;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($gateway)
    {
        $this->_gateway = $gateway;
        $this->_config = $gateway->config;
        $this->_http = new Http($gateway->config);
        $this->_http->useClientCredentials();

        $this->_config->assertHasClientCredentials();
    }

    /* Create an oAuth token from an authorization code
     *
     * @param mixed $params of request details
     *
     * @return Result\Successful|Result\Error
     */
    public function createTokenFromCode($params)
    {
        $params['grantType'] = "authorization_code";
        return $this->_createToken($params);
    }

    /* Create an oAuth token from a refresh token
     *
     * @param mixed $params of request details
     *
     * @return Result\Successful|Result\Error
     */
    public function createTokenFromRefreshToken($params)
    {
        $params['grantType'] = "refresh_token";
        return $this->_createToken($params);
    }

    /* Revoke an oAuth Access token
     *
     * @param mixed $params of request details
     *
     * @return Result\Successful|Result\Error
     */
    public function revokeAccessToken($accessToken)
    {
        $params = ['token' => $accessToken];
        $response = $this->_http->post('/oauth/revoke_access_token', $params);
        return $this->_verifyGatewayResponse($response);
    }

    private function _createToken($params)
    {
        $params = ['credentials' => $params];
        $response = $this->_http->post('/oauth/access_tokens', $params);
        return $this->_verifyGatewayResponse($response);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    private function _verifyGatewayResponse($response)
    {
        if (isset($response['credentials'])) {
            $result =  new Result\Successful(
                OAuthCredentials::factory($response['credentials'])
            );
            return $this->_mapSuccess($result);
        } elseif (isset($response['result'])) {
            $result =  new Result\Successful(
                OAuthResult::factory($response['result'])
            );
            return $this->_mapAccessTokenRevokeSuccess($result);
        } elseif (isset($response['apiErrorResponse'])) {
            $result = new Result\Error($response['apiErrorResponse']);
            return $this->_mapError($result);
        } else {
            throw new Exception\Unexpected(
                "Expected credentials or apiErrorResponse"
            );
        }
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function _mapError($result)
    {
        $error = $result->errors->deepAll()[0];

        if ($error->code == Error\Codes::OAUTH_INVALID_GRANT) {
            $result->error = 'invalid_grant';
        } elseif ($error->code == Error\Codes::OAUTH_INVALID_CREDENTIALS) {
            $result->error = 'invalid_credentials';
        } elseif ($error->code == Error\Codes::OAUTH_INVALID_SCOPE) {
            $result->error = 'invalid_scope';
        }
        $result->errorDescription = explode(': ', $error->message)[1];
        return $result;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function _mapAccessTokenRevokeSuccess($result)
    {
        $result->revocationResult = $result->success;
        return $result;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function _mapSuccess($result)
    {
        $credentials = $result->credentials;
        $result->accessToken = $credentials->accessToken;
        $result->refreshToken = $credentials->refreshToken;
        $result->tokenType = $credentials->tokenType;
        $result->expiresAt = $credentials->expiresAt;
        return $result;
    }

    /*
     * Create URL for oAuth connection
     *
     * @param array $params optional
     *
     * @return string
     */
    public function connectUrl($params = [])
    {
        $query = Util::camelCaseToDelimiterArray($params, '_');
        $query['client_id'] = $this->_config->getClientId();
        $queryString = preg_replace('/\%5B\d+\%5D/', '%5B%5D', http_build_query($query));

        return $this->_config->baseUrl() . '/oauth/connect?' . $queryString;
    }
}
