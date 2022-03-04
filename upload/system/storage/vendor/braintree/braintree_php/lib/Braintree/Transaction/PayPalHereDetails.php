<?php

namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * PayPal Here details from a transaction
 */

/**
 * creates and instance of PayPalHereDetails
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction#paypal_here_details developer docs} for information on attributes
 */
class PayPalHereDetails extends Instance
{
    protected $_attributes = [];

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attributes)
    {
        parent::__construct($attributes);
    }
}
