<?php

namespace Braintree;

/**
 * Braintree AchMandate module
 *
 * See our {link https://developer.paypal.com/braintree/docs/reference/response/us-bank-account/php#ach_mandate developer docs} for information on attributes
 */
class AchMandate extends Base
{
    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }

    /**
     * sets instance properties from an array of values
     *
     * @param array $achAttribs array of achMandate data
     *
     * @return void
     */
    protected function _initialize($achAttribs)
    {
        // set the attributes
        $this->_attributes = $achAttribs;
    }

    /**
     *  factory method: returns an instance of AchMandate
     *  to the requesting method, with populated properties
     *
     * @param array $attributes response object attributes
     *
     * @return AchMandate
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }
}
