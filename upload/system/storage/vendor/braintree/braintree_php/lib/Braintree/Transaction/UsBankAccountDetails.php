<?php
namespace Braintree\Transaction;

use Braintree\Instance;
use Braintree\AchMandate;

/**
 * US Bank Account details from a transaction
 * creates an instance of UsbankAccountDetails
 *
 * @package    Braintree
 * @subpackage Transaction
 *
 * @property-read string $token
 * @property-read string $imageUrl
 * @property-read string $routingNumber
 * @property-read string $accountType
 * @property-read string $accountHolderName
 * @property-read string $last4
 * @property-read string $bankName
 * @property-read string $achMandate
 */
class UsBankAccountDetails extends Instance
{
    protected $_attributes = [];

    /**
     * @ignore
     */
    public function __construct($attributes)
    {
        parent::__construct($attributes);

        $achMandate = isset($attributes['achMandate']) ?
            AchMandate::factory($attributes['achMandate']) :
            null;
        $this->achMandate = $achMandate;
    }
}
class_alias('Braintree\Transaction\UsBankAccountDetails', 'Braintree_Transaction_UsBankAccountDetails');
