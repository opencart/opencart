<?php
namespace Braintree;

/**
 * Braintree PaymentMethodNonce module
 *
 * @package    Braintree
 * @category   Resources
 */

/**
 * Creates and manages Braintree PaymentMethodNonces
 *
 * <b>== More information ==</b>
 *
 *
 * @package    Braintree
 * @category   Resources
 * 
 * @property-read \Braintree\BinData $binData
 * @property-read boolean $default
 * @property-read string $nonce
 * @property-read \Braintree\ThreeDSecureInfo $threeDSecureInfo
 * @property-read string $type
 */
class PaymentMethodNonce extends Base
{
    // static methods redirecting to gateway

    public static function create($token)
    {
        return Configuration::gateway()->paymentMethodNonce()->create($token);
    }

    public static function find($nonce)
    {
        return Configuration::gateway()->paymentMethodNonce()->find($nonce);
    }

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

        if(isset($nonceAttributes['threeDSecureInfo'])) {
            $this->_set('threeDSecureInfo', ThreeDSecureInfo::factory($nonceAttributes['threeDSecureInfo']));
        }

        if(isset($nonceAttributes['binData'])) {
            $this->_set('binData', BinData::factory($nonceAttributes['binData']));
        }
    }
}
class_alias('Braintree\PaymentMethodNonce', 'Braintree_PaymentMethodNonce');
