<?php

class Braintree_AddOnGateway
{
    /**
     *
     * @var Braintree_Gateway
     */
    private $_gateway;
    
    /**
     *
     * @var Braintree_Configuration
     */
    private $_config;
    
    /**
     *
     * @var Braintree_Http
     */
    private $_http;

    /**
     * 
     * @param Braintree_Gateway $gateway
     */
    public function __construct($gateway)
    {
        $this->_gateway = $gateway;
        $this->_config = $gateway->config;
        $this->_config->assertHasAccessTokenOrKeys();
        $this->_http = new Braintree_Http($gateway->config);
    }

    /**
     * 
     * @return Braintree_AddOn[]
     */
    public function all()
    {
        $path = $this->_config->merchantPath() . '/add_ons';
        $response = $this->_http->get($path);

        $addOns = array("addOn" => $response['addOns']);

        return Braintree_Util::extractAttributeAsArray(
            $addOns,
            'addOn'
        );
    }
}
