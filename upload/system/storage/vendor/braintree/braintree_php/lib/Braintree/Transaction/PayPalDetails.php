<?php
namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * PayPal details from a transaction
 *
 * @package    Braintree
 * @subpackage Transaction
 */

/**
 * creates an instance of PayPalDetails
 *
 *
 * @package    Braintree
 * @subpackage Transaction
 *
 * @property-read string $authorizationId
 * @property-read string $captureId
 * @property-read string $customField
 * @property-read string $description
 * @property-read string $imageUrl
 * @property-read string $payerEmail
 * @property-read string $payerFirstName
 * @property-read string $payerId
 * @property-read string $payerLastName
 * @property-read string $payerStatus
 * @property-read string $paymentId
 * @property-read string $refundId
 * @property-read string $sellerProtectionStatus
 * @property-read string $taxId
 * @property-read string $taxIdType
 * @property-read string $token
 * @property-read string $transactionFeeAmount
 * @property-read string $transactionFeeCurrencyIsoCode
 */
class PayPalDetails extends Instance
{
    protected $_attributes = [];

    /**
     * @ignore
     */
    public function __construct($attributes)
    {
        parent::__construct($attributes);
    }
}
class_alias('Braintree\Transaction\PayPalDetails', 'Braintree_Transaction_PayPalDetails');
