<?php
namespace Braintree;

/**
 * Connected Merchant Status Transitioned Payload
 *
 * @package    Braintree
 *
 * @property-read string $merchantPublicId
 * @property-read string $status
 * @property-read string $oauthApplicationClientId
 */
class ConnectedMerchantStatusTransitioned extends Base
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
class_alias('Braintree\ConnectedMerchantStatusTransitioned', 'Braintree_ConnectedMerchantStatusTransitioned');
