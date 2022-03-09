<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when an error occurs that the client library is not built to handle.
 * This shouldn't happen.
 *
 * @package    Braintree
 * @subpackage Exception
 */
class Unexpected extends Exception
{

}
class_alias('Braintree\Exception\Unexpected', 'Braintree_Exception_Unexpected');
