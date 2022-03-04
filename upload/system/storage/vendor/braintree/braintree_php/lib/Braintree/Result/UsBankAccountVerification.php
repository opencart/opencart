<?php

namespace Braintree\Result;

use Braintree\RiskData;
use Braintree\Util;
use Braintree\UsBankAccount;
use Braintree\Base;

/**
 * Braintree US Bank Account Verification Result
 *
 * This object is returned as part of an Error Result; it provides
 * access to the credit card verification data from the gateway
 *
 * See our {@link https://developer.paypal.com/braintree/docs/guides/acv/server-side developer docs} for more information
 */
class UsBankAccountVerification extends Base
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

    private $_gatewayRejectionReason;
    private $_status;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attributes)
    {
        $this->_initializeFromArray($attributes);

        $usBankAccount = isset($attributes['usBankAccount']) ?
            UsBankAccount::factory($attributes['usBankAccount']) :
            null;
        $this->usBankAccount = $usBankAccount;
    }

    /**
     * initializes instance properties from the keys/values of an array
     *
     * @param <type> $aAttribs array of properties to set - single level
     *
     * @return void
     */
    private function _initializeFromArray($attributes)
    {
        $this->_attributes = $attributes;
        foreach ($attributes as $name => $value) {
            $varName = "_$name";
            $this->$varName = $value;
        }
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __get($name)
    {
        $varName = "_$name";
        return isset($this->$varName) ? $this->$varName : null;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }

    /**
     * returns an array of all possible US Bank Account Verification statuses
     *
     * @return array
     */
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

    /**
     * returns an array of all possible US Bank Account Verification methods
     *
     * @return array
     */
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
