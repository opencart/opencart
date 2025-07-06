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

namespace ScssPhp\ScssPhp\Parser;

use SourceSpan\FileSpan;

class MultiSourceFormatException extends FormatException
{
    /**
     * {@see MultiSpanSassException::$primaryLabel}
     */
    public readonly string $primaryLabel;
    /**
     * {@see MultiSpanSassException::$secondarySpans}
     *
     * @var array<string, FileSpan>
     */
    public readonly array $secondarySpans;

    /**
     * @param array<string, FileSpan> $secondarySpans
     */
    public function __construct(string $message, FileSpan $span, string $primaryLabel, array $secondarySpans, ?\Throwable $previous = null)
    {
        $this->primaryLabel = $primaryLabel;
        $this->secondarySpans = $secondarySpans;

        parent::__construct($message, $span, $previous);
    }
}
