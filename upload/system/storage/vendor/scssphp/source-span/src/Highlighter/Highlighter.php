<?php

namespace SourceSpan\Highlighter;

use League\Uri\Contracts\UriInterface;
use SourceSpan\SourceSpan;
use SourceSpan\Util;

/**
 * A class for writing a chunk of text with a particular span highlighted.
 *
 * @internal
 */
final class Highlighter
{
    /**
     * The number of spaces to render for hard tabs that appear in `_span.text`.
     *
     * We don't want to render raw tabs, because they'll mess up our character
     * alignment.
     */
    private const SPACES_PER_TAB = 4;

    /**
     * The lines to display, including context around the highlighted spans.
     *
     * @var list<Line>
     */
    private array $lines;

    /**
     * The number of characters before the bar in the sidebar.
     */
    private readonly int $paddingBeforeSidebar;

    /**
     * The maximum number of multiline spans that cover any part of a single
     * line in {@see $lines}.
     */
    private readonly int $maxMultilineSpans;

    /**
     * Whether {@see $lines} includes lines from multiple different files.
     */
    private readonly bool $multipleFiles;

    /**
     * The buffer to which to write the result.
     */
    private string $buffer = '';

    /**
     * Creates a {@see Highlighter} that will return a string highlighting $span
     * within the text of its file when {@see highlight} is called.
     */
    public static function create(SourceSpan $span): Highlighter
    {
        return new Highlighter(self::collateLines([new Highlight($span, primary: true)]));
    }

    /**
     * Creates a {@see Highlighter} that will return a string highlighting
     * $primarySpan as well as all the spans in $secondarySpans within the text
     * of their file when {@see highlight} is called.
     *
     * Each span has an associated label that will be written alongside it. For
     * $primarySpan this message is $primaryLabel, and for $secondarySpans the
     * labels are the map keys.
     *
     * @param array<string, SourceSpan> $secondarySpans
     */
    public static function multiple(SourceSpan $primarySpan, string $primaryLabel, array $secondarySpans): Highlighter
    {
        $highlights = [new Highlight($primarySpan, primary: true, label: $primaryLabel)];
        foreach ($secondarySpans as $secondaryLabel => $secondarySpan) {
            $highlights[] = new Highlight($secondarySpan, label: $secondaryLabel);
        }

        return new Highlighter(self::collateLines($highlights));
    }

    /**
     * @param list<Line> $lines
     */
    private function __construct(array $lines)
    {
        $this->lines = $lines;
        $this->paddingBeforeSidebar = 1 + max(
            \strlen((string) (Util::listLast($lines)->number + 1)),
            // If $lines aren't contiguous, we'll write "..." in place of a
            // line number.
            self::contiguous($lines) ? 0 : 3
        );
        $this->maxMultilineSpans = array_reduce(array_map(fn (Line $line) => \count(array_filter($line->highlights, fn (Highlight $highlight) => Util::isMultiline($highlight->span))), $lines), 'max', 0);
        $this->multipleFiles = !Util::isAllTheSame(array_map(fn (Line $line) => $line->url, $lines));
    }

    /**
     * Returns whether $lines contains any adjacent lines from the same source
     * file that aren't adjacent in the original file.
     *
     * @param list<Line> $lines
     */
    private static function contiguous(array $lines): bool
    {
        for ($i = 0; $i < \count($lines) - 1; $i++) {
            $thisLine = $lines[$i];
            $nextLine = $lines[$i + 1];

            if ($thisLine->number + 1 !== $nextLine->number && Util::isSame($thisLine->url, $nextLine->url)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Collect all the source lines from the contexts of all spans in
     * $highlights, and associates them with the highlights that cover them.
     *
     * @param list<Highlight> $highlights
     * @return list<Line>
     */
    private static function collateLines(array $highlights): array
    {
        // Assign spans without URLs opaque strings as keys. Each such string will
        // be different, but they can then be used later on to determine which lines
        // came from the same span even if they'd all otherwise have `null` URLs.
        $highlightsByUrl = [];
        $urls = [];
        foreach ($highlights as $highlight) {
            $url = $highlight->span->getSourceUrl() ?? new \stdClass();
            $key = $url instanceof UriInterface ? $url->toString() : spl_object_hash($url);
            $highlightsByUrl[$key][] = $highlight;
            $urls[$key] = $url;
        }

        foreach ($highlightsByUrl as &$list) {
            usort($list, fn (Highlight $highlight1, Highlight $highlight2) => $highlight1->span->compareTo($highlight2->span));
        }

        return iterator_to_array(self::expandMapIterable($highlightsByUrl, function (array $highlightsForFile, string $urlKey) use ($urls) {
            // First, create a list of all the lines in the current file that we have
            // context for along with their line numbers.
            $lines = [];

            /** @var Highlight $highlight */
            foreach ($highlightsForFile as $highlight) {
                $context = $highlight->span->getContext();
                // If `$highlight->span->getContext()` contains lines prior to the one
                // `$highlight->span->getText()` appears on, write those first.
                $lineStart = Util::findLineStart($context, $highlight->span->getText(), $highlight->span->getStart()->getColumn());
                \assert($lineStart !== null);
                $linesBeforeSpan = substr_count(substr($context, 0, $lineStart), "\n");

                $lineNumber = $highlight->span->getStart()->getLine() - $linesBeforeSpan;

                foreach (explode("\n", $context) as $line) {
                    // Only add a line if it hasn't already been added for a previous span
                    if ($lines === [] || $lineNumber > Util::listLast($lines)->number) {
                        $lines[] = new Line($line, $lineNumber, $urls[$urlKey]);
                    }
                    $lineNumber++;
                }
            }

            // Next, associate each line with each highlight that covers it.
            $activeHighlights = [];
            $highlightIndex = 0;

            foreach ($lines as $line) {
                $activeHighlights = array_values(array_filter($activeHighlights, fn (Highlight $highlight) => $highlight->span->getEnd()->getLine() >= $line->number));

                $oldHighlightLength = \count($activeHighlights);

                foreach (array_slice($highlightsForFile, $highlightIndex) as $highlight) {
                    if ($highlight->span->getStart()->getLine() > $line->number) {
                        break;
                    }
                    $activeHighlights[] = $highlight;
                }

                $highlightIndex += \count($activeHighlights) - $oldHighlightLength;

                foreach ($activeHighlights as $activeHighlight) {
                    $line->highlights[] = $activeHighlight;
                }
            }

            return $lines;
        }), false);
    }

    /**
     * Returns the highlighted span text.
     *
     * This method should only be called once.
     */
    public function highlight(): string
    {
        $this->writeFileStart($this->lines[0]->url);

        // Each index of this list represents a column after the sidebar that could
        // contain a line indicating an active highlight. If it's `null`, that
        // column is empty; if it contains a highlight, it should be drawn for that
        // column.
        $highlightsByColumn = array_fill(0, $this->maxMultilineSpans, null);

        foreach ($this->lines as $i => $line) {
            if ($i > 0) {
                $lastLine = $this->lines[$i - 1];

                if (!Util::isSame($lastLine->url, $line->url)) {
                    $this->writeSidebar(end: AsciiGlyph::upEnd);
                    $this->buffer .= "\n";
                    $this->writeFileStart($line->url);
                } elseif ($lastLine->number + 1 !== $line->number) {
                    $this->writeSidebar(text: '...');
                    $this->buffer .= "\n";
                }
            }

            // If a highlight covers the entire first line other than initial
            // whitespace, don't bother pointing out exactly where it begins. Iterate
            // in reverse so that longer highlights (which are sorted after shorter
            // highlights) appear further out, leading to fewer crossed lines.
            foreach (array_reverse($line->highlights) as $highlight) {
                if (Util::isMultiline($highlight->span) && $highlight->span->getStart()->getLine() === $line->number && $this->isOnlyWhitespace(substr($line->text, 0, $highlight->span->getStart()->getColumn()))) {
                    Util::replaceFirstNull($highlightsByColumn, $highlight);
                }
            }

            $this->writeSidebar(line: $line->number);
            $this->buffer .= ' ';
            $this->writeMultilineHighlights($line, $highlightsByColumn);

            if ($highlightsByColumn !== []) {
                $this->buffer .= ' ';
            }
            $primaryIdx = Util::indexWhere($line->highlights, fn (Highlight $highlight) => $highlight->isPrimary());
            $primary = $primaryIdx === null ? null : $line->highlights[$primaryIdx];

            $this->writeText($line->text);
            $this->buffer .= "\n";

            // Always write the primary span's indicator first so that it's right next
            // to the highlighted text.
            if ($primary !== null) {
                $this->writeIndicator($line, $primary, $highlightsByColumn);
            }

            foreach ($line->highlights as $highlight) {
                if ($highlight->isPrimary()) {
                    continue;
                }
                $this->writeIndicator($line, $highlight, $highlightsByColumn);
            }
        }

        $this->writeSidebar(end: AsciiGlyph::upEnd);

        return $this->buffer;
    }

    /**
     * Writes the beginning of the file highlight for the file with the given
     * $url (or opaque object if it comes from a span with a null URL).
     */
    private function writeFileStart(object $url): void
    {
        if (!$this->multipleFiles || !$url instanceof UriInterface) {
            $this->writeSidebar(end: AsciiGlyph::downEnd);
        } else {
            $this->writeSidebar(end: AsciiGlyph::topLeftCorner);
            $this->buffer .= str_repeat(AsciiGlyph::horizontalLine, 2) . '> ';
            $this->buffer .= Util::prettyUri($url);
        }

        $this->buffer .= "\n";
    }

    /**
     * Writes the post-sidebar highlight bars for $line according to
     * $highlightsByColumn.
     *
     * If $current is passed, it's the highlight for which an indicator is being
     * written. If it appears in $highlightsByColumn, a horizontal line is
     * written from its column to the rightmost column.
     *
     * @param list<Highlight|null> $highlightsByColumn
     */
    private function writeMultilineHighlights(Line $line, array $highlightsByColumn, ?Highlight $current = null): void
    {
        // Whether we've written a sidebar indicator for opening a new span on this
        // line.
        $openedOnThisLine = false;
        $foundCurrent = false;

        foreach ($highlightsByColumn as $highlight) {
            $startLine = $highlight?->span->getStart()->getLine();
            $endLine = $highlight?->span->getEnd()->getLine();

            if ($current !== null && $highlight === $current) {
                $foundCurrent = true;
                \assert($startLine === $line->number || $endLine === $line->number);
                $this->buffer .= $startLine === $line->number ? AsciiGlyph::topLeftCorner : AsciiGlyph::bottomLeftCorner;
            } elseif ($foundCurrent) {
                $this->buffer .= $highlight === null ? AsciiGlyph::horizontalLine : AsciiGlyph::cross;
            } elseif ($highlight === null) {
                if ($openedOnThisLine) {
                    $this->buffer .= AsciiGlyph::horizontalLine;
                } else {
                    $this->buffer .= ' ';
                }
            } else {
                $vertical = $openedOnThisLine ? AsciiGlyph::cross : AsciiGlyph::verticalLine;

                if ($current !== null) {
                    $this->buffer .= $vertical;
                } elseif ($startLine === $line->number) {
                    $this->buffer .= '/';
                    $openedOnThisLine = true;
                } elseif ($endLine === $line->number && $highlight->span->getEnd()->getColumn() === \strlen($line->text)) {
                    $this->buffer .= $highlight->label === null ? '\\' : $vertical;
                } else {
                    $this->buffer .= $vertical;
                }
            }
        }
    }

    /**
     * Writes an indicator for where $highlight starts, ends, or both below
     * $line.
     *
     * This may either add or remove $highlight from $highlightsByColumn.
     *
     * @param list<Highlight|null> $highlightsByColumn
     */
    private function writeIndicator(Line $line, Highlight $highlight, array &$highlightsByColumn): void
    {
        if (!Util::isMultiline($highlight->span)) {
            $this->writeSidebar();
            $this->buffer .= ' ';
            $this->writeMultilineHighlights($line, $highlightsByColumn, $highlight);

            if ($highlightsByColumn !== []) {
                $this->buffer .= ' ';
            }

            $start = \strlen($this->buffer);
            $this->writeUnderline($line, $highlight->span, $highlight->isPrimary() ? '^' : AsciiGlyph::horizontalLineBold);
            $underlineLength = \strlen($this->buffer) - $start;
            $this->writeLabel($highlight, $highlightsByColumn, $underlineLength);
        } elseif ($highlight->span->getStart()->getLine() === $line->number) {
            if (\in_array($highlight, $highlightsByColumn, true)) {
                return;
            }

            Util::replaceFirstNull($highlightsByColumn, $highlight);

            $this->writeSidebar();
            $this->buffer .= ' ';
            $this->writeMultilineHighlights($line, $highlightsByColumn, $highlight);
            $this->writeArrow($line, $highlight->span->getStart()->getColumn());
            $this->buffer .= "\n";
        } elseif ($highlight->span->getEnd()->getLine() === $line->number) {
            $coversWholeLine = $highlight->span->getEnd()->getColumn() === \strlen($line->text);
            if ($coversWholeLine && $highlight->label === null) {
                Util::replaceWithNull($highlightsByColumn, $highlight);
                return;
            }

            $this->writeSidebar();
            $this->buffer .= ' ';
            $this->writeMultilineHighlights($line, $highlightsByColumn, $highlight);

            $start = \strlen($this->buffer);
            if ($coversWholeLine) {
                $this->buffer .= str_repeat(AsciiGlyph::horizontalLine, 3);
            } else {
                $this->writeArrow($line, max($highlight->span->getEnd()->getColumn() - 1, 0), false);
            }
            $underlineLength = \strlen($this->buffer) - $start;
            $this->writeLabel($highlight, $highlightsByColumn, $underlineLength);
            Util::replaceWithNull($highlightsByColumn, $highlight);
        }
    }

    /**
     * Underlines the portion of $line covered by $span with repeated instances
     * of $character.
     */
    private function writeUnderline(Line $line, SourceSpan $span, string $character): void
    {
        \assert(!Util::isMultiline($span));
        \assert(str_contains($line->text, $span->getText()));

        $startColumn = $span->getStart()->getColumn();
        $endColumn = $span->getEnd()->getColumn();

        // Adjust the start and end columns to account for any tabs that were
        // converted to spaces.
        $tabsBefore = substr_count(substr($line->text, 0, $startColumn), "\t");
        $tabsInside = substr_count(Util::substring($line->text, $startColumn, $endColumn), "\t");

        $startColumn += $tabsBefore * (self::SPACES_PER_TAB - 1);
        $endColumn += ($tabsBefore + $tabsInside) * (self::SPACES_PER_TAB - 1);

        $this->buffer .= str_repeat(' ', $startColumn);
        $this->buffer .= str_repeat($character, max($endColumn - $startColumn, 1));
    }

    /**
     * Write an arrow pointing to column $column in $line.
     *
     * If the arrow points to a tab character, this will point to the beginning
     * of the tab if $beginning is `true` and the end if it's `false`.
     */
    private function writeArrow(Line $line, int $column, bool $beginning = true): void
    {
        $tabs = substr_count(substr($line->text, 0, $column + ($beginning ? 0 : 1)), "\t");

        $this->buffer .= str_repeat(AsciiGlyph::horizontalLine, 1 + $column + $tabs * (self::SPACES_PER_TAB - 1));
        $this->buffer .= '^';
    }

    /**
     * Writes $highlight's label.
     *
     * The {@see $buffer} is assumed to be written to the point where the first line
     * of `$highlight->label` can be written after a space, but this takes care of
     * writing indentation and highlight columns for later lines.
     *
     * The $highlightsByColumn are used to write ongoing highlight lines if the
     * label is more than one line long.
     *
     * The $underlineLength is the length of the line written between the
     * highlights and the beginning of the first label.
     *
     * @param list<Highlight|null> $highlightsByColumn
     */
    private function writeLabel(Highlight $highlight, array $highlightsByColumn, int $underlineLength): void
    {
        $label = $highlight->label;

        if ($label === null) {
            $this->buffer .= "\n";
            return;
        }

        $lines = explode("\n", $label);
        $this->buffer .= ' ';
        $this->buffer .= $lines[0];
        $this->buffer .= "\n";

        foreach (array_slice($lines, 1) as $text) {
            $this->writeSidebar();
            $this->buffer .= ' ';

            foreach ($highlightsByColumn as $columnHighlight) {
                if ($columnHighlight === null || $columnHighlight === $highlight) {
                    $this->buffer .= ' ';
                } else {
                    $this->buffer .= AsciiGlyph::verticalLine;
                }
            }

            $this->buffer .= str_repeat(' ', $underlineLength + 1);
            $this->buffer .= $text;
            $this->buffer .= "\n";
        }
    }

    /**
     * Writes a snippet from the source text, converting hard tab characters into
     * plain indentation.
     */
    private function writeText(string $text): void
    {
        $this->buffer .= str_replace("\t", str_repeat(' ', self::SPACES_PER_TAB), $text);
    }

    /**
     * Writes a sidebar to {@see $buffer} that includes $line as the line number if
     * given and writes $end at the end (defaults to {@see AsciiGlyph::verticalLine}).
     *
     * If $text is given, it's used in place of the line number. It can't be
     * passed at the same time as $line.
     */
    private function writeSidebar(?int $line = null, ?string $text = null, ?string $end = null): void
    {
        \assert($line === null || $text === null);

        if ($line !== null) {
            // Add 1 to line to convert from computer-friendly 0-indexed line numbers to
            // human-friendly 1-indexed line numbers.
            $text = (string) ($line + 1);
        }

        $this->buffer .= str_pad($text ?? '', $this->paddingBeforeSidebar);
        $this->buffer .= $end ?? AsciiGlyph::verticalLine;
    }

    /**
     * Returns whether $text contains only space or tab characters.
     */
    private function isOnlyWhitespace(string $text): bool
    {
        for ($i = 0; $i < \strlen($text); $i++) {
            $char = $text[$i];

            if ($char !== ' ' && $char !== "\t") {
                return false;
            }
        }

        return true;
    }

    /**
     * @template K
     * @template E
     * @template T
     * @param iterable<K, E> $elements
     * @param callable(E, K): iterable<T> $callback
     * @return \Traversable<T>
     *
     * @param-immediately-invoked-callable $callback
     */
    private static function expandMapIterable(iterable $elements, callable $callback): \Traversable
    {
        foreach ($elements as $key => $element) {
            yield from $callback($element, $key);
        }
    }
}
