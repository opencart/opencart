<?php
namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * iDEAL payment details from a transaction
 * creates an instance of IdealPaymentDetails
 *
 * @package    Braintree
 * @subpackage Transaction
 *
 * @property-read string $idealPaymentId
 * @property-read string $idealTransactionId
 * @property-read string $imageUrl
 * @property-read string $maskedIban
 * @property-read string $bic
 */
class IdealPaymentDetails extends Instance
{
    protected $_attributes = [];
}
class_alias('Braintree\Transaction\IdealPaymentDetails', 'Braintree_Transaction_IdealPaymentDetails');
