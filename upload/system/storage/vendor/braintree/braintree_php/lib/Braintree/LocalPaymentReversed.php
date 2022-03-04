<?php

namespace Braintree;

/**
 * Braintree LocalPaymentReversed module
 */

/**
 * Manages Braintree LocalPaymentReversed
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/general/webhooks/local-payment-methods developer docs} for more information
 */
class LocalPaymentReversed extends Base
{
    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return LocalPaymentReversed
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    /* instance methods */

    /**
     * sets instance properties from an array of values
     *
     * @param array $LocalPaymentReversedAttribs array of localPaymentReversed data
     *
     * @return void
     */
    protected function _initialize($localPaymentReversedAttribs)
    {
        // set the attributes
        $this->_attributes = $localPaymentReversedAttribs;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }
}
