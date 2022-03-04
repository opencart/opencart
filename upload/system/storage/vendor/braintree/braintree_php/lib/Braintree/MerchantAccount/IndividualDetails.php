<?php

namespace Braintree\MerchantAccount;

use Braintree\Base;

/**
 * Braintree IndividualDetails class
 *
 * Object containing information on individual details of a merchant account
 */
class IndividualDetails extends Base
{
    protected function _initialize($individualAttribs)
    {
        $this->_attributes = $individualAttribs;
        if (isset($individualAttribs['address'])) {
            $this->_set('addressDetails', new AddressDetails($individualAttribs['address']));
        }
    }

    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return IndividualDetails
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }
}
