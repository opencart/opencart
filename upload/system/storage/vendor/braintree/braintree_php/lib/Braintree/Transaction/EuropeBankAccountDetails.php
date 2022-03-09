<?php
namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * Europe bank account details from a transaction
 * Creates an instance of europe bank account details as returned from a transaction
 *
 * @package    Braintree
 * @subpackage Transaction
 *
 * @property-read string $accountHolderName
 * @property-read string $bic
 * @property-read string $imageUrl
 * @property-read string $mandateAcceptedAt
 * @property-read string $mandateReferenceNumber
 * @property-read string $maskedIban
 * @property-read string $token
 */
class EuropeBankAccountDetails extends Instance
{
}
class_alias('Braintree\Transaction\EuropeBankAccountDetails', 'Braintree_Transaction_EuropeBankAccountDetails');
