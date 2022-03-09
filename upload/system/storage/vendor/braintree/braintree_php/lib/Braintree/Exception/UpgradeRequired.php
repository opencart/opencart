<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when a client library must be upgraded.
 *
 * @package    Braintree
 * @subpackage Exception
 */
class UpgradeRequired extends Exception
{

}
class_alias('Braintree\Exception\UpgradeRequired', 'Braintree_Exception_UpgradeRequired');
