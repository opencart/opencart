<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised from non-validating methods when gateway validations fail.
 *
 * @package    Braintree
 * @subpackage Exception
 */
class ValidationsFailed extends Exception
{

}
class_alias('Braintree\Exception\ValidationsFailed', 'Braintree_Exception_ValidationsFailed');
