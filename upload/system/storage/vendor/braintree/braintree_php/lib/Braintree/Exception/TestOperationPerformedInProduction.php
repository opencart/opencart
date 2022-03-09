<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
* Raised when a test method is used in production.
*
* @package Braintree
* @subpackage Exception
*/
class TestOperationPerformedInProduction extends Exception
{
}
class_alias('Braintree\Exception\TestOperationPerformedInProduction', 'Braintree_Exception_TestOperationPerformedInProduction');
