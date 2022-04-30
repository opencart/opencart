<?php
namespace Braintree;

/**
 * Braintree UsBankAccountVerification module
 *
 * @package    Braintree
 * @category   Resources
 */

/**
 * Manages Braintree UsBankAccountVerifications
 *
 * <b>== More information ==</b>
 *
 *
 * @package    Braintree
 * @category   Resources
 *
 */
class UsBankAccountVerification extends Result\UsBankAccountVerification
{
    /**
     *  factory method: returns an instance of UsBankAccountVerification
     *  to the requesting method, with populated properties
     *
     * @ignore
     * @return UsBankAccountVerification
     */
    public static function factory($attributes)
    {
        $instance = new self($attributes);
        $instance->_initialize($attributes);
        return $instance;
    }

    /* instance methods */

    /**
     * sets instance properties from an array of values
     *
     * @access protected
     * @param array $usBankAccountVerificationAttribs array of usBankAccountVerification data
     * @return void
     */
    protected function _initialize($usBankAccountVerificationAttribs)
    {
        // set the attributes
        $this->_attributes = $usBankAccountVerificationAttribs;
    }

    /**
     * create a printable representation of the object as:
     * ClassName[property=value, property=value]
     * @return string
     */
    public function  __toString()
    {
        return __CLASS__ . '[' . Util::attributesToString($this->_attributes) . ']';
    }


    // static methods redirecting to gateway

    /**
     * finds a US bank account verification
     *
     * @access public
     * @param string $token unique id
     * @return UsBankAccountVerification
     */
    public static function find($token)
    {
        return Configuration::gateway()->usBankAccountVerification()->find($token);
    }

    /**
     * Returns a ResourceCollection of US bank account verifications matching the search query.
     *
     * @access public
     * @param mixed $query search query
     * @return ResourceCollection
     */
    public static function search($query)
    {
        return Configuration::gateway()->usBankAccountVerification()->search($query);
    }

    /**
     * Returns a ResourceCollection of US bank account verifications matching the search query.
     *
     * @access public
     * @param string $token unique id
     * @param array $amounts micro transfer amounts
     * @return ResourceCollection
     */
    public static function confirmMicroTransferAmounts($token, $amounts)
    {
        return Configuration::gateway()->usBankAccountVerification()->confirmMicroTransferAmounts($token, $amounts);
    }
}
class_alias('Braintree\UsBankAccountVerification', 'Braintree_UsBankAccountVerification');
