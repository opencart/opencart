<?php
namespace Braintree\Result;

use Braintree\RiskData;
use Braintree\Util;
use Braintree\UsBankAccount;

/**
 * Braintree US Bank Account Verification Result
 *
 * This object is returned as part of an Error Result; it provides
 * access to the credit card verification data from the gateway
 *
 *
 * @package    Braintree
 * @subpackage Result
 *
 * @property-read string $id
 * @property-read string $status
 * @property-read string $verificationMethod
 * @property-read \DateTime $verificationDeterminedAt
 * @property-read Braintree\UsBankAccount $usBankAccount
 *
 */
class UsBankAccountVerification
{
    // Status
    const FAILED             = 'failed';
    const GATEWAY_REJECTED   = 'gateway_rejected';
    const PROCESSOR_DECLINED = 'processor_declined';
    const VERIFIED           = 'verified';
    const PENDING            = 'pending';

    const TOKENIZED_CHECK   = 'tokenized_check';
    const NETWORK_CHECK     = 'network_check';
    const INDEPENDENT_CHECK = 'independent_check';
    const MICRO_TRANSFERS   = 'micro_transfers';

    private $_attributes;
    private $_gatewayRejectionReason;
    private $_status;

    /**
     * @ignore
     */
    public function  __construct($attributes)
    {
        $this->_initializeFromArray($attributes);

        $usBankAccount = isset($attributes['usBankAccount']) ?
            UsBankAccount::factory($attributes['usBankAccount']) :
            null;
        $this->usBankAccount = $usBankAccount;
    }

    /**
     * initializes instance properties from the keys/values of an array
     * @ignore
     * @access protected
     * @param <type> $aAttribs array of properties to set - single level
     * @return void
     */
    private function _initializeFromArray($attributes)
    {
        $this->_attributes = $attributes;
        foreach($attributes AS $name => $value) {
            $varName = "_$name";
            $this->$varName = $value;
        }
    }

    /**
     * @ignore
     */
    public function  __get($name)
    {
        $varName = "_$name";
        return isset($this->$varName) ? $this->$varName : null;
    }

    /**
     * returns a string representation of the customer
     * @return string
     */
    public function  __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }

    public static function allStatuses()
    {
        return [
            UsBankAccountVerification::FAILED,
            UsBankAccountVerification::GATEWAY_REJECTED,
            UsBankAccountVerification::PROCESSOR_DECLINED,
            UsBankAccountVerification::VERIFIED,
            UsBankAccountVerification::PENDING,
        ];
    }

    public static function allVerificationMethods()
    {
        return [
            UsBankAccountVerification::TOKENIZED_CHECK,
            UsBankAccountVerification::NETWORK_CHECK,
            UsBankAccountVerification::INDEPENDENT_CHECK,
            UsBankAccountVerification::MICRO_TRANSFERS,
        ];
    }
}
class_alias('Braintree\Result\UsBankAccountVerification', 'Braintree_Result_UsBankAccountVerification');
