<?php // phpcs:disable PEAR.Commenting

namespace Braintree;

class MerchantGateway
{
    private $_gateway;
    private $_config;
    private $_http;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($gateway)
    {
        $this->_gateway = $gateway;
        $this->_config = $gateway->config;
        $this->_config->assertHasClientCredentials();
        $this->_http = new Http($gateway->config);
        $this->_http->useClientCredentials();
    }

    public function create($attribs)
    {
        $response = $this->_http->post('/merchants/create_via_api', ['merchant' => $attribs]);
        return $this->_verifyGatewayResponse($response);
    }

    private function _verifyGatewayResponse($response)
    {
        if (isset($response['response']['merchant'])) {
            // return a populated instance of merchant
            return new Result\Successful([
                Merchant::factory($response['response']['merchant']),
                OAuthCredentials::factory($response['response']['credentials']),
            ]);
        } elseif (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } else {
            throw new Exception\Unexpected(
                "Expected merchant or apiErrorResponse"
            );
        }
    }
}
