<?php

namespace Braintree;

/**
 * Create and Manage 3D Secure Info type objects
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/payment-method-nonce/php#three_d_secure_info developer docs} for information on attributes
 */
class ThreeDSecureInfo extends Base
{
    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return ThreeDSecureInfo
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

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }
}
