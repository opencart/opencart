<?php
namespace Braintree;

/**
 * Braintree IdealPayment module
 *
 * @package    Braintree
 * @category   Resources
 */

/**
 * Manages Braintree IdealPayments
 *
 * <b>== More information ==</b>
 *
 *
 * @package    Braintree
 * @category   Resources
 *
 * @property-read string $id
 * @property-read string $idealTransactionId
 * @property-read string $currency
 * @property-read string $amount
 * @property-read string $status
 * @property-read string $orderId
 * @property-read string $issuer
 * @property-read string $ibanBankAccount
 */
class IdealPayment extends Base
{
    /**
     *  factory method: returns an instance of IdealPayment
     *  to the requesting method, with populated properties
     *
     * @ignore
     * @return IdealPayment
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
     * @param array $idealPaymentAttribs array of idealPayment data
     * @return void
     */
    protected function _initialize($idealPaymentAttribs)
    {
        // set the attributes
        $this->_attributes = $idealPaymentAttribs;

        $ibanBankAccount = isset($idealPaymentAttribs['ibanBankAccount']) ?
            IbanBankAccount::factory($idealPaymentAttribs['ibanBankAccount']) :
            null;
        $this->_set('ibanBankAccount', $ibanBankAccount);
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


    // static methods redirecting to gateway

    public static function find($idealPaymentId)
    {
        return Configuration::gateway()->idealPayment()->find($idealPaymentId);
    }

    public static function sale($idealPaymentId, $transactionAttribs)
    {
        $transactionAttribs['options'] = [
            'submitForSettlement' => true
        ];
        return Configuration::gateway()->idealPayment()->sale($idealPaymentId, $transactionAttribs);
    }
}
class_alias('Braintree\IdealPayment', 'Braintree_IdealPayment');
