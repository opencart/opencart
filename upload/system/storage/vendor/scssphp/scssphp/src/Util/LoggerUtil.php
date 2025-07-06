<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp\Util;

use ScssPhp\ScssPhp\Deprecation;
use ScssPhp\ScssPhp\Logger\DeprecationProcessingLogger;
use ScssPhp\ScssPhp\Logger\LoggerInterface;
use ScssPhp\ScssPhp\StackTrace\Trace;
use SourceSpan\FileSpan;

/**
 * @internal
 */
final class LoggerUtil
{
    public static function warnForDeprecation(LoggerInterface $logger, Deprecation $deprecation, string $message, ?FileSpan $span = null, ?Trace $trace = null): void
    {
        if ($deprecation->isFuture() && !$logger instanceof DeprecationProcessingLogger) {
            return;
        }

        $logger->warn($message, $deprecation, $span, $trace);
    }
}
