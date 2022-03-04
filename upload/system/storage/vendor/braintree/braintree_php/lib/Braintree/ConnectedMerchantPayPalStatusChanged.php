<?php

namespace Braintree;

/**
 * Connected Merchant PayPal Status Changed Payload
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/general/webhooks/braintree-auth/php#notification-type-connected_merchant_paypal_status_changed developer docs} for information on attributes
 */
class ConnectedMerchantPayPalStatusChanged extends Base
{
    protected $_attributes = [];

    /**
     * Creates an instance of a ConnectedMerchantPayPalStatusChanged from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return ConnectedMerchantPayPalStatusChanged
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        $instance->_attributes['merchantId'] = $instance->_attributes['merchantPublicId'];

        return $instance;
    }

    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;
    }
}
