<?php

namespace SourceSpan;

/**
 * An interface that describes a segment of source text with additional context.
 */
interface SourceSpanWithContext extends SourceSpan
{
    /**
     * Text around the span, which includes the line containing this span.
     */
    public function getContext(): string;

    public function subspan(int $start, ?int $end = null): SourceSpanWithContext;
}
