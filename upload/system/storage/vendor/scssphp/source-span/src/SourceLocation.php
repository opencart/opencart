<?php

namespace SourceSpan;

use League\Uri\Contracts\UriInterface;

interface SourceLocation
{
    public function getOffset(): int;

    /**
     * The 0-based line of that location
     */
    public function getLine(): int;

    /**
     * The 0-based column of that location
     */
    public function getColumn(): int;

    public function getSourceUrl(): ?UriInterface;

    /**
     * Returns the distance in characters between $this and $other.
     *
     * This always returns a non-negative value.
     *
     * @return int<0, max>
     */
    public function distance(SourceLocation $other): int;

    /**
     * Returns a span that covers only a single point: this location.
     */
    public function pointSpan(): SourceSpan;

    /**
     * Compares two locations.
     *
     * It returns a negative integer if $this is ordered before $other,
     * a positive integer if $this is ordered after $other,
     * and zero if $this and $other are ordered together.
     *
     * $other must have the same source URL as $this.
     */
    public function compareTo(SourceLocation $other): int;
}
