<?php

namespace Braintree;

/**
 * Braintree DiscountGateway module
 *
 * Manages subscription discounts
 */
class DiscountGateway
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

    /*
     * Get all discounts
     *
     * @return array of discount objects
     */
    public function all()
    {
        $path = $this->_config->merchantPath() . '/discounts';
        $response = $this->_http->get($path);

        $discounts = ["discount" => $response['discounts']];

        return Util::extractAttributeAsArray(
            $discounts,
            'discount'
        );
    }
}
