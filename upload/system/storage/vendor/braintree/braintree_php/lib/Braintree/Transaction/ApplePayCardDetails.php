<?php

namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * Apple Pay card details from a transaction
 */

/**
 * creates an instance of ApplePayCardDetails
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction#apple_pay_details developer docs} for information on attributes
 */
class ApplePayCardDetails extends Instance
{
    protected $_attributes = [];

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attributes)
    {
        parent::__construct($attributes);
    }
}
