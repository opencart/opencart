<?php

namespace Braintree;

/**
 * Braintree VenmoProfileData module
 */
class VenmoProfileData extends Base
{
    /**
     *  factory method: returns an instance of VenmoProfileData
     *  to the requesting method, with populated properties
     *
     * @param array $attributes to be used in creating the object
     *
     * @return VenmoProfileData
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    protected function _initialize($venmoProfileDataAttribs)
    {
        $this->_attributes = $venmoProfileDataAttribs;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }
}
