<?php

namespace SourceSpan;

use League\Uri\Contracts\UriInterface;

/**
 * The implementation of {@see FileSpan} based on a {@see SourceFile}.
 *
 * @see SourceFile::span()
 *
 * @internal
 */
final class ConcreteFileSpan extends SourceSpanMixin implements FileSpan
{
    /**
     * @param int $start The offset of the beginning of the span.
     * @param int $end   The offset of the end of the span.
     */
    public function __construct(
        private readonly SourceFile $file,
        private readonly int $start,
        private readonly int $end,
    ) {
        if ($this->end < $this->start) {
            throw new \InvalidArgumentException("End $this->end must come after start $this->start.");
        }

        if ($this->end > $this->file->getLength()) {
            throw new \OutOfRangeException("End $this->end not be greater than the number of characters in the file, {$this->file->getLength()}.");
        }

        if ($this->start < 0) {
            throw new \OutOfRangeException("Start may not be negative, was $this->start.");
        }
    }

    public function getFile(): SourceFile
    {
        return $this->file;
    }

    public function getSourceUrl(): ?UriInterface
    {
        return $this->file->getSourceUrl();
    }

    public function getLength(): int
    {
        return $this->end - $this->start;
    }

    public function getStart(): FileLocation
    {
        return new FileLocation($this->file, $this->start);
    }

    public function getEnd(): FileLocation
    {
        return new FileLocation($this->file, $this->end);
    }

    public function getText(): string
    {
        return $this->file->getText($this->start, $this->end);
    }

    public function getContext(): string
    {
        $endLine = $this->file->getLine($this->end);
        $endColumn = $this->file->getColumn($this->end);

        if ($endColumn === 0 && $endLine !== 0) {
            // If $this->end is at the very beginning of the line, the span covers the
            // previous newline, so we only want to include the previous line in the
            // context...

            if ($this->getLength() === 0) {
                // ...unless this is a point span, in which case we want to include the
                // next line (or the empty string if this is the end of the file).
                return $endLine === $this->file->getLines() - 1 ? '' : $this->file->getText($this->file->getOffset($endLine), $this->file->getOffset($endLine + 1));
            }

            $endOffset = $this->end;
        } elseif ($endLine === $this->file->getLines() - 1) {
            // If the span covers the last line of the file, the context should go all
            // the way to the end of the file.
            $endOffset = $this->file->getLength();
        } else {
            // Otherwise, the context should cover the full line on which [end]
            // appears.
            $endOffset = $this->file->getOffset($endLine + 1);
        }

        return $this->file->getText($this->file->getOffset($this->file->getLine($this->start)), $endOffset);
    }

    public function compareTo(SourceSpan $other): int
    {
        if (!$other instanceof ConcreteFileSpan) {
            return parent::compareTo($other);
        }

        $result = $this->start <=> $other->start;

        if ($result !== 0) {
            return $result;
        }

        return $this->end <=> $other->end;
    }

    public function union(SourceSpan $other): SourceSpan
    {
        if (!$other instanceof FileSpan) {
            return parent::union($other);
        }

        $span = $this->expand($other);

        if ($other instanceof ConcreteFileSpan) {
            if ($this->start > $other->end || $other->start > $this->end) {
                throw new \InvalidArgumentException("Spans are disjoint.");
            }
        } else {
            if ($this->start > $other->getEnd()->getOffset() || $other->getStart()->getOffset() > $this->end) {
                throw new \InvalidArgumentException("Spans are disjoint.");
            }
        }

        return $span;
    }

    public function expand(FileSpan $other): FileSpan
    {
        if ($this->file->getSourceUrl() !== $other->getFile()->getSourceUrl()) {
            throw new \InvalidArgumentException('Source map URLs don\'t match.');
        }

        $start = min($this->start, $other->getStart()->getOffset());
        $end = max($this->end, $other->getEnd()->getOffset());

        return new ConcreteFileSpan($this->file, $start, $end);
    }

    public function subspan(int $start, ?int $end = null): FileSpan
    {
        Util::checkValidRange($start, $end, $this->getLength());

        if ($start === 0 && ($end === null || $end === $this->getLength())) {
            return $this;
        }

        return $this->file->span($this->start + $start, $end === null ? $this->end : $this->start + $end);
    }
}
