<?php

namespace Braintree;

/**
 * Braintree VenmoAccount module
 * Creates and manages Braintree Venmo accounts
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/venmo-account developer docs} for information on attributes
 */
class VenmoAccount extends Base
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
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return VenmoAccount
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
     * @param array $venmoAccountAttribs array of Venmo account properties
     *
     * @return void
     */
    protected function _initialize($venmoAccountAttribs)
    {
        $this->_attributes = $venmoAccountAttribs;

        $subscriptionArray = array();
        if (isset($venmoAccountAttribs['subscriptions'])) {
            foreach ($venmoAccountAttribs['subscriptions'] as $subscription) {
                $subscriptionArray[] = Subscription::factory($subscription);
            }
        }

        $this->_set('subscriptions', $subscriptionArray);
    }
}
