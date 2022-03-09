<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when a Timeout occurs
 *
 * @package    Braintree
 * @subpackage Exception
 */
class Timeout extends Exception
{

}
class_alias('Braintree\Exception\Timeout', 'Braintree_Exception_Timeout');
