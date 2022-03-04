<?php

namespace Braintree;

/**
 * Class for setting subscription search queries
 */
class SubscriptionSearch
{
    /*
     * Create a new range node for billing cycles remaining
     *
     * @return RangeNode
     */
    public static function billingCyclesRemaining()
    {
        return new RangeNode('billing_cycles_remaining');
    }

    /*
     * Create a new range node for days past due
     *
     * @return RangeNode
     */
    public static function daysPastDue()
    {
        return new RangeNode('days_past_due');
    }

    /*
     * Create a new text node for id
     *
     * @return TextNode
     */
    public static function id()
    {
        return new TextNode('id');
    }

    /*
     * Create a multiple value node for in trial period
     *
     * @return MultipleValueNode
     */
    public static function inTrialPeriod()
    {
        return new MultipleValueNode('in_trial_period', [true, false]);
    }

    /*
     * Create a multiple value node for merchant account id
     *
     * @return MultipleValueNode
     */
    public static function merchantAccountId()
    {
        return new MultipleValueNode('merchant_account_id');
    }

    /*
     * Create a new range node for next billing date
     *
     * @return RangeNode
     */
    public static function nextBillingDate()
    {
        return new RangeNode('next_billing_date');
    }

    /*
     * Create a multiple value node for plan id
     *
     * @return MultipleValueNode
     */
    public static function planId()
    {
        return new MultipleValueOrTextNode('plan_id');
    }

    /*
     * Create a new range node for price
     *
     * @return RangeNode
     */
    public static function price()
    {
        return new RangeNode('price');
    }

    /*
     * Create a multiple value node for status
     *
     * @return MultipleValueNode
     */
    public static function status()
    {
        return new MultipleValueNode('status', [
            Subscription::ACTIVE,
            Subscription::CANCELED,
            Subscription::EXPIRED,
            Subscription::PAST_DUE,
            Subscription::PENDING,
        ]);
    }

    /*
     * Create a new text node for transaction id
     *
     * @return TextNode
     */
    public static function transactionId()
    {
        return new TextNode('transaction_id');
    }

    /*
     * Create a multiple value node for ids
     *
     * @return MultipleValueNode
     */
    public static function ids()
    {
        return new MultipleValueNode('ids');
    }

    /*
     * Create a new range node for created at
     *
     * @return RangeNode
     */
    public static function createdAt()
    {
        return new RangeNode('created_at');
    }
}
