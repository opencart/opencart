<?php

namespace Braintree\MerchantAccount;

use Braintree\Base;

/**
 * Braintree BusinessDetails class
 *
 * Object containing information on business details of a merchant account
 */
class BusinessDetails extends Base
{
    protected function _initialize($businessAttribs)
    {
        $this->_attributes = $businessAttribs;
        if (isset($businessAttribs['address'])) {
            $this->_set('addressDetails', new AddressDetails($businessAttribs['address']));
        }
    }

    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return BusinessDetails
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }
}
