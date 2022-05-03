<?php
namespace Braintree\Transaction;

use Braintree\Instance;

/**
 * Coinbase details from a transaction
 *
 * @package    Braintree
 * @subpackage Transaction
 */

/**
 * creates an instance of Coinbase
 *
 *
 * @package    Braintree
 * @subpackage Transaction
 *
 * @property-read string $token
 * @property-read string $userId
 * @property-read string $userName
 * @property-read string $userEmail
 * @property-read string $imageUrl
 */
class CoinbaseDetails extends Instance
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
class_alias('Braintree\Transaction\CoinbaseDetails', 'Braintree_Transaction_CoinbaseDetails');
