<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when authorization fails
 * Raised when the API key being used is not authorized to perform
 * the attempted action according to the roles assigned to the user
 * who owns the API key.
 *
 * @package    Braintree
 * @subpackage Exception
 */
class Authorization extends Exception
{

}
class_alias('Braintree\Exception\Authorization', 'Braintree_Exception_Authorization');
