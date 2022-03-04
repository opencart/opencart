<?php

namespace Braintree;

/**
 * Disbursement details from a transaction
 *
 * Contains information about how and when the transaction was disbursed, including timing and currency information. This detail is only available if you have an eligible merchant account.
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/transaction/php#disbursement_details developer docs} for information on attributes
 */
class DisbursementDetails extends Instance
{
    /**
     * Checks whether a Disbursement date is valid
     *
     * @return bool
     */
    public function isValid()
    {
        return !is_null($this->disbursementDate);
    }
}
