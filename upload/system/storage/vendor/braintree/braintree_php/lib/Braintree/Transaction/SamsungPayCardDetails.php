<?php

namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * SamsungPayCard details from a transaction
 * creates an instance of SamsungPayCardDetails
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction#samsung_pay_card_details developer docs} for information on attributes
 */
class SamsungPayCardDetails extends Instance
{
    protected $_attributes = [];

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attributes)
    {
        parent::__construct($attributes);
        $this->_attributes['expirationDate'] = $this->expirationMonth . '/' . $this->expirationYear;
        $this->_attributes['maskedNumber'] = $this->bin . '******' . $this->last4;
    }
}
