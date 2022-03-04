<?php

namespace Braintree;

/**
 * Braintree PayPalAccount module
 * Manages Braintree PayPalAccounts
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/paypal-account/php developer docs} for information on attributes
 */
class PayPalAccount extends Base
{
    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return PayPalAccount
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

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
     * sets instance properties from an array of values
     *
     * @param array $paypalAccountAttribs array of paypalAccount data
     *
     * @return void
     */
    protected function _initialize($paypalAccountAttribs)
    {
        // set the attributes
        $this->_attributes = $paypalAccountAttribs;

        $subscriptionArray = [];
        if (isset($paypalAccountAttribs['subscriptions'])) {
            foreach ($paypalAccountAttribs['subscriptions'] as $subscription) {
                $subscriptionArray[] = Subscription::factory($subscription);
            }
        }

        $this->_set('subscriptions', $subscriptionArray);
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        return __CLASS__ . '[' .
                Util::attributesToString($this->_attributes) . ']';
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $token paypal accountunique id
     *
     * @see PayPalGateway::find()
     *
     * @throws Exception\NotFound
     *
     * @return PayPalAccount
     */
    public static function find($token)
    {
        return Configuration::gateway()->payPalAccount()->find($token);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * if calling this method in context, $token
     * is the 2nd attribute. $token is not sent in object context.
     *
     * @param string $token      (optional)
     * @param array  $attributes including request parameters
     *
     * @see PayPalGateway::update()
     *
     * @return Result\Successful or Result\Error
     */
    public static function update($token, $attributes)
    {
        return Configuration::gateway()->payPalAccount()->update($token, $attributes);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $token paypal account identifier
     *
     * @see PayPalGateway::delete()
     *
     * @return Result
     */
    public static function delete($token)
    {
        return Configuration::gateway()->payPalAccount()->delete($token);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $token              paypal account identifier
     * @param array  $transactionAttribs containing request parameters
     *
     * @see PayPalGateway::sale()
     *
     * @return Result\Successful|Result\Error
     */
    public static function sale($token, $transactionAttribs)
    {
        return Configuration::gateway()->payPalAccount()->sale($token, $transactionAttribs);
    }
}
