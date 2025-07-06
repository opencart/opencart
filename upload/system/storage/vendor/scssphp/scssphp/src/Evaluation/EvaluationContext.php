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

namespace ScssPhp\ScssPhp\Evaluation;

use ScssPhp\ScssPhp\Deprecation;
use SourceSpan\FileSpan;

/**
 * @internal
 */
abstract class EvaluationContext
{
    private static ?EvaluationContext $evaluationContext = null;

    /**
     * The current evaluation context.
     *
     * @throws \LogicException if there isn't a Sass stylesheet currently being
     * evaluated.
     */
    public static function getCurrent(): EvaluationContext
    {
        if (self::$evaluationContext !== null) {
            return self::$evaluationContext;
        }

        throw new \LogicException('No Sass stylesheet is currently being evaluated.');
    }

    /**
     * Runs $callback with $context as {@see EvaluationContext::getCurrent()}.
     *
     * @template T
     *
     * @param callable(): T $callback
     *
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     */
    public static function withEvaluationContext(EvaluationContext $context, callable $callback)
    {
        $oldContext = self::$evaluationContext;
        self::$evaluationContext = $context;

        try {
            return $callback();
        } finally {
            self::$evaluationContext = $oldContext;
        }
    }

    /**
     * Returns the span for the currently executing callable.
     *
     * For normal exception reporting, this should be avoided in favor of
     * throwing {@see SassScriptException}s. It should only be used when calling APIs
     * that require spans.
     *
     * @throws \LogicException if there isn't a callable being invoked.
     */
    abstract public function getCurrentCallableSpan(): FileSpan;

    /**
     * Prints a warning message associated with the current `@import` or function
     * call.
     *
     * If $deprecation is non-null`, the warning is emitted as a deprecation
     * warning of that type.
     */
    abstract public function warn(string $message, ?Deprecation $deprecation = null): void;
}
