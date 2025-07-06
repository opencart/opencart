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
 * A wrapper for {@see FileSpan} that allows an expensive creation process to be
 * deferred until the span is actually needed.
 *
 * @internal
 */
class LazyFileSpan implements FileSpan
{
    /**
     * @var \Closure(): FileSpan
     * @readonly
     */
    private readonly \Closure $builder;

    /**
     * @var FileSpan|null
     */
    private ?FileSpan $span = null;

    /**
     * @param \Closure(): FileSpan $builder
     */
    public function __construct(\Closure $builder)
    {
        $this->builder = $builder;
    }

    public function getSpan(): FileSpan
    {
        if ($this->span === null) {
            $this->span = ($this->builder)();
        }

        return $this->span;
    }

    public function getFile(): SourceFile
    {
        return $this->getSpan()->getFile();
    }

    public function getSourceUrl(): ?UriInterface
    {
        return $this->getSpan()->getSourceUrl();
    }

    public function getLength(): int
    {
        return $this->getSpan()->getLength();
    }

    public function getStart(): FileLocation
    {
        return $this->getSpan()->getStart();
    }

    public function getEnd(): FileLocation
    {
        return $this->getSpan()->getEnd();
    }

    public function getText(): string
    {
        return $this->getSpan()->getText();
    }

    public function union(SourceSpan $other): SourceSpan
    {
        return $this->getSpan()->union($other);
    }

    public function compareTo(SourceSpan $other): int
    {
        return $this->getSpan()->compareTo($other);
    }

    public function expand(FileSpan $other): FileSpan
    {
        return $this->getSpan()->expand($other);
    }

    public function message(string $message): string
    {
        return $this->getSpan()->message($message);
    }

    public function messageMultiple(string $message, string $label, array $secondarySpans): string
    {
        return $this->getSpan()->messageMultiple($message, $label, $secondarySpans);
    }

    public function highlight(): string
    {
        return $this->getSpan()->highlight();
    }

    public function highlightMultiple(string $label, array $secondarySpans): string
    {
        return $this->getSpan()->highlightMultiple($label, $secondarySpans);
    }

    public function subspan(int $start, ?int $end = null): FileSpan
    {
        return $this->getSpan()->subspan($start, $end);
    }

    public function getContext(): string
    {
        return $this->getSpan()->getContext();
    }
}
