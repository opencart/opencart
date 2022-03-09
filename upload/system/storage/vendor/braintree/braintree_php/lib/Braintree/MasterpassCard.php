<?php
namespace Braintree;

/**
 * Braintree MasterpassCard module
 * Creates and manages Braintree MasterpassCards
 *
 * <b>== More information ==</b>
 *
 * For more detailed information on CreditCard verifications, see {@link https://developers.braintreepayments.com/reference/response/credit-card-verification/php https://developers.braintreepayments.com/reference/response/credit-card-verification/php}
 *
 * @package    Braintree
 * @category   Resources
 *
 * @property-read \Braintree\Address $billingAddress
 * @property-read string $bin
 * @property-read string $cardType
 * @property-read string $cardholderName
 * @property-read string $commercial
 * @property-read string $countryOfIssuance
 * @property-read \DateTime $createdAt
 * @property-read string $customerId
 * @property-read string $customerLocation
 * @property-read string $debit
 * @property-read boolean $default
 * @property-read string $durbinRegulated
 * @property-read string $expirationDate
 * @property-read string $expirationMonth
 * @property-read string $expirationYear
 * @property-read boolean $expired
 * @property-read string $healthcare
 * @property-read string $imageUrl
 * @property-read string $issuingBank
 * @property-read string $last4
 * @property-read string $maskedNumber
 * @property-read string $payroll
 * @property-read string $prepaid
 * @property-read string $productId
 * @property-read \Braintree\Subscription[] $subscriptions
 * @property-read string $token
 * @property-read string $uniqueNumberIdentifier
 * @property-read \DateTime $updatedAt
 */
class MasterpassCard extends Base
{
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
     * sets instance properties from an array of values
     *
     * @access protected
     * @param array $creditCardAttribs array of creditcard data
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
            foreach ($creditCardAttribs['subscriptions'] AS $subscription) {
                $subscriptionArray[] = Subscription::factory($subscription);
            }
        }

        $this->_set('subscriptions', $subscriptionArray);
        $this->_set('billingAddress', $billingAddress);
        $this->_set('expirationDate', $this->expirationMonth . '/' . $this->expirationYear);
        $this->_set('maskedNumber', $this->bin . '******' . $this->last4);
    }

    /**
     * returns false if comparing object is not a CreditCard,
     * or is a CreditCard with a different id
     *
     * @param object $otherCreditCard customer to compare against
     * @return boolean
     */
    public function isEqual($otherMasterpassCard)
    {
        return !($otherMasterpassCard instanceof self) ? false : $this->token === $otherMasterpassCard->token;
    }

    /**
     * create a printable representation of the object as:
     * ClassName[property=value, property=value]
     * @return string
     */
    public function  __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) .']';
    }

    /**
     *  factory method: returns an instance of CreditCard
     *  to the requesting method, with populated properties
     *
     * @ignore
     * @return MasterpassCard
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
}
class_alias('Braintree\MasterpassCard', 'Braintree_MasterpassCard');
