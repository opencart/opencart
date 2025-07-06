<?php

namespace SourceSpan;

final class SimpleSourceSpanWithContext extends SourceSpanMixin implements SourceSpanWithContext
{
    public function __construct(
        private readonly SourceLocation $start,
        private readonly SourceLocation $end,
        private readonly string $text,
        private readonly string $context
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

        if (!str_contains($this->context, $this->text)) {
            throw new \InvalidArgumentException("The context line \"$context\" must contain \"$text\".");
        }

        if (Util::findLineStart($this->context, $this->text, $this->start->getColumn()) === null) {
            $column = $this->start->getColumn() + 1;
            throw new \InvalidArgumentException("The span text \"$text\" must start at column $column in a line within \"$context\".");
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

    public function getContext(): string
    {
        return $this->context;
    }

    public function subspan(int $start, ?int $end = null): SourceSpanWithContext
    {
        Util::checkValidRange($start, $end, $this->getLength());

        if ($start === 0 && ($end === null || $end === $this->getLength())) {
            return $this;
        }

        $locations = Util::subspanLocations($this, $start, $end);

        return new SimpleSourceSpanWithContext($locations[0], $locations[1], Util::substring($this->text, $start, $end), $this->context);
    }
}
