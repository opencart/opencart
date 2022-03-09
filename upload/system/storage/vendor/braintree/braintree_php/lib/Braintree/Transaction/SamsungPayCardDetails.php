<?php
namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * SamsungPayCard details from a transaction
 * creates an instance of SamsungPayCardDetails
 *
 * @package    Braintree
 * @subpackage Transaction
 *
 * @property-read string $bin
 * @property-read string $cardType
 * @property-read string $cardholderName
 * @property-read string $commercial
 * @property-read string $countryOfIssuance
 * @property-read string $customerId
 * @property-read string $customerLocation
 * @property-read string $debit
 * @property-read string $durbinRegulated
 * @property-read string $expirationDate
 * @property-read string $expirationMonth
 * @property-read string $expirationYear
 * @property-read string $healthcare
 * @property-read string $imageUrl
 * @property-read string $issuingBank
 * @property-read string $last4
 * @property-read string $maskedNumber
 * @property-read string $payroll
 * @property-read string $prepaid
 * @property-read string $productId
 * @property-read string $sourceCardLast4
 * @property-read string $token
 * @property-read string $updatedAt
 */
class SamsungPayCardDetails extends Instance
{
    protected $_attributes = [];

    /**
     * @ignore
     */
    public function __construct($attributes)
    {
        parent::__construct($attributes);
        $this->_attributes['expirationDate'] = $this->expirationMonth . '/' . $this->expirationYear;
        $this->_attributes['maskedNumber'] = $this->bin . '******' . $this->last4;

    }
}
class_alias('Braintree\Transaction\SamsungPayCardDetails', 'Braintree_Transaction_SamsungPayCardDetails');
