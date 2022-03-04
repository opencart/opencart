<?php

namespace Braintree;

/**
 * Braintree VisaCheckoutCard module
 * Creates and manages Braintree VisaCheckoutCards
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/visa-checkout-card developer docs} for information on attributes
 */
class VisaCheckoutCard extends Base
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
     * returns false if comparing object is not a VisaCheckoutCard,
     * or is a VisaCheckoutCard with a different id
     *
     * @param object $otherVisaCheckoutCard customer to compare against
     *
     * @return boolean
     */
    public function isEqual($otherVisaCheckoutCard)
    {
        return !($otherVisaCheckoutCard instanceof self) ? false : $this->token === $otherVisaCheckoutCard->token;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }

    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return VisaCheckoutCard
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
