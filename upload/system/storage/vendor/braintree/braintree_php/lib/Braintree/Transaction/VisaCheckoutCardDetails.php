<?php

namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * VisaCheckoutCard details from a transaction
 * creates an instance of VisaCheckoutCardDetails
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction#visa_checkout_card_details developer docs} for information on attributes
 */
class VisaCheckoutCardDetails extends Instance
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
