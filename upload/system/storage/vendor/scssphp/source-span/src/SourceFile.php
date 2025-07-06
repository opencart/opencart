<?php

namespace SourceSpan;

use League\Uri\Contracts\UriInterface;

final class SourceFile
{
    private readonly string $string;

    private readonly ?UriInterface $sourceUrl;

    /**
     * @var list<int>
     */
    private readonly array $lineStarts;

    /**
     * The 0-based last line that was returned by {@see getLine}
     *
     * This optimizes computation for successive accesses to
     * the same line or to the next line.
     * It is stored as 0-based to correspond to the indices
     * in {@see $lineStarts}.
     *
     * @var int|null
     */
    private ?int $cachedLine = null;

    public static function fromString(string $content, ?UriInterface $sourceUrl = null): SourceFile
    {
        return new SourceFile($content, $sourceUrl);
    }

    private function __construct(string $content, ?UriInterface $sourceUrl = null)
    {
        $this->string = $content;
        $this->sourceUrl = $sourceUrl;

        // Extract line starts
        $lineStarts = [0];

        if ($content === '') {
            $this->lineStarts = $lineStarts;
            return;
        }

        $prev = 0;

        while (true) {
            $crPos = strpos($content, "\r", $prev);
            $lfPos = strpos($content, "\n", $prev);

            if ($crPos === false && $lfPos === false) {
                break;
            }

            if ($crPos !== false) {
                // Return not followed by newline is treated as a newline
                if ($lfPos === false || $lfPos > $crPos + 1) {
                    $lineStarts[] = $crPos + 1;
                    $prev = $crPos + 1;
                    continue;
                }
            }

            if ($lfPos !== false) {
                $lineStarts[] = $lfPos + 1;
                $prev = $lfPos + 1;
            }
        }

        $this->lineStarts = $lineStarts;
    }

    public function getLength(): int
    {
        return \strlen($this->string);
    }

    /**
     * The number of lines in the file.
     */
    public function getLines(): int
    {
        return \count($this->lineStarts);
    }

    public function span(int $start, ?int $end = null): FileSpan
    {
        if ($end === null) {
            $end = \strlen($this->string);
        }

        return new ConcreteFileSpan($this, $start, $end);
    }

    public function location(int $offset): FileLocation
    {
        if ($offset < 0) {
            throw new \OutOfRangeException("Offset may not be negative, was $offset.");
        }

        if ($offset > \strlen($this->string)) {
            $fileLength = \strlen($this->string);

            throw new \OutOfRangeException("Offset $offset must not be greater than the number of characters in the file, $fileLength.");
        }

        return new FileLocation($this, $offset);
    }

    public function getSourceUrl(): ?UriInterface
    {
        return $this->sourceUrl;
    }

    public function getString(): string
    {
        return $this->string;
    }

    /**
     * The 0-based line corresponding to that offset.
     */
    public function getLine(int $offset): int
    {
        if ($offset < 0) {
            throw new \OutOfRangeException('Position cannot be negative');
        }

        if ($offset > \strlen($this->string)) {
            throw new \OutOfRangeException('Position cannot be greater than the number of characters in the string.');
        }

        if ($offset < $this->lineStarts[0]) {
            return -1;
        }

        if ($offset >= Util::listLast($this->lineStarts)) {
            return \count($this->lineStarts) - 1;
        }

        if ($this->isNearCacheLine($offset)) {
            assert($this->cachedLine !== null);

            return $this->cachedLine;
        }

        $this->cachedLine = $this->binarySearch($offset) - 1;

        return $this->cachedLine;
    }

    /**
     * Returns `true` if $offset is near {@see $cachedLine}.
     *
     * Checks on {@see $cachedLine} and the next line. If it's on the next line, it
     * updates {@see $cachedLine} to point to that.
     */
    private function isNearCacheLine(int $offset): bool
    {
        if ($this->cachedLine === null) {
            return false;
        }

        if ($offset < $this->lineStarts[$this->cachedLine]) {
            return false;
        }

        if (
            $this->cachedLine >= \count($this->lineStarts) - 1 ||
            $offset < $this->lineStarts[$this->cachedLine + 1]
        ) {
            return true;
        }

        if (
            $this->cachedLine >= \count($this->lineStarts) - 2 ||
            $offset < $this->lineStarts[$this->cachedLine + 2]
        ) {
            ++$this->cachedLine;

            return true;
        }

        return false;
    }

    /**
     * Binary search through {@see $lineStarts} to find the line containing $offset.
     *
     * Returns the index of the line in {@see $lineStarts}.
     */
    private function binarySearch(int $offset): int
    {
        $min = 0;
        $max = \count($this->lineStarts) - 1;

        while ($min < $max) {
            $half = $min + intdiv($max - $min, 2);

            if ($this->lineStarts[$half] > $offset) {
                $max = $half;
            } else {
                $min = $half + 1;
            }
        }

        return $max;
    }

    /**
     * The 0-based column of that offset.
     */
    public function getColumn(int $offset): int
    {
        $line = $this->getLine($offset);

        return $offset - $this->lineStarts[$line];
    }

    /**
     * Gets the offset for a line and column.
     */
    public function getOffset(int $line, int $column = 0): int
    {
        if ($line < 0) {
            throw new \OutOfRangeException('Line may not be negative.');
        }

        if ($line >= \count($this->lineStarts)) {
            throw new \OutOfRangeException('Line must be less than the number of lines in the file.');
        }

        if ($column < 0) {
            throw new \OutOfRangeException('Column may not be negative.');
        }

        $result = $this->lineStarts[$line] + $column;

        if ($result > \strlen($this->string) || ($line + 1 < \count($this->lineStarts) && $result >= $this->lineStarts[$line + 1])) {
            throw new \OutOfRangeException("Line $line doesn't have $column columns.");
        }

        return $result;
    }

    /**
     * Returns the text of the file from $start to $end (exclusive).
     *
     * If $end isn't passed, it defaults to the end of the file.
     */
    public function getText(int $start, ?int $end = null): string
    {
        if ($end !== null) {
            if ($end < $start) {
                throw new \InvalidArgumentException("End $end must come after start $start.");
            }

            if ($end > $this->getLength()) {
                throw new \OutOfRangeException("End $end not be greater than the number of characters in the file, {$this->getLength()}.");
            }
        }

        if ($start < 0) {
            throw new \OutOfRangeException("Start may not be negative, was $start.");
        }

        return Util::substring($this->string, $start, $end);
    }
}
