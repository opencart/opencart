<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when an unexpected server error occurs.
 *
 * @package    Braintree
 * @subpackage Exception
 */
class ServerError extends Exception
{

}
class_alias('Braintree\Exception\ServerError', 'Braintree_Exception_ServerError');
