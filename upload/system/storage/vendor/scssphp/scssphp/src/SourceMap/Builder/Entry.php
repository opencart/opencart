<?php

/**
 * SCSSPHP
 *
 * @copyright 2018-2020 Anthon Pang
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp\SourceMap\Builder;

use SourceSpan\SourceLocation;

/**
 * An entry in the source map builder.
 *
 * @internal
 */
final class Entry
{
    /**
     * Span denoting the original location in the input source file
     */
    public readonly SourceLocation $source;

    /**
     * Span indicating the corresponding location in the target file.
     */
    public readonly SourceLocation $target;

    public function __construct(SourceLocation $source, SourceLocation $target)
    {
        $this->source = $source;
        $this->target = $target;
    }

    /**
     * Implements comparison to ensure that entries are ordered by their
     * location in the target file. We sort primarily by the target offset
     * because source map files are encoded by printing each mapping in order as
     * they appear in the target file.
     */
    public function compareTo(Entry $other): int
    {
        $res = $this->target->compareTo($other->target);

        if ($res !== 0) {
            return $res;
        }

        $res = (string) $this->source->getSourceUrl() <=> (string) $other->source->getSourceUrl();

        if ($res !== 0) {
            return $res;
        }

        return $this->source->compareTo($other->source);
    }
}
