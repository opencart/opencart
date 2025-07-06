<?php

namespace SourceSpan;

use League\Uri\Contracts\UriInterface;

/**
 * An interface that describes a segment of source text.
 */
interface SourceSpan
{
    /**
     * The start location of this span.
     */
    public function getStart(): SourceLocation;

    /**
     * The end location of this span, exclusive.
     */
    public function getEnd(): SourceLocation;

    /**
     * The source text for this span.
     */
    public function getText(): string;

    /**
     * The URL of the source (typically a file) of this span.
     *
     * This may be null, indicating that the source URL is unknown or
     * unavailable.
     */
    public function getSourceUrl(): ?UriInterface;

    /**
     * The length of this span, in bytes.
     */
    public function getLength(): int;

    /**
     * Creates a new span that's the union of $this and $other.
     *
     * The two spans must have the same source URL and may not be disjoint.
     * {@see getText} is computed by combining `$this->getText()` and `$other->getText()`.
     */
    public function union(SourceSpan $other): SourceSpan;

    /**
     * Compares two spans.
     *
     * It returns a negative integer if $this is ordered before $other,
     * a positive integer if $this is ordered after $other,
     * and zero if $this and $other are ordered together.
     *
     * $other must have the same source URL as `this`. This orders spans by
     * {@see getStart} then {@see getLength}.
     */
    public function compareTo(SourceSpan $other): int;

    /**
     * Formats $message in a human-friendly way associated with this span.
     *
     * @param string $message
     *
     * @return string
     */
    public function message(string $message): string;

    /**
     * Like {@see message}, but also highlights $secondarySpans to provide
     * the user with additional context.
     *
     * Each span takes a label ($label for this span, and the keys of the
     * $secondarySpans map for the secondary spans) that's used to indicate to
     * the user what that particular span represents.
     *
     * @throws \InvalidArgumentException if any secondary span has a different source URL than this span.
     *
     * @param array<string, SourceSpan> $secondarySpans
     */
    public function messageMultiple(string $message, string $label, array $secondarySpans): string;

    /**
     * Prints the text associated with this span in a user-friendly way.
     *
     * This is identical to {@see message}, except that it doesn't print the file
     * name, line number, column number, or message.
     */
    public function highlight(): string;

    /**
     * Like {@see highlight}, but also highlights $secondarySpans to provide
     * the user with additional context.
     *
     * Each span takes a label ($label for this span, and the keys of the
     * $secondarySpans map for the secondary spans) that's used to indicate to
     * the user what that particular span represents.
     *
     * @throws \InvalidArgumentException if any secondary span has a different source URL than this span.
     *
     * @param array<string, SourceSpan> $secondarySpans
     */
    public function highlightMultiple(string $label, array $secondarySpans): string;

    /**
     * Return a span from $start bytes (inclusive) to $end bytes
     * (exclusive) after the beginning of this span
     */
    public function subspan(int $start, ?int $end = null): SourceSpan;
}
