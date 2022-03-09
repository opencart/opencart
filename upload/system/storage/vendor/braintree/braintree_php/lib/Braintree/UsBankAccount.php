<?php
namespace Braintree;

/**
 * Braintree UsBankAccount module
 *
 * @package    Braintree
 * @category   Resources
 */

/**
 * Manages Braintree UsBankAccounts
 *
 * <b>== More information ==</b>
 *
 *
 * @package    Braintree
 * @category   Resources
 *
 * @property-read string $customerId
 * @property-read string $email
 * @property-read string $token
 * @property-read string $imageUrl
 * @property-read string $routingNumber
 * @property-read string $accountType
 * @property-read string $accountHolderName
 * @property-read string $last4
 * @property-read string $bankName
 * @property-read string $achMandate
 * @property-read boolean $default
 * @property-read boolean $verified
 */
class UsBankAccount extends Base
{
    /**
     *  factory method: returns an instance of UsBankAccount
     *  to the requesting method, with populated properties
     *
     * @ignore
     * @return UsBankAccount
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    /* instance methods */

    /**
     * sets instance properties from an array of values
     *
     * @access protected
     * @param array $usBankAccountAttribs array of usBankAccount data
     * @return void
     */
    protected function _initialize($usBankAccountAttribs)
    {
        // set the attributes
        $this->_attributes = $usBankAccountAttribs;

        $achMandate = isset($usBankAccountAttribs['achMandate']) ?
            AchMandate::factory($usBankAccountAttribs['achMandate']) :
            null;
        $this->_set('achMandate', $achMandate);

        if (isset($usBankAccountAttribs['verifications'])) {
            $verification_records = $usBankAccountAttribs['verifications'];

            $verifications = array();
            for ($i = 0; $i < count($verification_records); $i++) {
                $verifications[$i] = UsBankAccountVerification::factory($verification_records[$i]);
            }
            $this->_set('verifications', $verifications);
        } else {
            $this->_set('verifications', null);
        }
    }

    /**
     * create a printable representation of the object as:
     * ClassName[property=value, property=value]
     * @return string
     */
    public function  __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }

    /**
     * returns false if default is null or false
     *
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }

    // static methods redirecting to gateway

    public static function find($token)
    {
        return Configuration::gateway()->usBankAccount()->find($token);
    }

    public static function sale($token, $transactionAttribs)
    {
        $transactionAttribs['options'] = [
            'submitForSettlement' => true
        ];
        return Configuration::gateway()->usBankAccount()->sale($token, $transactionAttribs);
    }
}
class_alias('Braintree\UsBankAccount', 'Braintree_UsBankAccount');
