<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when a record could not be found.
 *
 * @package    Braintree
 * @subpackage Exception
 */
class NotFound extends Exception
{

}
class_alias('Braintree\Exception\NotFound', 'Braintree_Exception_NotFound');
