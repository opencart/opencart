<?php

namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * PayPal details from a transaction
 */

/**
 * creates an instance of PayPalDetails
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction#paypal_details developer docs} for information on attributes
 */
class PayPalDetails extends Instance
{
    protected $_attributes = [];

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attributes)
    {
        parent::__construct($attributes);
    }
}
