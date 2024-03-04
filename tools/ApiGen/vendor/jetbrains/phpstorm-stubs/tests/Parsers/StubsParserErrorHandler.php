<?php
declare(strict_types=1);

namespace StubTests\Parsers;

use PhpParser\Error;
use PhpParser\ErrorHandler;

class StubsParserErrorHandler implements ErrorHandler
{
    public function handleError(Error $error): void
    {
        $error->setRawMessage($error->getRawMessage() . "\n" . $error->getFile());
    }
}
