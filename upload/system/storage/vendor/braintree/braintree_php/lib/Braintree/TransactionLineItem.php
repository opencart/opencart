<?php

namespace Braintree;

use Braintree\Instance;

/**
 * Line item associated with a transaction
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction-line-item developer docs} for information on attributes
 */
class TransactionLineItem extends Instance
{
    // TransactionLineItem Kinds
    const CREDIT = 'credit';
    const DEBIT = 'debit';

    protected $_attributes = [];

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attributes)
    {
        parent::__construct($attributes);
    }

    /**
     * Static methods redirecting to gateway class
     *
     * @param string $transactionId of the Transaction to search for line items
     *
     * @see TransactionLineItemGateway::findAll()
     *
     * @return TransactionLineItem|Exception
     */
    public static function findAll($transactionId)
    {
        return Configuration::gateway()->transactionLineItem()->findAll($transactionId);
    }
}
