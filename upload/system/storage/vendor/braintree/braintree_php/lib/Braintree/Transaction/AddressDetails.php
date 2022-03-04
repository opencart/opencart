<?php

namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * Creates an instance of AddressDetails as returned from a transaction
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction developer docs} for information on attributes
 */
class AddressDetails extends Instance
{
    protected $_attributes = [];
}
