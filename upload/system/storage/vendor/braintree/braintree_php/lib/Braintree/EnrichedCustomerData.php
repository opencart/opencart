<?php

namespace Braintree;

/**
 * Braintree EnrichedCustomerData module
 */
class EnrichedCustomerData extends Base
{
    /**
     *  factory method: returns an instance of EnrichedCustomerData
     *  to the requesting method, with populated properties
     *
     * @param array $attributes used to create the object
     *
     * @return EnrichedCustomerData
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    protected function _initialize($enrichedCustomerDataAttribs)
    {
        // set the attributes
        $this->_attributes = $enrichedCustomerDataAttribs;

        if (isset($enrichedCustomerDataAttribs['profileData'])) {
            $this->_set(
                'profileData',
                VenmoProfileData::factory(
                    $enrichedCustomerDataAttribs['profileData']
                )
            );
        }
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }
}
