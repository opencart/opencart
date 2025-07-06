<?php

namespace SourceSpan;

use League\Uri\Contracts\UriInterface;

/**
 * The implementation of {@see SourceLocation} based on a {@see SourceFile}.
 *
 * @see SourceFile::location()
 */
final class FileLocation extends SourceLocationMixin
{
    /**
     * @internal
     */
    public function __construct(
        private readonly SourceFile $file,
        private readonly int $offset,
    ) {
    }

    public function getFile(): SourceFile
    {
        return $this->file;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLine(): int
    {
        return $this->file->getLine($this->offset);
    }

    public function getColumn(): int
    {
        return $this->file->getColumn($this->offset);
    }

    public function getSourceUrl(): ?UriInterface
    {
        return $this->file->getSourceUrl();
    }

    public function pointSpan(): FileSpan
    {
        return new ConcreteFileSpan($this->file, $this->offset, $this->offset);
    }
}
