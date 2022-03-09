<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when the gateway request rate-limit is exceeded.
 *
 * @package    Braintree
 * @subpackage Exception
 */
class TooManyRequests extends Exception
{

}
class_alias('Braintree\Exception\TooManyRequests', 'Braintree_Exception_TooManyRequests');
