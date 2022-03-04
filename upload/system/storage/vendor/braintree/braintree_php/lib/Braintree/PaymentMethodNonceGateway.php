<?php

namespace Braintree;

/**
 * Braintree PaymentMethodNonceGateway module
 */
class PaymentMethodNonceGateway
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
    }

    /**
     * Create a payment method nonce from an existing payment method's token
     *
     * @param string     $token  the identifier of the payment method
     * @param mixed|null $params additional parameters to be included in the request
     *
     * @return PaymentMethodNonce|Error
     */
    public function create($token, $params = [])
    {
        $subPath = '/payment_methods/' . $token . '/nonces';
        $fullPath = $this->_config->merchantPath() . $subPath;
        $schema = [[
            'paymentMethodNonce' => [
                'merchantAccountId',
                'authenticationInsight',
            ['authenticationInsightOptions' => [
                    'amount',
                    'recurringCustomerConsent',
                    'recurringMaxAmount'
                ]
                ]]
        ]];
        Util::verifyKeys($schema, $params);
        $response = $this->_http->post($fullPath, $params);

        return new Result\Successful(
            PaymentMethodNonce::factory($response['paymentMethodNonce']),
            "paymentMethodNonce"
        );
    }

    /**
     * Find a Payment Method Nonce given the string value
     *
     * @param string $nonce to be found
     *
     * @throws NotFound
     *
     * @return PaymentMethodNonce
     */
    public function find($nonce)
    {
        try {
            $path = $this->_config->merchantPath() . '/payment_method_nonces/' . $nonce;
            $response = $this->_http->get($path);
            return PaymentMethodNonce::factory($response['paymentMethodNonce']);
        } catch (Exception\NotFound $e) {
            throw new Exception\NotFound(
                'payment method nonce with id ' . $nonce . ' not found'
            );
        }
    }
}
