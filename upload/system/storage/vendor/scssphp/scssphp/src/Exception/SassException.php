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

namespace ScssPhp\ScssPhp\Exception;

use ScssPhp\ScssPhp\StackTrace\Trace;
use SourceSpan\FileSpan;

interface SassException extends \Throwable
{
    /**
     * The span associated with this exception.
     */
    public function getSpan(): FileSpan;

    /**
     * Gets the original message without the location info in it.
     */
    public function getOriginalMessage(): string;

    /**
     * The Sass stack trace at the point this exception was thrown.
     *
     * This includes {@see getSpan}.
     */
    public function getSassTrace(): Trace;

    /**
     * Converts this to a {@see MultiSpanSassException} with the additional $span and
     * $label.
     *
     * @internal
     */
    public function withAdditionalSpan(FileSpan $span, string $label, ?\Throwable $previous = null): MultiSpanSassException;

    /**
     * Returns a copy of this as a {@see SassRuntimeException} with $trace as its
     * Sass stack trace.
     *
     * @internal
     */
    public function withTrace(Trace $trace, ?\Throwable $previous = null): SassRuntimeException;
}
