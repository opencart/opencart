<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp\SourceSpan;

use League\Uri\Contracts\UriInterface;
use SourceSpan\FileLocation;
use SourceSpan\FileSpan;
use SourceSpan\SourceFile;
use SourceSpan\SourceSpan;

/**
 * A FileSpan wrapper that with secondary spans attached, so that
 * {@see MultiSpan::message} can forward to {@see SourceSpan::messageMultiple}.
 *
 * This is used to transparently support multi-span messages in situations that
 * need to be backwards-compatible with single spans, such as logger
 * invocations. To match the `source_span` package, separate APIs should
 * generally be preferred over this class wherever backwards compatibility
 * isn't a concern.
 *
 * @internal
 */
final class MultiSpan implements FileSpan
{
    /**
     * @param array<string, SourceSpan> $secondarySpans
     */
    public function __construct(
        private readonly FileSpan $primary,
        private readonly string $primaryLabel,
        private readonly array $secondarySpans,
    ) {
    }

    public function getStart(): FileLocation
    {
        return $this->primary->getStart();
    }

    public function getEnd(): FileLocation
    {
        return $this->primary->getEnd();
    }

    public function getText(): string
    {
        return $this->primary->getText();
    }

    public function getContext(): string
    {
        return $this->primary->getContext();
    }

    public function getFile(): SourceFile
    {
        return $this->primary->getFile();
    }

    public function getLength(): int
    {
        return $this->primary->getLength();
    }

    public function getSourceUrl(): ?UriInterface
    {
        return $this->primary->getSourceUrl();
    }

    public function compareTo(SourceSpan $other): int
    {
        return $this->primary->compareTo($other);
    }

    public function expand(FileSpan $other): FileSpan
    {
        return $this->withPrimary($this->primary->expand($other));
    }

    public function union(SourceSpan $other): SourceSpan
    {
        return $this->primary->union($other);
    }

    public function subspan(int $start, ?int $end = null): FileSpan
    {
        return $this->withPrimary($this->primary->subspan($start, $end));
    }

    public function highlight(): string
    {
        return $this->primary->highlightMultiple($this->primaryLabel, $this->secondarySpans);
    }

    public function message(string $message): string
    {
        return $this->primary->messageMultiple($message, $this->primaryLabel, $this->secondarySpans);
    }

    public function highlightMultiple(string $label, array $secondarySpans): string
    {
        return $this->primary->highlightMultiple($label, array_merge($this->secondarySpans, $secondarySpans));
    }

    public function messageMultiple(string $message, string $label, array $secondarySpans): string
    {
        return $this->primary->messageMultiple($message, $label, array_merge($this->secondarySpans, $secondarySpans));
    }

    /**
     * Returns a copy of $this with $newPrimary as its primary span.
     */
    private function withPrimary(FileSpan $newPrimary): MultiSpan
    {
        return new MultiSpan($newPrimary, $this->primaryLabel, $this->secondarySpans);
    }
}
