<?php

namespace Braintree\Exception;

use Braintree\Exception;

/**
* Raised when a test method is used in production.
*/
class TestOperationPerformedInProduction extends Exception
{
}
