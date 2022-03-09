<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when the gateway is down for maintenance.
 *
 * @package    Braintree
 * @subpackage Exception
 */
class DownForMaintenance extends Exception
{

}
class_alias('Braintree\Exception\DownForMaintenance', 'Braintree_Exception_DownForMaintenance');
