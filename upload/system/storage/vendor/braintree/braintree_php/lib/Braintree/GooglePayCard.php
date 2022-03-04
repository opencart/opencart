<?php

namespace Braintree;

/**
 * Braintree GooglePayCard module
 * Creates and manages Braintree Google Pay cards
 *
 * <b>== More information ==</b>
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/google-pay-card developer docs} for information on attributes
 */
class GooglePayCard extends Base
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
     * Creates an instance of a GooglePayCard from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return GooglePayCard
     */
    public static function factory($attributes)
    {
        $defaultAttributes = [
            'expirationMonth'    => '',
            'expirationYear'    => '',
            'last4'  => $attributes['virtualCardLast4'],
            'cardType'  => $attributes['virtualCardType'],
        ];

        $instance = new self();
        $instance->_initialize(array_merge($defaultAttributes, $attributes));
        return $instance;
    }

    /**
     * sets instance properties from an array of values
     *
     * @param array $googlePayCardAttribs array of Google Pay card properties
     *
     * @return void
     */
    protected function _initialize($googlePayCardAttribs)
    {
        // set the attributes
        $this->_attributes = $googlePayCardAttribs;

        $subscriptionArray = [];
        if (isset($googlePayCardAttribs['subscriptions'])) {
            foreach ($googlePayCardAttribs['subscriptions'] as $subscription) {
                $subscriptionArray[] = Subscription::factory($subscription);
            }
        }

        $this->_set('subscriptions', $subscriptionArray);
    }
}
