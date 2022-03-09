<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when the connection fails
 *
 * @package    Braintree
 * @subpackage Exception
 * @copyright  2015 Braintree, a division of PayPal, Inc.
 */
class Connection extends Exception
{

}
class_alias('Braintree\Exception\Connection', 'Braintree_Exception_Connection');