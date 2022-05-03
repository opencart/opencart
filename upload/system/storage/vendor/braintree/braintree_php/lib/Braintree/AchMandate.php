<?php
namespace Braintree;

/**
 * Braintree AchMandate module
 * PHP Version 5
 *
 * @package   Braintree
 *
 * @property-read string $text
 * @property-read string $acceptedAt
 */
class AchMandate extends Base
{
    /**
     * create a printable representation of the object as:
     * ClassName[property=value, property=value]
     * @ignore
     * @return string
     */
    public function  __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }

    /**
     * sets instance properties from an array of values
     *
     * @ignore
     * @access protected
     * @param array $achAttribs array of achMandate data
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
     * @ignore
     * @return AchMandate
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;

    }
}
class_alias('Braintree\AchMandate', 'Braintree_Mandate');
