<?php

namespace Braintree;

/**
 * Braintree LocalPaymentExpired module
 */
class LocalPaymentExpired extends Base
{
    /**
     *  factory method: returns an instance of LocalPaymentExpired
     *  to the requesting method, with populated properties
     *
     * @param array $attributes to be used in creating the object
     *
     * @return LocalPaymentExpired
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    protected function _initialize($localPaymentExpiredAttribs)
    {
        $this->_attributes = $localPaymentExpiredAttribs;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }
}
