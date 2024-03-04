<?php

declare(strict_types=1);

namespace PHPSTORM_META;

expectedArguments(\Tracy\Debugger::log(), 1, \Tracy\ILogger::DEBUG, \Tracy\ILogger::INFO, \Tracy\ILogger::WARNING, \Tracy\ILogger::ERROR, \Tracy\ILogger::EXCEPTION, \Tracy\ILogger::CRITICAL);
expectedArguments(\Tracy\ILogger::log(), 1, \Tracy\ILogger::DEBUG, \Tracy\ILogger::INFO, \Tracy\ILogger::WARNING, \Tracy\ILogger::ERROR, \Tracy\ILogger::EXCEPTION, \Tracy\ILogger::CRITICAL);

exitPoint(\dumpe());
