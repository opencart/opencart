<?php

namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree ClientTokenGateway module
 *
 * Manages Braintree ClientTokens
 * For more detailed information on ClientTokens, see {@link https://developer.paypal.com/braintree/docsreference/response/client-token/php our developer docs}. <br />
 */
class ClientTokenGateway
{
    private $_gateway;
    private $_config;
    private $_http;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($gateway)
    {
        $this->_gateway = $gateway;
        $this->_config = $gateway->config;
        $this->_config->assertHasAccessTokenOrKeys();
        $this->_http = new Http($gateway->config);
    }

    /**
     * Generate a client token for client-side authorization
     *
     * @param Optional $params containing request parameters
     *
     * @return string client token
     */
    public function generate($params = [])
    {
        if (!array_key_exists("version", $params)) {
            $params["version"] = ClientToken::DEFAULT_VERSION;
        }

        $this->conditionallyVerifyKeys($params);
        $generateParams = ["client_token" => $params];

        return $this->_doGenerate('/client_token', $generateParams);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function _doGenerate($subPath, $params)
    {
        $fullPath = $this->_config->merchantPath() . $subPath;
        $response = $this->_http->post($fullPath, $params);

        return $this->_verifyGatewayResponse($response);
    }

    /*
     * Checks if customer id is provided prior to verifying keys provided in params
     *
     * @param array $params to be verified
     *
     * @return array
     */
    public function conditionallyVerifyKeys($params)
    {
        if (array_key_exists("customerId", $params)) {
            Util::verifyKeys($this->generateWithCustomerIdSignature(), $params);
        } else {
            Util::verifyKeys($this->generateWithoutCustomerIdSignature(), $params);
        }
    }

    /*
     * returns an array of keys including customer id
     *
     * @return array
     *
     */
    public function generateWithCustomerIdSignature()
    {
        return [
            "version", "customerId", "proxyMerchantId",
            ["options" => ["makeDefault", "verifyCard", "failOnDuplicatePaymentMethod"]],
            "merchantAccountId"];
    }

    /*
     * returns an array of keys without customer id
     *
     * @return array
     */
    public function generateWithoutCustomerIdSignature()
    {
        return ["version", "proxyMerchantId", "merchantAccountId"];
    }

    /**
     * generic method for validating incoming gateway responses
     *
     * If the request is successful, returns a client token string.
     * Otherwise, throws an InvalidArgumentException with the error
     * response from the Gateway or an HTTP status code exception.
     *
     * @param array $response gateway response values
     *
     * @throws InvalidArgumentException | HTTP status code exception
     *
     * @return string client token
     */
    private function _verifyGatewayResponse($response)
    {
        if (isset($response['clientToken'])) {
            return $response['clientToken']['value'];
        } elseif (isset($response['apiErrorResponse'])) {
            throw new InvalidArgumentException(
                $response['apiErrorResponse']['message']
            );
        } else {
            throw new Exception\Unexpected(
                "Expected clientToken or apiErrorResponse"
            );
        }
    }
}
