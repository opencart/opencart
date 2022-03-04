<?php

namespace Braintree;

/**
 * Braintree Subscription module
 *
 * // phpcs:ignore
 * For more detailed information on Subscriptions, see {@link https://developer.paypal.com/braintree/docs/reference/response/subscription our developer docs}
 */
class Subscription extends Base
{
    const ACTIVE = 'Active';
    const CANCELED = 'Canceled';
    const EXPIRED = 'Expired';
    const PAST_DUE = 'Past Due';
    const PENDING = 'Pending';

    // Subscription Sources
    const API           = 'api';
    const CONTROL_PANEL = 'control_panel';
    const RECURRING     = 'recurring';

    /**
     * Creates an instance from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return Subscription
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);

        return $instance;
    }

    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;

        $addOnArray = [];
        if (isset($attributes['addOns'])) {
            foreach ($attributes['addOns'] as $addOn) {
                $addOnArray[] = AddOn::factory($addOn);
            }
        }
        $this->_attributes['addOns'] = $addOnArray;

        $discountArray = [];
        if (isset($attributes['discounts'])) {
            foreach ($attributes['discounts'] as $discount) {
                $discountArray[] = Discount::factory($discount);
            }
        }
        $this->_attributes['discounts'] = $discountArray;

        if (isset($attributes['descriptor'])) {
            $this->_set('descriptor', new Descriptor($attributes['descriptor']));
        }

        if (isset($attributes['description'])) {
            $this->_set('description', $attributes['description']);
        }

        $statusHistory = [];
        if (isset($attributes['statusHistory'])) {
            foreach ($attributes['statusHistory'] as $history) {
                $statusHistory[] = new Subscription\StatusDetails($history);
            }
        }
        $this->_attributes['statusHistory'] = $statusHistory;

        $transactionArray = [];
        if (isset($attributes['transactions'])) {
            foreach ($attributes['transactions'] as $transaction) {
                $transactionArray[] = Transaction::factory($transaction);
            }
        }
        $this->_attributes['transactions'] = $transactionArray;
    }

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __toString()
    {
        $excludedAttributes = ['statusHistory'];

        $displayAttributes = [];
        foreach ($this->_attributes as $key => $val) {
            if (!in_array($key, $excludedAttributes)) {
                $displayAttributes[$key] = $val;
            }
        }

        return __CLASS__ . '[' .
                Util::attributesToString($displayAttributes) . ']';
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param array $attributes containing request params
     *
     * @see SubscriptionGateway::create()
     *
     * @return Result\Successful|Result\Error
     */
    public static function create($attributes)
    {
        return Configuration::gateway()->subscription()->create($attributes);
    }

    /*
     * Static methods redirecting to gateway class
     *
     * @param string $id of the subscription to find
     *
     * @see SubscriptionGateway::find()
     *
     * @return Subscription|Exception\NotFound
     */
    public static function find($id)
    {
        return Configuration::gateway()->subscription()->find($id);
    }

    /*
     * Static methods redirecting to gateway class
     *
     * @param mixed $query of search fields
     *
     * @see SubscriptionGateway::search()
     *
     * @return ResourceCollection of Subscription objects
     */
    public static function search($query)
    {
        return Configuration::gateway()->subscription()->search($query);
    }

    /*
     * Static methods redirecting to gateway class
     *
     * @param mixed $query of search fields
     * @param array $ids to be fetched
     *
     * @see SubscriptionGateway::fetch()
     *
     * @return ResourceCollection of Subscription objects
     */
    public static function fetch($query, $ids)
    {
        return Configuration::gateway()->subscription()->fetch($query, $ids);
    }

    /*
     * Static methods redirecting to gateway class
     *
     * @param string $subscriptionId the ID of the subscription to be updated
     * @param mixed $attributes
     *
     * @see SubscriptionGateway::update()
     *
     * @return Subscription|Exception\NotFound
     */
    public static function update($subscriptionId, $attributes)
    {
        return Configuration::gateway()->subscription()->update($subscriptionId, $attributes);
    }

    /*
     * Static methods redirecting to gateway class
     *
     * @param string $subscriptionId the ID of the subscription with a charge being retried
     * @param string $amount optional
     * @param bool $submitForSettlement defaults to false unless specified true
     *
     * @see SubscriptionGateway::retryCharge()
     *
     * @return Transaction
     */
    public static function retryCharge($subscriptionId, $amount = null, $submitForSettlement = false)
    {
        return Configuration::gateway()->subscription()->retryCharge($subscriptionId, $amount, $submitForSettlement);
    }

    /*
     * Static methods redirecting to gateway class
     *
     * @param string $subscriptionId to be canceled
     *
     * @see SubscriptionGateway::cancel()
     *
     * @return Subscription|Exception\NotFound
     */
    public static function cancel($subscriptionId)
    {
        return Configuration::gateway()->subscription()->cancel($subscriptionId);
    }
}
