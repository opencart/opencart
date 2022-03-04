<?php

namespace Braintree;

/**
 * Braintree ApplePayCard module
 * Creates and manages Braintree Apple Pay cards
 *
 * See our reference docs for a complete list of properties {@link https://developer.paypal.com/braintree/docs/reference/response/apple-pay-card/php}<br />
 */
class ApplePayCard extends Base
{
    // Card Type
    const AMEX = 'Apple Pay - American Express';
    const MASTER_CARD = 'Apple Pay - MasterCard';
    const VISA = 'Apple Pay - Visa';

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
     *  factory method: returns an instance of ApplePayCard
     *  to the requesting method, with populated properties
     *
     * @param mixed $attributes of the ApplePayCard object
     *
     * @return ApplePayCard
     */
    public static function factory($attributes)
    {
        $defaultAttributes = [
            'expirationMonth' => '',
            'expirationYear' => '',
            'last4'  => '',
        ];

        $instance = new self();
        $instance->_initialize(array_merge($defaultAttributes, $attributes));
        return $instance;
    }

    /**
     * sets instance properties from an array of values
     *
     * @param array $applePayCardAttribs array of Apple Pay card properties
     *
     * @return void
     */
    protected function _initialize($applePayCardAttribs)
    {
        // set the attributes
        $this->_attributes = $applePayCardAttribs;

        $subscriptionArray = [];
        if (isset($applePayCardAttribs['subscriptions'])) {
            foreach ($applePayCardAttribs['subscriptions'] as $subscription) {
                $subscriptionArray[] = Subscription::factory($subscription);
            }
        }

        $this->_set('subscriptions', $subscriptionArray);
        $this->_set('expirationDate', $this->expirationMonth . '/' . $this->expirationYear);
    }
}
