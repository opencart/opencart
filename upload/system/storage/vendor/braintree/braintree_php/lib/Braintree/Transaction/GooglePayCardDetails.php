<?php

namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * Google Pay card details from a transaction
 */

/**
 * creates an instance of GooglePayCardDetails
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction#android_pay_details developer docs} for information on attributes
 */
class GooglePayCardDetails extends Instance
{
    protected $_attributes = [];

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attributes)
    {
        parent::__construct($attributes);
        $this->_attributes['cardType'] = $this->virtualCardType;
        $this->_attributes['last4'] = $this->virtualCardLast4;
    }
}
