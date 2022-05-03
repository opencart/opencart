<?php
namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * Customer details from a transaction
 * Creates an instance of customer details as returned from a transaction
 *
 * @package    Braintree
 * @subpackage Transaction
 *
 * @property-read string $billingPeriodStartDate
 * @property-read string $billingPeriodEndDate
 */
class SubscriptionDetails extends Instance
{
}
class_alias('Braintree\Transaction\SubscriptionDetails', 'Braintree_Transaction_SubscriptionDetails');
