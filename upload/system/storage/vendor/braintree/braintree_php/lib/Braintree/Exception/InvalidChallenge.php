<?php

namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when the webhook challenge you attempt to verify is in an invalid format.
 *
 * @link https://developer.paypal.com/braintree/docs/reference/general/exceptions/php#invalid-challenge
 */
class InvalidChallenge extends Exception
{
}
