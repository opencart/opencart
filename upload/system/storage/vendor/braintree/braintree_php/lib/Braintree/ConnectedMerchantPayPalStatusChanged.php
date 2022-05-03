<?php
namespace Braintree;

/**
 * Connected Merchant PayPal Status Changed Payload
 *
 * @package    Braintree
 *
 * @property-read string $merchantPublicId
 * @property-read string $action
 * @property-read string $oauthApplicationClientId
 */
class ConnectedMerchantPayPalStatusChanged extends Base
{
    protected $_attributes = [];

    /**
     * @ignore
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        $instance->_attributes['merchantId'] = $instance->_attributes['merchantPublicId'];

        return $instance;
    }

    /**
     * @ignore
     */
    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;
    }
}
class_alias('Braintree\ConnectedMerchantPayPalStatusChanged', 'Braintree_ConnectedMerchantPayPalStatusChanged');
