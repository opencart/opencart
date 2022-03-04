<?php

namespace Braintree;

/**
 * Braintree UnknownPaymentMethod module
 * Manages Braintree UnknownPaymentMethod
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/payment-method developer docs} for information on attributes
 */

class UnknownPaymentMethod extends Base
{
    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return UnknownPaymentMethod
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $values = array_values($attributes);
        $instance->_initialize(array_shift($values));
        return $instance;
    }

    /* instance methods */

    /**
     * returns false if default is null or false
     *
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * sets instance properties from an array of values
     *
     * @param array $unknownPaymentMethodAttribs array of unknownPaymentMethod data
     *
     * @return void
     */
    protected function _initialize($unknownPaymentMethodAttribs)
    {
        // set the attributes
        $this->imageUrl = 'https://assets.braintreegateway.com/payment_method_logo/unknown.png';
        $this->_attributes = $unknownPaymentMethodAttribs;
    }
}
