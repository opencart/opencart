<?php
namespace Braintree;

/**
 * super class for all Braintree exceptions
 *
 * @package    Braintree
 * @subpackage Exception
 */
class Exception extends \Exception
{
}
class_alias('Braintree\Exception', 'Braintree_Exception');
