<?php
namespace Braintree;

/**
 * Disbursement details from a transaction
 * Creates an instance of DisbursementDetails as returned from a transaction
 *
 *
 * @package    Braintree
 *
 * @property-read string $disbursementDate
 * @property-read boolean $fundsHeld
 * @property-read string $settlementAmount
 * @property-read string $settlementCurrencyExchangeRate
 * @property-read string $settlementCurrencyIsoCode
 * @property-read string $success
 */
class DisbursementDetails extends Instance
{
    public function isValid() {
        return !is_null($this->disbursementDate);
    }
}
class_alias('Braintree\DisbursementDetails', 'Braintree_DisbursementDetails');
