<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when the SSL CaFile is not found.
 *
 * @package    Braintree
 * @subpackage Exception
 */
class SSLCaFileNotFound extends Exception
{

}
class_alias('Braintree\Exception\SSLCaFileNotFound', 'Braintree_Exception_SSLCaFileNotFound');
