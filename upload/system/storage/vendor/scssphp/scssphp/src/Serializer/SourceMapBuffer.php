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

namespace ScssPhp\ScssPhp\Serializer;

use ScssPhp\ScssPhp\SourceMap\SingleMapping;
use SourceSpan\FileSpan;

/**
 * @internal
 */
interface SourceMapBuffer extends StringBuffer
{
    /**
     * Runs $callback and associates all text written within it with $span.
     *
     * Specifically, this associates the point at the beginning of the written
     * text with {@see FileSpan::getStart()} and the point at the end of the
     * written text with {@see FileSpan::getEnd()}.
     *
     * @template T
     * @param callable(): T $callback
     * @return T
     */
    public function forSpan(FileSpan $span, callable $callback);

    /**
     * Returns the source map for the file being written.
     *
     * If $prefix is passed, all the entries in the source map will be moved
     * forward by the number of characters and lines in $prefix.
     */
    public function buildSourceMap(?string $prefix): SingleMapping;
}
