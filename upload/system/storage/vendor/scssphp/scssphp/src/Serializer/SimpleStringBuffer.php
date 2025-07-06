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
 * A buffer that doesn't actually build a source map.
 *
 * We implement {@see SourceMapBuffer} directly on SimpleStringBuffer to avoid
 * an unnecessary wrapper for NoSourceMapBuffer (dart-sass has to make a wrapper
 * because StringBuffer comes from dart core).
 *
 * @internal
 */
final class SimpleStringBuffer implements SourceMapBuffer
{
    private string $text = '';

    public function getLength(): int
    {
        return \strlen($this->text);
    }

    public function write(string $string): void
    {
        $this->text .= $string;
    }

    public function writeChar(string $char): void
    {
        $this->text .= $char;
    }

    public function __toString(): string
    {
        return $this->text;
    }

    public function forSpan(FileSpan $span, callable $callback)
    {
        return $callback();
    }

    public function buildSourceMap(?string $prefix): SingleMapping
    {
        throw new \BadMethodCallException(__METHOD__ . ' is not supported.');
    }
}
