<?php

namespace Braintree\Transaction;

use Braintree\Instance;
use Braintree\AchMandate;

/**
 * US Bank Account details from a transaction
 * creates an instance of UsbankAccountDetails
 *
 * See our {@link https://developer.paypal.com/braintree/docs/guides/ach/server-side developer docs} for more information
 */
class UsBankAccountDetails extends Instance
{
    protected $_attributes = [];

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($attributes)
    {
        parent::__construct($attributes);

        $achMandate = isset($attributes['achMandate']) ?
            AchMandate::factory($attributes['achMandate']) :
            null;
        $this->achMandate = $achMandate;
    }
}
