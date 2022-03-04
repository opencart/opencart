<?php

namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * Venmo account details from a transaction
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction#venmo_account_details developer docs} for information on attributes
 */
class VenmoAccountDetails extends Instance
{
    protected $_attributes = array();

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attributes)
    {
        parent::__construct($attributes);
    }
}
