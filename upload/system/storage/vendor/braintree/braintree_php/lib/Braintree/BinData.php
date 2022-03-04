<?php

namespace Braintree;

/**
 * Bank Identification Number (BIN) Data Class
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/payment-method-nonce/php#bin_data developer docs} for information on attributes
 */
class BinData extends Base
{
    /**
     * Creates an instance of an BinData from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return BinData
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
