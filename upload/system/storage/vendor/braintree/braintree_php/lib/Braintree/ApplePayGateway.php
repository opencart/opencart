<?php

namespace Braintree;

/**
 * Braintree ApplePayGateway module
 * Manages Apple Pay for Web
 */
class ApplePayGateway
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
     * Register a domain for apple pay
     *
     * @see https://developer.paypal.com/braintree/docs/guides/apple-pay/configuration#domain-registration
     *
     * @param string $domain to be registered
     *
     * @return Result\Successful|Result\Error
     */
    public function registerDomain($domain)
    {
        $path = $this->_config->merchantPath() . '/processing/apple_pay/validate_domains';
        $response = $this->_http->post($path, ['url' => $domain]);
        if (array_key_exists('response', $response) && $response['response']['success']) {
            return new Result\Successful();
        } elseif (array_key_exists('apiErrorResponse', $response)) {
            return new Result\Error($response['apiErrorResponse']);
        }
    }

    /*
     * Unregister a domain for apple pay
     *
     * @param string $domain to be unregistered
     *
     * @return Result\Successful
     */
    public function unregisterDomain($domain)
    {
        $path = $this->_config->merchantPath() . '/processing/apple_pay/unregister_domain';
        $this->_http->delete($path, ['url' => $domain]);
        return new Result\Successful();
    }

    /*
     * Retrieve a list of all registered domains for apple pay
     *
     * @return Result\Successful|Result\Error
     */
    public function registeredDomains()
    {
        $path = $this->_config->merchantPath() . '/processing/apple_pay/registered_domains';
        $response = $this->_http->get($path);
        if (array_key_exists('response', $response) && array_key_exists('domains', $response['response'])) {
            $options = ApplePayOptions::factory($response['response']);
            return new Result\Successful($options, 'applePayOptions');
        } elseif (array_key_exists('apiErrorResponse', $response)) {
            return new Result\Error($response['apiErrorResponse']);
        } else {
            throw new Exception\Unexpected('expected response or apiErrorResponse');
        }
    }
}
