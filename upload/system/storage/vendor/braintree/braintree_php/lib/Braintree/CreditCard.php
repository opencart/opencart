<?php

namespace Braintree;

/**
 * Braintree CreditCard module
 * Creates and manages Braintree CreditCards
 *
 * For more detailed information on CreditCards, see {@link https://developer.paypal.com/braintree/docs/reference/response/credit-card our developer docs}<br />
 * For more detailed information on CreditCard verifications, see {@link https://developer.paypal.com/braintree/docs/reference/response/credit-card-verification our developer docs}
 */
class CreditCard extends Base
{
    // Card Type
    const AMEX = 'American Express';
    const CARTE_BLANCHE = 'Carte Blanche';
    const CHINA_UNION_PAY = 'China UnionPay';
    const DINERS_CLUB_INTERNATIONAL = 'Diners Club';
    const DISCOVER = 'Discover';
    const ELO = 'Elo';
    const JCB = 'JCB';
    const LASER = 'Laser';
    const MAESTRO = 'Maestro';
    const UK_MAESTRO = 'UK Maestro';
    const MASTER_CARD = 'MasterCard';
    const SOLO = 'Solo';
    const SWITCH_TYPE = 'Switch';
    const VISA = 'Visa';
    const UNKNOWN = 'Unknown';

    // Credit card origination location
    const INTERNATIONAL = "international";
    const US = "us";

    const PREPAID_YES = 'Yes';
    const PREPAID_NO = 'No';
    const PREPAID_UNKNOWN = 'Unknown';

    const PAYROLL_YES = 'Yes';
    const PAYROLL_NO = 'No';
    const PAYROLL_UNKNOWN = 'Unknown';

    const HEALTHCARE_YES = 'Yes';
    const HEALTHCARE_NO = 'No';
    const HEALTHCARE_UNKNOWN = 'Unknown';

    const DURBIN_REGULATED_YES = 'Yes';
    const DURBIN_REGULATED_NO = 'No';
    const DURBIN_REGULATED_UNKNOWN = 'Unknown';

    const DEBIT_YES = 'Yes';
    const DEBIT_NO = 'No';
    const DEBIT_UNKNOWN = 'Unknown';

    const COMMERCIAL_YES = 'Yes';
    const COMMERCIAL_NO = 'No';
    const COMMERCIAL_UNKNOWN = 'Unknown';

    const COUNTRY_OF_ISSUANCE_UNKNOWN = "Unknown";
    const ISSUING_BANK_UNKNOWN = "Unknown";
    const PRODUCT_ID_UNKNOWN = "Unknown";

    /* instance methods */
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
     * checks whether the card is expired based on the current date
     *
     * @return boolean
     */
    public function isExpired()
    {
        return $this->expired;
    }

    /**
     * checks whether the card is associated with venmo sdk
     *
     * @return boolean
     */
    public function isVenmoSdk()
    {
        return $this->venmoSdk;
    }

    /**
     * sets instance properties from an array of values
     *
     * @param array $creditCardAttribs array of creditcard data
     *
     * @return void
     */
    protected function _initialize($creditCardAttribs)
    {
        // set the attributes
        $this->_attributes = $creditCardAttribs;

        // map each address into its own object
        $billingAddress = isset($creditCardAttribs['billingAddress']) ?
            Address::factory($creditCardAttribs['billingAddress']) :
            null;

        $subscriptionArray = [];
        if (isset($creditCardAttribs['subscriptions'])) {
            foreach ($creditCardAttribs['subscriptions'] as $subscription) {
                $subscriptionArray[] = Subscription::factory($subscription);
            }
        }

        $this->_set('subscriptions', $subscriptionArray);
        $this->_set('billingAddress', $billingAddress);
        $this->_set('expirationDate', $this->expirationMonth . '/' . $this->expirationYear);
        $this->_set('maskedNumber', $this->bin . '******' . $this->last4);

        if (isset($creditCardAttribs['verifications']) && count($creditCardAttribs['verifications']) > 0) {
            $verifications = $creditCardAttribs['verifications'];
            usort($verifications, [$this, '_compareCreatedAtOnVerifications']);

            $this->_set('verification', CreditCardVerification::factory($verifications[0]));
        }
    }

    private function _compareCreatedAtOnVerifications($verificationAttrib1, $verificationAttrib2)
    {
        return ($verificationAttrib2['createdAt'] < $verificationAttrib1['createdAt']) ? -1 : 1;
    }

    /**
     * returns false if comparing object is not a CreditCard,
     * or is a CreditCard with a different id
     *
     * @param object $otherCreditCard customer to compare against
     *
     * @return boolean
     */
    public function isEqual($otherCreditCard)
    {
        return !($otherCreditCard instanceof self) ? false : $this->token === $otherCreditCard->token;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }

    /**
     * Creates an instance of an CreditCard from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return CreditCard
     */
    public static function factory($attributes)
    {
        $defaultAttributes = [
            'bin' => '',
            'expirationMonth'    => '',
            'expirationYear'    => '',
            'last4'  => '',
        ];

        $instance = new self();
        $instance->_initialize(array_merge($defaultAttributes, $attributes));
        return $instance;
    }

    /**
     * static method redirecting to gateway class
     *
     * @param array $attribs containing request parameters
     *
     * @see CreditCardGateway::create()
     *
     * @throws Exception\ValidationError
     *
     * @return Result\Successful|Result\Error
     */
    public static function create($attribs)
    {
        return Configuration::gateway()->creditCard()->create($attribs);
    }

    /**
     * Attempts the create operation assuming all data will validate
     * returns a CreditCard object instead of a Result
     *
     * @param array $attribs containing request parameters
     *
     * @throws Exception\ValidationError
     *
     * @return CreditCard
     */
    public static function createNoValidate($attribs)
    {
        return Configuration::gateway()->creditCard()->createNoValidate($attribs);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public static function createCreditCardUrl()
    {
        return Configuration::gateway()->creditCard()->createCreditCardUrl();
    }

    /**
     * Returns a ResourceCollection of expired credit cards
     *
     * @return ResourceCollection
     */
    public static function expired()
    {
        return Configuration::gateway()->creditCard()->expired();
    }

    /**
     * Returns a ResourceCollection of expired credit cards
     *
     * @param string $ids containing credit card IDs
     *
     * @return ResourceCollection
     */
    public static function fetchExpired($ids)
    {
        return Configuration::gateway()->creditCard()->fetchExpired($ids);
    }

    /**
     * Returns a ResourceCollection of credit cards expiring between start/end
     *
     * @param string $startDate the start date of search
     * @param string $endDate   the end date of search
     *
     * @return ResourceCollection
     */
    public static function expiringBetween($startDate, $endDate)
    {
        return Configuration::gateway()->creditCard()->expiringBetween($startDate, $endDate);
    }

    /**
     * Returns a ResourceCollection of credit cards expiring between start/end given a set of IDs
     *
     * @param string $startDate the start date of search
     * @param string $endDate   the end date of search
     * @param string $ids       containing ids to search
     *
     * @return ResourceCollection
     */
    public static function fetchExpiring($startDate, $endDate, $ids)
    {
        return Configuration::gateway()->creditCard()->fetchExpiring($startDate, $endDate, $ids);
    }

    /**
     * Find a creditcard by token
     *
     * @param string $token credit card unique id
     *
     * @throws Exception\NotFound
     *
     * @return CreditCard
     */
    public static function find($token)
    {
        return Configuration::gateway()->creditCard()->find($token);
    }

    /**
     * Convert a payment method nonce to a credit card
     *
     * @param string $nonce payment method nonce
     *
     * @throws Exception\NotFound
     *
     * @return CreditCard
     */
    public static function fromNonce($nonce)
    {
        return Configuration::gateway()->creditCard()->fromNonce($nonce);
    }

   /**
     * Create a credit on the card for the passed transaction
     *
     * @param string $token              belonging to the credit card
     * @param array  $transactionAttribs containing request parameters
     *
     * @return Result\Successful|Result\Error
     */
    public static function credit($token, $transactionAttribs)
    {
        return Configuration::gateway()->creditCard()->credit($token, $transactionAttribs);
    }

    /**
     * Create a credit on this card, assuming validations will pass
     *
     * Returns a Transaction object on success
     *
     * @param string $token              belonging to the credit card
     * @param array  $transactionAttribs containing request parameters
     *
     * @throws Exception\ValidationError
     *
     * @return Transaction
     */
    public static function creditNoValidate($token, $transactionAttribs)
    {
        return Configuration::gateway()->creditCard()->creditNoValidate($token, $transactionAttribs);
    }

    /**
     * Create a new sale for the current card
     *
     * @param string $token              belonging to the credit card
     * @param array  $transactionAttribs containing request parameters
     *
     * @return Result\Successful|Result\Error
     */
    public static function sale($token, $transactionAttribs)
    {
        return Configuration::gateway()->creditCard()->sale($token, $transactionAttribs);
    }

    /**
     * Create a new sale using this card, assuming validations will pass
     *
     * Returns a Transaction object on success
     *
     * @param string $token              belonging to the credit card
     * @param array  $transactionAttribs containing request parameters
     *
     * @throws Exception\ValidationsFailed
     *
     * @return Transaction
     */
    public static function saleNoValidate($token, $transactionAttribs)
    {
        return Configuration::gateway()->creditCard()->saleNoValidate($token, $transactionAttribs);
    }

    /**
     * Updates the creditcard record
     *
     * If calling this method in context, $token
     * is the 2nd attribute. $token is not sent in object context.
     *
     * @param string $token      (optional)
     * @param array  $attributes containing request parameters
     *
     * @return Result\Successful|Result\Error
     */
    public static function update($token, $attributes)
    {
        return Configuration::gateway()->creditCard()->update($token, $attributes);
    }

    /**
     * Update a creditcard record, assuming validations will pass
     *
     * If calling this method in context, $token
     * is the 2nd attribute. $token is not sent in object context.
     * returns a CreditCard object on success
     *
     * @param string $token      (optional)
     * @param array  $attributes containing request parameters
     *
     * @return CreditCard
     *
     * @throws Exception\ValidationsFailed
     */
    public static function updateNoValidate($token, $attributes)
    {
        return Configuration::gateway()->creditCard()->updateNoValidate($token, $attributes);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public static function updateCreditCardUrl()
    {
        return Configuration::gateway()->creditCard()->updateCreditCardUrl();
    }

    /**
     * Delete a credit card record
     *
     * @param string $token credit card identifier
     *
     * @return Result
     */
    public static function delete($token)
    {
        return Configuration::gateway()->creditCard()->delete($token);
    }

    /**
     * All credit card types in an array
     *
     * @return array
     */
    public static function allCardTypes()
    {
        return [
            CreditCard::AMEX,
            CreditCard::CARTE_BLANCHE,
            CreditCard::CHINA_UNION_PAY,
            CreditCard::DINERS_CLUB_INTERNATIONAL,
            CreditCard::DISCOVER,
            CreditCard::ELO,
            CreditCard::JCB,
            CreditCard::LASER,
            CreditCard::MAESTRO,
            CreditCard::MASTER_CARD,
            CreditCard::SOLO,
            CreditCard::SWITCH_TYPE,
            CreditCard::VISA,
            CreditCard::UNKNOWN
        ];
    }
}
