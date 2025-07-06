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

use ScssPhp\ScssPhp\SourceMap\Builder\Entry;
use ScssPhp\ScssPhp\SourceMap\SingleMapping;
use ScssPhp\ScssPhp\Util\ListUtil;
use SourceSpan\FileLocation;
use SourceSpan\FileSpan;
use SourceSpan\SimpleSourceLocation;
use SourceSpan\SourceLocation;
use SourceSpan\SourceSpan;

/**
 * A {@see SourceMapBuffer} that builds a source map.
 *
 * @internal
 */
final class TrackingSourceMapBuffer implements SourceMapBuffer
{
    private readonly StringBuffer $buffer;
    /**
     * @var list<Entry>
     */
    private array $entries = [];
    /**
     * The index of the current line in {@see $buffer}.
     */
    private int $line = 0;
    /**
     * The index of the current column in {@see $buffer}.
     */
    private int $column = 0;
    /**
     * Whether the text currently being written should be encompassed by a
     * {@see SourceSpan}.
     */
    private bool $inSpan = false;

    public function __construct()
    {
        $this->buffer = new SimpleStringBuffer();
    }

    public function getLength(): int
    {
        return $this->buffer->getLength();
    }

    /**
     * The current location in {@see $buffer}.
     */
    private function getTargetLocation(): SourceLocation
    {
        return new SimpleSourceLocation($this->buffer->getLength(), line: $this->line, column: $this->column);
    }

    public function forSpan(FileSpan $span, callable $callback)
    {
        $wasInSpan = $this->inSpan;
        $this->inSpan = true;
        $this->addEntry($span->getStart(), $this->getTargetLocation());

        try {
            return $callback();
        } finally {
            // We could map $span->getEnd() to $this->getTargetLocation() here, but in practice
            // browsers don't care about where a span ends as long as it covers at
            // least the entity that they're looking up. Avoiding end mappings halves
            // the size of the source maps we generate.

            $this->inSpan = $wasInSpan;
        }
    }

    /**
     * Adds an entry to {@see $entries} unless it's redundant with the last entry.
     */
    private function addEntry(FileLocation $source, SourceLocation $target): void
    {
        if ($this->entries !== []) {
            $entry = ListUtil::last($this->entries);

            // Browsers don't care about the position of a value within a line, so
            // it's redundant to have two entries on the same target line that both
            // point to the same source line, even if they point to different
            // columns in that line.
            if ($entry->source->getLine() === $source->getLine() && $entry->target->getLine() === $target->getLine()) {
                return;
            }

            // Since source maps are only used to look up the source from the target
            // and not vice versa, we don't need multiple mappings to the same target.
            if ($entry->target->getOffset() === $target->getOffset()) {
                return;
            }
        }

        $this->entries[] = new Entry($source, $target);
    }

    public function write(string $string): void
    {
        $this->buffer->write($string);

        for ($i = 0; $i < \strlen($string); ++$i) {
            if ($string[$i] === "\n") {
                $this->writeLine();
            } else {
                $this->column++;
            }
        }
    }

    public function writeChar(string $char): void
    {
        $this->buffer->writeChar($char);

        if ($char === "\n") {
            $this->writeLine();
        } else {
            $this->column++;
        }
    }

    /**
     * Records that a line has been passed.
     *
     * If we're in the middle of a source span, indicate that at the beginning of
     * the new line. This is necessary because source maps consider each line
     * separately.
     */
    private function writeLine(): void
    {
        $lastEntry = ListUtil::last($this->entries);

        // Trim useless entries.
        if ($lastEntry->target->getLine() === $this->line && $lastEntry->target->getColumn() === $this->column) {
            array_pop($this->entries);
        }

        $this->line++;
        $this->column = 0;

        if ($this->inSpan) {
            $this->entries[] = new Entry($lastEntry->source, $this->getTargetLocation());
        }
    }

    public function __toString(): string
    {
        return (string) $this->buffer;
    }

    public function buildSourceMap(?string $prefix): SingleMapping
    {
        if ($prefix === null || $prefix === '') {
            return SingleMapping::fromEntries($this->entries);
        }

        $prefixLength = \strlen($prefix);
        $prefixLines = 0;
        $prefixColumn = 0;
        for ($i = 0; $i < \strlen($prefix); ++$i) {
            if ($prefix[$i] === "\n") {
                $prefixLines++;
                $prefixColumn = 0;
            } else {
                $prefixColumn++;
            }
        }

        return SingleMapping::fromEntries(array_map(fn (Entry $entry) => new Entry(
            $entry->source,
            new SimpleSourceLocation(
                $entry->target->getOffset() + $prefixLength,
                line: $entry->target->getLine() + $prefixLines,
                // Only adjust the column for entries that are on the same line as
                // the last chunk of the prefix.
                column: $entry->target->getColumn() + ($entry->target->getLine() === 0 ? $prefixColumn : 0)
            )
        ), $this->entries));
    }
}
