<?php

namespace Braintree;

/**
 * Braintree AddOnGateway module
 *
 * Manages subscription addons
 */
class AddOnGateway
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
     * Retrieve all add ons
     *
     * @return AddOn[]
     */
    public function all()
    {
        $path = $this->_config->merchantPath() . '/add_ons';
        $response = $this->_http->get($path);

        $addOns = ["addOn" => $response['addOns']];

        return Util::extractAttributeAsArray(
            $addOns,
            'addOn'
        );
    }
}
