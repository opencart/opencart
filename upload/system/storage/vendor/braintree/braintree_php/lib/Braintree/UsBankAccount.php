<?php

namespace Braintree;

/**
 * Braintree UsBankAccount module
 * Manages Braintree UsBankAccounts
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/us-bank-account developer docs} for information on attributes
 */
class UsBankAccount extends Base
{
    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return UsBankAccount
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    /**
     * sets instance properties from an array of values
     *
     * @param array $usBankAccountAttribs array of usBankAccount data
     *
     * @return mixed
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

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
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

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $token the payment method identifier
     *
     * @see USBankAccountGateway::find()
     *
     * @return UsBankAccount|Error
     */
    public static function find($token)
    {
        return Configuration::gateway()->usBankAccount()->find($token);
    }

    /**
     * DO NOT USE, Use Transaction#sale instead. If you do choose to use this function, note that the subsequent transaction (if successful) will be automatically submitted for settlement.
     *
     * @param string $token              the payment method identifier
     * @param array  $transactionAttribs all other transaction parameters
     *
     * @return UsBankAccount|Error
     */
    public static function sale($token, $transactionAttribs)
    {
        $transactionAttribs['options'] = [
            'submitForSettlement' => true
        ];
        return Configuration::gateway()->usBankAccount()->sale($token, $transactionAttribs);
    }
}
