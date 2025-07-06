<?php

namespace SourceSpan;

final class SimpleSourceSpan extends SourceSpanMixin
{
    public function __construct(
        private readonly SourceLocation $start,
        private readonly SourceLocation $end,
        private readonly string $text,
    ) {
        if (!Util::isSameUrl($start->getSourceUrl(), $end->getSourceUrl())) {
            throw new \InvalidArgumentException("Source URLs \"{$start->getSourceUrl()}\" and \"{$end->getSourceUrl()}\" don't match.");
        }

        if ($this->end->getOffset() < $this->start->getOffset()) {
            throw new \InvalidArgumentException('End must come after start.');
        }

        $distance = $this->start->distance($this->end);
        if (\strlen($this->text) !== $distance) {
            throw new \InvalidArgumentException("Text \"$text\" must be $distance characters long.");
        }
    }

    public function getStart(): SourceLocation
    {
        return $this->start;
    }

    public function getEnd(): SourceLocation
    {
        return $this->end;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function subspan(int $start, ?int $end = null): SourceSpan
    {
        Util::checkValidRange($start, $end, $this->getLength());

        if ($start === 0 && ($end === null || $end === $this->getLength())) {
            return $this;
        }

        $locations = Util::subspanLocations($this, $start, $end);

        return new SimpleSourceSpan($locations[0], $locations[1], Util::substring($this->text, $start, $end));
    }
}
