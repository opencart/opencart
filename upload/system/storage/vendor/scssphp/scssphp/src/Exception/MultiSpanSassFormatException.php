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

use SourceSpan\FileSpan;

final class MultiSpanSassFormatException extends MultiSpanSassException implements SassFormatException
{
    public function withAdditionalSpan(FileSpan $span, string $label, ?\Throwable $previous = null): MultiSpanSassFormatException
    {
        return new self($this->getOriginalMessage(), $this->getSpan(), $this->primaryLabel, $this->secondarySpans + [$label => $span], $previous);
    }
}
