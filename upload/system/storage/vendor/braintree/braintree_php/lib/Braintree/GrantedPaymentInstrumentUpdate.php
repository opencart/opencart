<?php

namespace Braintree;

/**
 * Braintree GrantedPaymentInstrumentUpdate module
 */

/**
 * Manages Braintree GrantedPaymentInstrumentUpdate
 *
 * See our {@link https://developer.paypal.com/braintree/docs/guides/extend/grant-api/webhooks developer docs} for more information
 */
class GrantedPaymentInstrumentUpdate extends Base
{
    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return GrantedPaymentInstrumentUpdate
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
     * @param array $GrantedPaymentInstrumentAttribs array of grantedPaymentInstrumentUpdate data
     *
     * @return void
     */
    protected function _initialize($grantedPaymentInstrumentUpdateAttribs)
    {
        // set the attributes
        $this->_attributes = $grantedPaymentInstrumentUpdateAttribs;

        $paymentMethodNonce = isset($grantedPaymentInstrumentUpdateAttribs['paymentMethodNonce']) ?
            GrantedPaymentInstrumentUpdate::factory($grantedPaymentInstrumentUpdateAttribs['paymentMethodNonce']) :
            null;
        $this->_set('paymentMethodNonce', $paymentMethodNonce);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }
}
