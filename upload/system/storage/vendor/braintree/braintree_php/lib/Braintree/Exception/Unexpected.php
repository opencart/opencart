<?php

namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when an error occurs that the client library is not built to handle.
 * This shouldn't happen.
 */
class Unexpected extends Exception
{
}
