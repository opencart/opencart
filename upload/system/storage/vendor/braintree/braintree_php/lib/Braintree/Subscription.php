<?php
namespace Braintree;

/**
 * Braintree Subscription module
 *
 * <b>== More information ==</b>
 *
 * For more detailed information on Subscriptions, see {@link https://developers.braintreepayments.com/reference/response/subscription/php https://developers.braintreepayments.com/reference/response/subscription/php}
 *
 * PHP Version 5
 *
 * @package   Braintree
 * 
 * @property-read \Braintree\Addon[] $addOns
 * @property-read string $balance
 * @property-read int $billingDayOfMonth
 * @property-read \DateTime $billingPeriodEndDate
 * @property-read \DateTime $billingPeriodStartDate
 * @property-read \DateTime $createdAt
 * @property-read int $currentBillingCycle
 * @property-read int|null $daysPastDue
 * @property-read string|null $description
 * @property-read \Braintree\Descriptor|null $descriptor
 * @property-read \Braintree\Discount[] $discounts
 * @property-read int $failureCount
 * @property-read \DateTime $firstBillingDate
 * @property-read string $id
 * @property-read string $merchantAccountId
 * @property-read boolean $neverExpires
 * @property-read string $nextBillingPeriodAmount
 * @property-read \DateTime $nextBillingDate
 * @property-read int|null $numberOfBillingCycles
 * @property-read \DateTime|null $paidThroughDate
 * @property-read string $paymentMethodToken
 * @property-read string $planId
 * @property-read string $price
 * @property-read string $status
 * @property-read \Braintree\Subscription\StatusDetails[] $statusHistory
 * @property-read \Braintree\Transaction[] $transactions
 * @property-read int $trialDuration
 * @property-read string $trialDurationUnit
 * @property-read boolean $trialPeriod
 * @property-read \DateTime $updatedAt
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
     * @ignore
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);

        return $instance;
    }

    /**
     * @ignore
     */
    protected function _initialize($attributes)
    {
        $this->_attributes = $attributes;

        $addOnArray = [];
        if (isset($attributes['addOns'])) {
            foreach ($attributes['addOns'] AS $addOn) {
                $addOnArray[] = AddOn::factory($addOn);
            }
        }
        $this->_attributes['addOns'] = $addOnArray;

        $discountArray = [];
        if (isset($attributes['discounts'])) {
            foreach ($attributes['discounts'] AS $discount) {
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
            foreach ($attributes['statusHistory'] AS $history) {
                $statusHistory[] = new Subscription\StatusDetails($history);
            }
        }
        $this->_attributes['statusHistory'] = $statusHistory;

        $transactionArray = [];
        if (isset($attributes['transactions'])) {
            foreach ($attributes['transactions'] AS $transaction) {
                $transactionArray[] = Transaction::factory($transaction);
            }
        }
        $this->_attributes['transactions'] = $transactionArray;
    }

    /**
     * returns a string representation of the customer
     * @return string
     */
    public function  __toString()
    {
        $excludedAttributes = ['statusHistory'];

        $displayAttributes = [];
        foreach($this->_attributes as $key => $val) {
            if (!in_array($key, $excludedAttributes)) {
                $displayAttributes[$key] = $val;
            }
        }

        return __CLASS__ . '[' .
                Util::attributesToString($displayAttributes) .']';
    }


    // static methods redirecting to gateway

    public static function create($attributes)
    {
        return Configuration::gateway()->subscription()->create($attributes);
    }

    public static function find($id)
    {
        return Configuration::gateway()->subscription()->find($id);
    }

    public static function search($query)
    {
        return Configuration::gateway()->subscription()->search($query);
    }

    public static function fetch($query, $ids)
    {
        return Configuration::gateway()->subscription()->fetch($query, $ids);
    }

    public static function update($subscriptionId, $attributes)
    {
        return Configuration::gateway()->subscription()->update($subscriptionId, $attributes);
    }

    public static function retryCharge($subscriptionId, $amount = null, $submitForSettlement = false)
    {
        return Configuration::gateway()->subscription()->retryCharge($subscriptionId, $amount, $submitForSettlement);
    }

    public static function cancel($subscriptionId)
    {
        return Configuration::gateway()->subscription()->cancel($subscriptionId);
    }
}
class_alias('Braintree\Subscription', 'Braintree_Subscription');
