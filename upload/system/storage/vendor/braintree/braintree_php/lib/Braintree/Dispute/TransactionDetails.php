<?php
namespace Braintree\Dispute;

use Braintree\Instance;

/**
 * Transaction details for a dispute
 *
 * @package    Braintree
 */

/**
 * Creates an instance of DisbursementDetails as returned from a transaction
 *
 *
 * @package    Braintree
 *
 * @property-read string $amount
 * @property-read string $id
 */
class TransactionDetails extends Instance
{
}

class_alias('Braintree\Dispute\TransactionDetails', 'Braintree_Dispute_TransactionDetails');
