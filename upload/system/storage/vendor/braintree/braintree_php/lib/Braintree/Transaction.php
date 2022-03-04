<?php

// phpcs:disable Generic.Files.LineLength
namespace Braintree;

/**
 * Braintree Transaction processor creates and manages transactions
 *
 * At minimum, an amount, credit card number, and
 * credit card expiration date are required.
 *
 * <b>Minimalistic example:</b>
 * <code>
 * Transaction::saleNoValidate(array(
 *   'amount' => '100.00',
 *   'creditCard' => array(
 *       'number' => '5105105105105100',
 *       'expirationDate' => '05/12',
 *       ),
 *   ));
 * </code>
 *
 * <b>Full example:</b>
 * <code>
 * Transaction::saleNoValidate(array(
 *    'amount'      => '100.00',
 *    'orderId'    => '123',
 *    'channel'    => 'MyShoppingCardProvider',
 *    'creditCard' => array(
 *         // if token is omitted, the gateway will generate a token
 *         'token' => 'credit_card_123',
 *         'number' => '5105105105105100',
 *         'expirationDate' => '05/2011',
 *         'cvv' => '123',
 *    ),
 *    'customer' => array(
 *     // if id is omitted, the gateway will generate an id
 *     'id'    => 'customer_123',
 *     'firstName' => 'Dan',
 *     'lastName' => 'Smith',
 *     'company' => 'Braintree',
 *     'email' => 'dan@example.com',
 *     'phone' => '419-555-1234',
 *     'fax' => '419-555-1235',
 *     'website' => 'http://braintreepayments.com'
 *    ),
 *    'billing'    => array(
 *      'firstName' => 'Carl',
 *      'lastName'  => 'Jones',
 *      'company'    => 'Braintree',
 *      'streetAddress' => '123 E Main St',
 *      'extendedAddress' => 'Suite 403',
 *      'locality' => 'Chicago',
 *      'region' => 'IL',
 *      'postalCode' => '60622',
 *      'countryName' => 'United States of America'
 *    ),
 *    'shipping' => array(
 *      'firstName'    => 'Andrew',
 *      'lastName'    => 'Mason',
 *      'company'    => 'Braintree',
 *      'streetAddress'    => '456 W Main St',
 *      'extendedAddress'    => 'Apt 2F',
 *      'locality'    => 'Bartlett',
 *      'region'    => 'IL',
 *      'postalCode'    => '60103',
 *      'countryName'    => 'United States of America'
 *    ),
 *    'customFields'    => array(
 *      'birthdate'    => '11/13/1954'
 *    )
 *  )
 * </code>
 *
 * <b>== Storing in the Vault ==</b>
 *
 * The customer and credit card information used for
 * a transaction can be stored in the vault by setting
 * <i>transaction[options][storeInVault]</i> to true.
 * <code>
 *   $transaction = Transaction::saleNoValidate(array(
 *     'customer' => array(
 *       'firstName'    => 'Adam',
 *       'lastName'    => 'Williams'
 *     ),
 *     'creditCard'    => array(
 *       'number'    => '5105105105105100',
 *       'expirationDate'    => '05/2012'
 *     ),
 *     'options'    => array(
 *       'storeInVault'    => true
 *     )
 *   ));
 *
 *  echo $transaction->customerDetails->id
 *  // '865534'
 *  echo $transaction->creditCardDetails->token
 *  // '6b6m'
 * </code>
 *
 * To also store the billing address in the vault, pass the
 * <b>addBillingAddressToPaymentMethod</b> option.
 * <code>
 *   Transaction.saleNoValidate(array(
 *    ...
 *     'options' => array(
 *       'storeInVault' => true
 *       'addBillingAddressToPaymentMethod' => true
 *     )
 *   ));
 * </code>
 *
 * <b>== Submitting for Settlement==</b>
 *
 * This can only be done when the transction's
 * status is <b>authorized</b>. If <b>amount</b> is not specified,
 * the full authorized amount will be settled. If you would like to settle
 * less than the full authorized amount, pass the desired amount.
 * You cannot settle more than the authorized amount.
 *
 * A transaction can be submitted for settlement when created by setting
 * $transaction[options][submitForSettlement] to true.
 *
 * <code>
 *   $transaction = Transaction::saleNoValidate(array(
 *     'amount'    => '100.00',
 *     'creditCard'    => array(
 *       'number'    => '5105105105105100',
 *       'expirationDate'    => '05/2012'
 *     ),
 *     'options'    => array(
 *       'submitForSettlement'    => true
 *     )
 *   ));
 * </code>
 *
 * For more detailed information on Transactions, see {@link https://developer.paypal.com/braintree/docs/reference/response/transaction our developer docs}
// phpcs:enable Generic.Files.LineLength
 */
class Transaction extends Base
{
    // Transaction Status
    const AUTHORIZATION_EXPIRED    = 'authorization_expired';
    const AUTHORIZING              = 'authorizing';
    const AUTHORIZED               = 'authorized';
    const GATEWAY_REJECTED         = 'gateway_rejected';
    const FAILED                   = 'failed';
    const PROCESSOR_DECLINED       = 'processor_declined';
    const SETTLED                  = 'settled';
    const SETTLING                 = 'settling';
    const SUBMITTED_FOR_SETTLEMENT = 'submitted_for_settlement';
    const VOIDED                   = 'voided';
    const UNRECOGNIZED             = 'unrecognized';
    const SETTLEMENT_DECLINED      = 'settlement_declined';
    const SETTLEMENT_PENDING       = 'settlement_pending';
    const SETTLEMENT_CONFIRMED     = 'settlement_confirmed';

    // Transaction Escrow Status
    const ESCROW_HOLD_PENDING    = 'hold_pending';
    const ESCROW_HELD            = 'held';
    const ESCROW_RELEASE_PENDING = 'release_pending';
    const ESCROW_RELEASED        = 'released';
    const ESCROW_REFUNDED        = 'refunded';

    // Transaction Types
    const SALE   = 'sale';
    const CREDIT = 'credit';

    // Transaction Created Using
    const FULL_INFORMATION = 'full_information';
    const TOKEN            = 'token';

    // Transaction Sources
    const API           = 'api';
    const CONTROL_PANEL = 'control_panel';
    const RECURRING     = 'recurring';

    // Gateway Rejection Reason
    const AVS            = 'avs';
    const AVS_AND_CVV    = 'avs_and_cvv';
    const CVV            = 'cvv';
    const DUPLICATE      = 'duplicate';
    const FRAUD          = 'fraud';
    const RISK_THRESHOLD = 'risk_threshold';
    const THREE_D_SECURE = 'three_d_secure';
    const TOKEN_ISSUANCE = 'token_issuance';
    const APPLICATION_INCOMPLETE = 'application_incomplete';

    // Industry Types
    const LODGING_INDUSTRY           = 'lodging';
    const TRAVEL_AND_CRUISE_INDUSTRY = 'travel_cruise';
    const TRAVEL_AND_FLIGHT_INDUSTRY = 'travel_flight';

    // Additional Charge Types
    const RESTAURANT = 'lodging';
    const GIFT_SHOP  = 'gift_shop';
    const MINI_BAR   = 'mini_bar';
    const TELEPHONE  = 'telephone';
    const LAUNDRY    = 'laundry';
    const OTHER      = 'other';

    /**
     * sets instance properties from an array of values
     *
     * @param array $transactionAttribs array of transaction data
     *
     * @return void
     */
    protected function _initialize($transactionAttribs)
    {
        $this->_attributes = $transactionAttribs;

        if (isset($transactionAttribs['applePay'])) {
            $this->_set(
                'applePayCardDetails',
                new Transaction\ApplePayCardDetails(
                    $transactionAttribs['applePay']
                )
            );
        }

        // Rename androidPayCard from API responses to GooglePayCard
        if (isset($transactionAttribs['androidPayCard'])) {
            $this->_set(
                'googlePayCardDetails',
                new Transaction\GooglePayCardDetails(
                    $transactionAttribs['androidPayCard']
                )
            );
        }

        if (isset($transactionAttribs['visaCheckoutCard'])) {
            $this->_set(
                'visaCheckoutCardDetails',
                new Transaction\VisaCheckoutCardDetails(
                    $transactionAttribs['visaCheckoutCard']
                )
            );
        }

        if (isset($transactionAttribs['samsungPayCard'])) {
            $this->_set(
                'samsungPayCardDetails',
                new Transaction\SamsungPayCardDetails(
                    $transactionAttribs['samsungPayCard']
                )
            );
        }

        if (isset($transactionAttribs['venmoAccount'])) {
            $this->_set(
                'venmoAccountDetails',
                new Transaction\VenmoAccountDetails(
                    $transactionAttribs['venmoAccount']
                )
            );
        }

        if (isset($transactionAttribs['creditCard'])) {
            $this->_set(
                'creditCardDetails',
                new Transaction\CreditCardDetails(
                    $transactionAttribs['creditCard']
                )
            );
        }

        if (isset($transactionAttribs['usBankAccount'])) {
            $this->_set(
                'usBankAccount',
                new Transaction\UsBankAccountDetails(
                    $transactionAttribs['usBankAccount']
                )
            );
        }

        if (isset($transactionAttribs['paypal'])) {
            $this->_set(
                'paypalDetails',
                new Transaction\PayPalDetails(
                    $transactionAttribs['paypal']
                )
            );
        }

        if (isset($transactionAttribs['paypalHere'])) {
            $this->_set(
                'paypalHereDetails',
                new Transaction\PayPalHereDetails(
                    $transactionAttribs['paypalHere']
                )
            );
        }

        if (isset($transactionAttribs['localPayment'])) {
            $this->_set(
                'localPaymentDetails',
                new Transaction\LocalPaymentDetails(
                    $transactionAttribs['localPayment']
                )
            );
        }

        if (isset($transactionAttribs['customer'])) {
            $this->_set(
                'customerDetails',
                new Transaction\CustomerDetails(
                    $transactionAttribs['customer']
                )
            );
        }

        if (isset($transactionAttribs['billing'])) {
            $this->_set(
                'billingDetails',
                new Transaction\AddressDetails(
                    $transactionAttribs['billing']
                )
            );
        }

        if (isset($transactionAttribs['shipping'])) {
            $this->_set(
                'shippingDetails',
                new Transaction\AddressDetails(
                    $transactionAttribs['shipping']
                )
            );
        }

        if (isset($transactionAttribs['subscription'])) {
            $this->_set(
                'subscriptionDetails',
                new Transaction\SubscriptionDetails(
                    $transactionAttribs['subscription']
                )
            );
        }

        if (isset($transactionAttribs['descriptor'])) {
            $this->_set(
                'descriptor',
                new Descriptor(
                    $transactionAttribs['descriptor']
                )
            );
        }

        if (isset($transactionAttribs['disbursementDetails'])) {
            $this->_set(
                'disbursementDetails',
                new DisbursementDetails($transactionAttribs['disbursementDetails'])
            );
        }

        $disputes = [];
        if (isset($transactionAttribs['disputes'])) {
            foreach ($transactionAttribs['disputes'] as $dispute) {
                $disputes[] = Dispute::factory($dispute);
            }
        }

        $this->_set('disputes', $disputes);

        $statusHistory = [];
        if (isset($transactionAttribs['statusHistory'])) {
            foreach ($transactionAttribs['statusHistory'] as $history) {
                $statusHistory[] = new Transaction\StatusDetails($history);
            }
        }

        $this->_set('statusHistory', $statusHistory);

        $addOnArray = [];
        if (isset($transactionAttribs['addOns'])) {
            foreach ($transactionAttribs['addOns'] as $addOn) {
                $addOnArray[] = AddOn::factory($addOn);
            }
        }
        $this->_set('addOns', $addOnArray);

        $discountArray = [];
        if (isset($transactionAttribs['discounts'])) {
            foreach ($transactionAttribs['discounts'] as $discount) {
                $discountArray[] = Discount::factory($discount);
            }
        }
        $this->_set('discounts', $discountArray);

        $authorizationAdjustments = [];
        if (isset($transactionAttribs['authorizationAdjustments'])) {
            foreach ($transactionAttribs['authorizationAdjustments'] as $authorizationAdjustment) {
                $authorizationAdjustments[] = AuthorizationAdjustment::factory($authorizationAdjustment);
            }
        }

        $this->_set('authorizationAdjustments', $authorizationAdjustments);

        if (isset($transactionAttribs['riskData'])) {
            $this->_set('riskData', RiskData::factory($transactionAttribs['riskData']));
        }
        if (isset($transactionAttribs['threeDSecureInfo'])) {
            $this->_set('threeDSecureInfo', ThreeDSecureInfo::factory($transactionAttribs['threeDSecureInfo']));
        }
        if (isset($transactionAttribs['facilitatedDetails'])) {
            $this->_set('facilitatedDetails', FacilitatedDetails::factory($transactionAttribs['facilitatedDetails']));
        }
        if (isset($transactionAttribs['facilitatorDetails'])) {
            $this->_set('facilitatorDetails', FacilitatorDetails::factory($transactionAttribs['facilitatorDetails']));
        }
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        // array of attributes to print
        $display = [
            'id', 'type', 'amount', 'status',
            'createdAt', 'creditCardDetails', 'customerDetails'
            ];

        $displayAttributes = [];
        foreach ($display as $attrib) {
            $displayAttributes[$attrib] = $this->$attrib;
        }
        return __CLASS__ . '[' .
                Util::attributesToString($displayAttributes) . ']';
    }

    /*
     * Checks if one transaction's ID is the same as another transaction's Id.
     *
     * @param string $otherTx to be compared
     *
     * @return bool
     */
    public function isEqual($otherTx)
    {
        return $this->id === $otherTx->id;
    }

    //NEXT_MAJOR_VERSION this function is only used for tests, the assertions this function provides are obfuscated.
    //We should remove this function and update the tests to be more clear in what we're asserting.
    public function vaultCreditCard()
    {
        $token = $this->creditCardDetails->token;
        if (empty($token)) {
            return null;
        } else {
            return CreditCard::find($token);
        }
    }

    //NEXT_MAJOR_VERSION this function is only used for tests, the assertions this function provides are obfuscated.
    //We should remove this function and update the tests to be more clear in what we're asserting.
    public function vaultCustomer()
    {
        $customerId = $this->customerDetails->id;
        if (empty($customerId)) {
            return null;
        } else {
            return Customer::find($customerId);
        }
    }

    /**
     * Checks if transactions is disbursed
     *
     * @return boolean
     */
    public function isDisbursed()
    {
        return $this->disbursementDetails->isValid();
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @see TransactionLineItemGateway::findAll()
     *
     * @return ResourceCollection of TransactionLineItem objects
     */
    public function lineItems()
    {
        return Configuration::gateway()->transactionLineItem()->findAll($this->id);
    }

    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return Transaction
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $transactionId unique identifier
     * @param string $amount        to be adjusted
     *
     * @see TransactionGateway::adjustAuthorization()
     *
     * @return Transction|Result\Error
     */
    public static function adjustAuthorization($transactionId, $amount)
    {
        return Configuration::gateway()->transaction()->adjustAuthorization($transactionId, $amount);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $transactionId to be cloned
     * @param mixed  $attribs       containing any additional request parameters
     *
     * @see TransactionGateway::cloneTransaction()
     *
     * @return Transction|Result\Error
     */
    public static function cloneTransaction($transactionId, $attribs)
    {
        return Configuration::gateway()->transaction()->cloneTransaction($transactionId, $attribs);
    }

    //NEXT_MAJOR_VERSION remove this function, it was only used for a Transparent Redirect test that no longer exists
    public static function createTransactionUrl()
    {
        return Configuration::gateway()->transaction()->createTransactionUrl();
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param mixed $attribs containing any request parameters
     *
     * @see TransactionGateway::credit()
     *
     * @return Result\Successful|Result\Error
     */
    public static function credit($attribs)
    {
        return Configuration::gateway()->transaction()->credit($attribs);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param mixed $attribs containing any request parameters
     *
     * @see TransactionGateway::creditNoValidate()
     *
     * @return Transaction|Result\Error
     */
    public static function creditNoValidate($attribs)
    {
        return Configuration::gateway()->transaction()->creditNoValidate($attribs);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param mixed $id unique identifier of the transaction to find
     *
     * @see TransactionGateway::find()
     *
     * @return Result\Successful|Exception\NotFound
     */
    public static function find($id)
    {
        return Configuration::gateway()->transaction()->find($id);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param mixed $attribs containing any request parameters
     *
     * @see TransactionGateway::sale()
     *
     * @return Result\Successful|Exception\NotFound
     */
    public static function sale($attribs)
    {
        return Configuration::gateway()->transaction()->sale($attribs);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param mixed $attribs containing any request parameters
     *
     * @see TransactionGateway::saleNoValidate()
     *
     * @return Transaction|Result\Error
     */
    public static function saleNoValidate($attribs)
    {
        return Configuration::gateway()->transaction()->saleNoValidate($attribs);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param mixed $query containing search fields
     *
     * @see TransactionGateway::search()
     *
     * @return ResourceCollection of Transaction objects
     */
    public static function search($query)
    {
        return Configuration::gateway()->transaction()->search($query);
    }

    /*
     * Static methods redirecting to gateway class
     *
     * @param mixed $query of search fields
     * @param array $ids to be fetched
     *
     * @see TransactionGateway::fetch()
     *
     * @return ResourceCollection of Transaction objects
     */
    public static function fetch($query, $ids)
    {
        return Configuration::gateway()->transaction()->fetch($query, $ids);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $transactionId unque identifier of the transaction to be voided
     *
     * @see TransactionGateway::void()
     *
     * @return Result\Successful|Exception\NotFound
     */
    public static function void($transactionId)
    {
        return Configuration::gateway()->transaction()->void($transactionId);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $transactionId unque identifier of the transaction to be voided
     *
     * @see TransactionGateway::voidNoValidate()
     *
     * @return Transaction|Result\Error
     */
    public static function voidNoValidate($transactionId)
    {
        return Configuration::gateway()->transaction()->voidNoValidate($transactionId);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $transactionId unque identifier of the transaction to be submitted for settlement
     * @param string $amount        optional
     * @param mixed  $attribs       any additional request parameters
     *
     * @see TransactionGateway::submitForSettlement()
     *
     * @return Result\Successful|Exception\NotFound
     */
    public static function submitForSettlement($transactionId, $amount = null, $attribs = [])
    {
        return Configuration::gateway()->transaction()->submitForSettlement($transactionId, $amount, $attribs);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $transactionId unque identifier of the transaction to be submitted for settlement
     * @param string $amount        optional
     * @param mixed  $attribs       any additional request parameters
     *
     * @see TransactionGateway::submitForSettlement()
     *
     * @return Transaction|Result\Error
     */
    public static function submitForSettlementNoValidate($transactionId, $amount = null, $attribs = [])
    {
        // phpcs:ignore Generic.Files.LineLength
        return Configuration::gateway()->transaction()->submitForSettlementNoValidate($transactionId, $amount, $attribs);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $transactionId to be updated
     * @param array  $attribs       attributes to be updated in the request
     *
     * @see TransactionGateway::updateDetails()
     *
     * @return Result\Successful|Result\Error
     */
    public static function updateDetails($transactionId, $attribs = [])
    {
        return Configuration::gateway()->transaction()->updateDetails($transactionId, $attribs);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $transactionId unque identifier of the transaction to be submitted for settlement
     * @param string $amount        optional
     * @param mixed  $attribs       any additional request parameters
     *
     * @see TransactionGateway::submitForPartialSettlement()
     *
     * @return Result\Successful|Exception\NotFound
     */
    public static function submitForPartialSettlement($transactionId, $amount, $attribs = [])
    {
        return Configuration::gateway()->transaction()->submitForPartialSettlement($transactionId, $amount, $attribs);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $transactionId unque identifier of the transaction to be held in escrow
     *
     * @see TransactionGateway::holdInEscrow()
     *
     * @return Result\Successful|Exception\NotFound
     */
    public static function holdInEscrow($transactionId)
    {
        return Configuration::gateway()->transaction()->holdInEscrow($transactionId);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $transactionId unque identifier of the transaction to be released from escrow
     *
     * @see TransactionGateway::releaseFromEscrow()
     *
     * @return Result\Successful|Exception\NotFound
     */
    public static function releaseFromEscrow($transactionId)
    {
        return Configuration::gateway()->transaction()->releaseFromEscrow($transactionId);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $transactionId unque identifier of the transaction whose escrow release is to be canceled
     *
     * @see TransactionGateway::cancelRelease()
     *
     * @return Result\Successful|Exception\NotFound
     */
    public static function cancelRelease($transactionId)
    {
        return Configuration::gateway()->transaction()->cancelRelease($transactionId);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $transactionId unque identifier of the transaction to be refunded
     * @param string $amount        to be refunded, optional
     *
     * @see TransactionGateway::refund()
     *
     * @return Result\Successful|Exception\NotFound
     */
    public static function refund($transactionId, $amount = null)
    {
        return Configuration::gateway()->transaction()->refund($transactionId, $amount);
    }
}
