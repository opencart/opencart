<?php

namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * Local payment details from a transaction
 */

/**
 * creates an instance of LocalPaymentDetails
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction developer docs} for information on attributes
 */
class LocalPaymentDetails extends Instance
{
    protected $_attributes = [];

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attributes)
    {
        parent::__construct($attributes);
    }
}
