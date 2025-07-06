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

namespace ScssPhp\ScssPhp;

use ScssPhp\ScssPhp\Evaluation\EvaluationContext;

final class Warn
{
    /**
     * Prints a warning message associated with the current `@import` or function call.
     *
     * This may only be called within a custom function or importer callback.
     */
    public static function warning(string $message): void
    {
        self::reportWarning($message, null);
    }

    /**
     * Prints a deprecation warning message associated with the current `@import` or function call.
     *
     * This may only be called within a custom function or importer callback.
     */
    public static function deprecation(string $message): void
    {
        self::reportWarning($message, Deprecation::userAuthored);
    }

    public static function forDeprecation(string $message, Deprecation $deprecation): void
    {
        self::reportWarning($message, $deprecation);
    }

    private static function reportWarning(string $message, ?Deprecation $deprecation): void
    {
        EvaluationContext::getCurrent()->warn($message, $deprecation);
    }
}
