<?php
namespace Braintree\Subscription;

use Braintree\Instance;

/**
 * Status details from a subscription
 * Creates an instance of StatusDetails, as part of a subscription response
 *
 * @package    Braintree
 *
 * @property-read string $price
 * @property-read string $currencyIsoCode
 * @property-read string $planId
 * @property-read string $balance
 * @property-read string $status
 * @property-read \DateTime $timestamp
 * @property-read string $subscriptionSource
 * @property-read string $user
 */
class StatusDetails extends Instance
{
}
class_alias('Braintree\Subscription\StatusDetails', 'Braintree_Subscription_StatusDetails');
