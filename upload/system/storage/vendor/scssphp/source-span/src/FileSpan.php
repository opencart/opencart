<?php

namespace SourceSpan;

interface FileSpan extends SourceSpanWithContext
{
    public function getFile(): SourceFile;

    public function getStart(): FileLocation;

    public function getEnd(): FileLocation;

    public function expand(FileSpan $other): FileSpan;

    /**
     * Return a span from $start bytes (inclusive) to $end bytes
     * (exclusive) after the beginning of this span
     */
    public function subspan(int $start, ?int $end = null): FileSpan;
}
