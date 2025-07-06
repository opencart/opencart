<?php

namespace SourceSpan\Highlighter;

use SourceSpan\SimpleSourceLocation;
use SourceSpan\SimpleSourceSpanWithContext;
use SourceSpan\SourceSpan;
use SourceSpan\SourceSpanWithContext;
use SourceSpan\Util;

/**
 * Information about how to highlight a single section of a source file.
 *
 * @internal
 */
final class Highlight
{
    /**
     * The section of the source file to highlight.
     *
     * This is normalized to make it easier for {@see Highlighter} to work with.
     */
    public readonly SourceSpanWithContext $span;

    /**
     * The label to include inline when highlighting {@see $span}.
     *
     * This helps distinguish clarify what each highlight means when multiple are
     * used in the same message.
     */
    public readonly ?string $label;

    public function __construct(
        SourceSpan $span,
        private readonly bool $primary = false,
        ?string $label = null,
    ) {
        $this->span = self::normalizeSpan($span);
        $this->label = $label === null ? null : str_replace("\r\n", "\n", $label);
    }

    /**
     * Whether this is the primary span in the highlight.
     *
     * The primary span is highlighted with a different character than
     * non-primary spans.
     */
    public function isPrimary(): bool
    {
        return $this->primary;
    }

    private static function normalizeSpan(SourceSpan $span): SourceSpanWithContext
    {
        $newSpan = self::normalizeContext($span);
        $newSpan = self::normalizeNewlines($newSpan);
        $newSpan = self::normalizeTrailingNewline($newSpan);

        return self::normalizeEndOfLine($newSpan);
    }

    /**
     * Normalizes $span to ensure that it's a {@see SourceSpanWithContext} whose
     * context actually contains its text at the expected column.
     *
     * If it's not already a {@see SourceSpanWithContext}, adjust the start and end
     * locations' line and column fields so that the highlighter can assume they
     * match up with the context.
     */
    private static function normalizeContext(SourceSpan $span): SourceSpanWithContext
    {
        if ($span instanceof SourceSpanWithContext && Util::findLineStart($span->getContext(), $span->getText(), $span->getStart()->getColumn()) !== null) {
            return $span;
        }

        return new SimpleSourceSpanWithContext(
            new SimpleSourceLocation($span->getStart()->getOffset(), $span->getSourceUrl(), 0, 0),
            new SimpleSourceLocation($span->getEnd()->getOffset(), $span->getSourceUrl(), substr_count($span->getText(), "\n"), self::lastLineLength($span->getText())),
            $span->getText(),
            $span->getText()
        );
    }

    /**
     * Normalizes $span to replace Windows-style newlines with Unix-style
     * newlines.
     */
    private static function normalizeNewlines(SourceSpanWithContext $span): SourceSpanWithContext
    {
        $text = $span->getText();
        if (!str_contains($text, "\r\n")) {
            return $span;
        }

        $endOffset = $span->getEnd()->getOffset() - substr_count($text, "\r\n");

        return new SimpleSourceSpanWithContext(
            $span->getStart(),
            new SimpleSourceLocation($endOffset, $span->getSourceUrl(), $span->getEnd()->getLine(), $span->getEnd()->getColumn()),
            str_replace("\r\n", "\n", $text),
            str_replace("\r\n", "\n", $span->getContext())
        );
    }

    /**
     * Normalizes $span to remove a trailing newline from `$span->getContext()`.
     *
     * If necessary, also adjust `$span->getEnd()` so that it doesn't point past where
     * the trailing newline used to be.
     */
    private static function normalizeTrailingNewline(SourceSpanWithContext $span): SourceSpanWithContext
    {
        if (!str_ends_with($span->getContext(), "\n")) {
            return $span;
        }

        // If there's a full blank line on the end of `$span->getContext()`, it's probably
        // significant, so we shouldn't trim it.
        if (str_ends_with($span->getText(), "\n\n")) {
            return $span;
        }

        $context = substr($span->getContext(), 0, -1);
        $text = $span->getText();
        $start = $span->getStart();
        $end = $span->getEnd();

        if (str_ends_with($text, "\n") && self::isTextAtEndOfContext($span)) {
            $text = substr($text, 0, -1);

            if ($text === '') {
                $end = $start;
            } else {
                $end = new SimpleSourceLocation(
                    $end->getOffset() - 1,
                    $span->getSourceUrl(),
                    $end->getLine() - 1,
                    self::lastLineLength($context)
                );
                $start = $span->getStart()->getOffset() === $span->getEnd()->getOffset() ? $end : $span->getStart();
            }
        }

        return new SimpleSourceSpanWithContext($start, $end, $text, $context);
    }

    /**
     * Normalizes $span so that the end location is at the end of a line rather
     * than at the beginning of the next line.
     */
    private static function normalizeEndOfLine(SourceSpanWithContext $span): SourceSpanWithContext
    {
        if ($span->getEnd()->getColumn() !== 0) {
            return $span;
        }

        if ($span->getEnd()->getLine() === $span->getStart()->getLine()) {
            return $span;
        }

        $text = substr($span->getText(), 0, -1);

        return new SimpleSourceSpanWithContext(
            $span->getStart(),
            new SimpleSourceLocation(
                $span->getEnd()->getOffset() - 1,
                $span->getSourceUrl(),
                $span->getEnd()->getLine() - 1,
                \strlen($text) - Util::lastIndexOf($text, "\n") - 1
            ),
            $text,
            // If the context also ends with a newline, it's possible that we don't
            // have the full context for that line, so we shouldn't print it at all.
            str_ends_with($span->getContext(), "\n") ? substr($span->getContext(), 0, -1) : $span->getContext()
        );
    }

    /**
     * Returns the length of the last line in $text, whether or not it ends in a
     * newline.
     */
    private static function lastLineLength(string $text): int
    {
        if ($text === '') {
            return 0;
        }

        if ($text[\strlen($text) - 1] === '\n') {
            return \strlen($text) === 1 ? 0 : \strlen($text) - Util::lastIndexOf($text, "\n", \strlen($text) - 2) - 1;
        }

        return \strlen($text) - Util::lastIndexOf($text, "\n") - 1;
    }

    /**
     * Returns whether $span's text runs all the way to the end of its context.
     */
    private static function isTextAtEndOfContext(SourceSpanWithContext $span): bool
    {
        $lineStart = Util::findLineStart($span->getContext(), $span->getText(), $span->getStart()->getColumn());
        \assert($lineStart !== null);

        return $lineStart + $span->getStart()->getColumn() + $span->getLength() === \strlen($span->getContext());
    }
}
