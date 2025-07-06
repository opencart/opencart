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

final class MultiSpanSassRuntimeException extends MultiSpanSassException implements SassRuntimeException
{
    private readonly Trace $sassTrace;

    /**
     * @param array<string, FileSpan> $secondarySpans
     */
    public function __construct(string $message, FileSpan $span, string $primaryLabel, array $secondarySpans, Trace $sassTrace, ?\Throwable $previous = null)
    {
        $this->sassTrace = $sassTrace;

        parent::__construct($message, $span, $primaryLabel, $secondarySpans, $previous);
    }

    public function getSassTrace(): Trace
    {
        return $this->sassTrace;
    }

    public function withAdditionalSpan(FileSpan $span, string $label, ?\Throwable $previous = null): MultiSpanSassRuntimeException
    {
        return new self($this->getOriginalMessage(), $this->getSpan(), $this->primaryLabel, $this->secondarySpans + [$label => $span], $this->sassTrace, $previous);
    }
}
