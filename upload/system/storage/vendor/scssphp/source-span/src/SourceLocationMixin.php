<?php

namespace SourceSpan;

/**
 * A mixin for easily implementing {@see SourceLocation}.
 *
 * @internal
 */
abstract class SourceLocationMixin implements SourceLocation
{
    public function distance(SourceLocation $other): int
    {
        if (!Util::isSameUrl($this->getSourceUrl(), $other->getSourceUrl())) {
            throw new \InvalidArgumentException("Source URLs \"{$this->getSourceUrl()}\" and \"{$other->getSourceUrl()}\" don't match.");
        }

        return abs($this->getOffset() - $other->getOffset());
    }

    public function pointSpan(): SourceSpan
    {
        return new SimpleSourceSpan($this, $this, '');
    }

    public function compareTo(SourceLocation $other): int
    {
        if (!Util::isSameUrl($this->getSourceUrl(), $other->getSourceUrl())) {
            throw new \InvalidArgumentException("Source URLs \"{$this->getSourceUrl()}\" and \"{$other->getSourceUrl()}\" don't match.");
        }

        return $this->getOffset() - $other->getOffset();
    }
}
