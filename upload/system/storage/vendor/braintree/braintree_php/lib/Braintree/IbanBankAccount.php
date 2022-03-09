<?php
namespace Braintree;

/**
 * Braintree IbanBankAccount module
 * PHP Version 5
 *
 * @package   Braintree
 *
 * @property-read string $maskedIban
 * @property-read string $bic
 * @property-read string $ibanCountry
 * @property-read string $description
 * @property-read string $ibanAccountNumberLast4
 */
class IbanBankAccount extends Base
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
     * @param array $ibanAttribs array of ibanBankAccount data
     * @return void
     */
    protected function _initialize($ibanAttribs)
    {
        // set the attributes
        $this->_attributes = $ibanAttribs;
    }

    /**
     *  factory method: returns an instance of IbanBankAccount
     *  to the requesting method, with populated properties
     * @ignore
     * @return IbanBankAccount
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }
}
class_alias('Braintree\IbanBankAccount', 'Braintree_IbanBankAccount');
