<?php
namespace Braintree;

use Braintree\Instance;

/**
 * Line item associated with a transaction
 *
 * @package    Braintree
 */

/**
 * creates an instance of TransactionLineItem
 *
 *
 * @package    Braintree
 *
 * @property-read string $commodityCode
 * @property-read string $description
 * @property-read string $discountAmount
 * @property-read string $kind
 * @property-read string $name
 * @property-read string $productCode
 * @property-read string $quantity
 * @property-read string $taxAmount
 * @property-read string $totalAmount
 * @property-read string $unitAmount
 * @property-read string $unitOfMeasure
 * @property-read string $unitTaxAmount
 * @property-read string $url
 */
class TransactionLineItem extends Instance
{
    // LineItem Kinds
    const CREDIT = 'credit';
    const DEBIT = 'debit';

    protected $_attributes = [];

    /**
     * @ignore
     */
    public function __construct($attributes)
    {
        parent::__construct($attributes);
    }

    public static function findAll($transactionId)
    {
        return Configuration::gateway()->transactionLineItem()->findAll($transactionId);
    }
}
class_alias('Braintree\TransactionLineItem', 'Braintree_TransactionLineItem');
class_alias('Braintree\TransactionLineItem', 'Braintree\Transaction\LineItem');
class_alias('Braintree\TransactionLineItem', 'Braintree_Transaction_LineItem');
