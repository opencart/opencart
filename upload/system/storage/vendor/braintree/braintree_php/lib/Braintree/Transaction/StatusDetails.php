<?php
namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * Status details from a transaction
 * Creates an instance of StatusDetails, as part of a transaction response
 *
 * @package    Braintree
 *
 * @property-read string    $amount
 * @property-read string    $status
 * @property-read \DateTime $timestamp
 * @property-read string    $transactionSource
 * @property-read string    $user
 */
class StatusDetails extends Instance
{
}
class_alias('Braintree\Transaction\StatusDetails', 'Braintree_Transaction_StatusDetails');
