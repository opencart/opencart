<?php

namespace Braintree;

/**
 * Braintree ApplePayOptions module
 * Manages configuration and options for Apple Pay
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/apple-pay-options developer docs} for information on attributes
 */

class ApplePayOptions extends Base
{
    /**
     * Creates an instance of an ApplePayOptions from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return ApplePayOptions
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;
    }
}
