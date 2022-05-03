<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when a suspected forged query string is present
 * Raised from methods that confirm transparent redirect requests
 * when the given query string cannot be verified. This may indicate
 * an attempted hack on the merchant's transparent redirect
 * confirmation URL.
 *
 * @package    Braintree
 * @subpackage Exception
 */
class ForgedQueryString extends Exception
{

}
class_alias('Braintree\Exception\ForgedQueryString', 'Braintree_Exception_ForgedQueryString');
