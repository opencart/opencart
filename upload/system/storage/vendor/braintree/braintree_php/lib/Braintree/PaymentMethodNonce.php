<?php

namespace Braintree;

/**
 * Braintree PaymentMethodNonce module
 */

/**
 * Creates and manages Braintree PaymentMethodNonces
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/payment-method-nonce developer docs} for information on attributes
 */
class PaymentMethodNonce extends Base
{
    /**
     * Static method from gateway class
     *
     * @param string     $token  the identifier of the payment method
     * @param array|null $params additional parameters to be included in the request
     *
     * @see PaymentMethodNonceGateway::create()
     *
     * @return PaymentMethodNonce|Error
     */
    public static function create($token, $params = [])
    {
        return Configuration::gateway()->paymentMethodNonce()->create($token, $params);
    }

    /*
     * Static method from gateway class
     *
     * @param string $nonce the payment method nonce string to return information about
     *
     * @see PaymentMethodNonceGateway::find()
     *
     * @return PaymentMethodNonce
     */
    public static function find($nonce)
    {
        return Configuration::gateway()->paymentMethodNonce()->find($nonce);
    }

    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return PaymentMethodNonce
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    protected function _initialize($nonceAttributes)
    {
        $this->_attributes = $nonceAttributes;
        $this->_set('nonce', $nonceAttributes['nonce']);
        $this->_set('type', $nonceAttributes['type']);

        if (isset($nonceAttributes['authenticationInsight'])) {
            $this->_set('authenticationInsight', $nonceAttributes['authenticationInsight']);
        }

        if (isset($nonceAttributes['binData'])) {
            $this->_set('binData', BinData::factory($nonceAttributes['binData']));
        }

        if (isset($nonceAttributes['threeDSecureInfo'])) {
            $this->_set('threeDSecureInfo', ThreeDSecureInfo::factory($nonceAttributes['threeDSecureInfo']));
        }
    }
}
