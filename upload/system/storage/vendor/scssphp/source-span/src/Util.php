<?php

namespace SourceSpan;

use League\Uri\BaseUri;
use League\Uri\Contracts\UriInterface;

/**
 * @internal
 */
final class Util
{
    /**
     * @param iterable<object> $iter
     */
    public static function isAllTheSame(iterable $iter): bool
    {
        $previousValue = null;

        foreach ($iter as $value) {
            if ($previousValue === null) {
                $previousValue = $value;
                continue;
            }

            if (!self::isSame($value, $previousValue)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns whether 2 objects are the same, considering URIs as the same by equality rather than reference.
     */
    public static function isSame(object $object1, object $object2): bool
    {
        if ($object1 === $object2) {
            return true;
        }

        if ($object1 instanceof UriInterface && $object2 instanceof UriInterface) {
            return $object1->toString() === $object2->toString();
        }

        return false;
    }

    /**
     * Returns whether $span covers multiple lines.
     */
    public static function isMultiline(SourceSpan $span): bool
    {
        return $span->getStart()->getLine() !== $span->getEnd()->getLine();
    }

    /**
     * Sets the first `null` element of $list to $element.
     *
     * @template E
     * @param list<E|null> $list
     * @param E            $element
     */
    public static function replaceFirstNull(array &$list, $element): void
    {
        $index = array_search(null, $list, true);

        if ($index === false) {
            throw new \InvalidArgumentException('The list contains no null elements.');
        }

        // @phpstan-ignore parameterByRef.type
        $list[$index] = $element;
        \assert(array_is_list($list));
    }

    /**
     * Sets the element of $list that currently contains $element to `null`.
     *
     * @template E
     * @param list<E|null> $list
     * @param E            $element
     */
    public static function replaceWithNull(array &$list, $element): void
    {
        $index = array_search($element, $list, true);

        if ($index === false) {
            throw new \InvalidArgumentException('The list contains no matching elements.');
        }

        // @phpstan-ignore parameterByRef.type
        $list[$index] = null;
        \assert(array_is_list($list));
    }

    /**
     * Finds a line in $context containing $text at the specified column.
     *
     * Returns the index in $context where that line begins, or null if none
     * exists.
     */
    public static function findLineStart(string $context, string $text, int $column): ?int
    {
        // If the text is empty, we just want to find the first line that has at least
        // $column characters.
        if ($text === '') {
            $beginningOfLine = 0;

            while (true) {
                $index = strpos($context, "\n", $beginningOfLine);

                if ($index === false) {
                    return \strlen($context) - $beginningOfLine >= $column ? $beginningOfLine : null;
                }

                if ($index - $beginningOfLine >= $column) {
                    return $beginningOfLine;
                }

                $beginningOfLine = $index + 1;
            }
        }

        $index = strpos($context, $text);

        while ($index !== false) {
            // Start looking before $index in case $text starts with a newline.
            $lineStart = $index === 0 ? 0 : Util::lastIndexOf($context, "\n", $index - 1) + 1;
            $textColumn = $index - $lineStart;

            if ($column === $textColumn) {
                return $lineStart;
            }

            $index = strpos($context, $text, $index + 1);
        }

        return null;
    }

    /**
     * Returns a two-element list containing the start and end locations of the
     * span from $start bytes (inclusive) to $end bytes (exclusive)
     * after the beginning of $span.
     *
     * @return array{SourceLocation, SourceLocation}
     */
    public static function subspanLocations(SourceSpan $span, int $start, ?int $end = null): array
    {
        $text = $span->getText();
        $startLocation = $span->getStart();
        $line = $startLocation->getLine();
        $column = $startLocation->getColumn();

        // Adjust $line and $column as necessary if the character at $i in $text
        // is a newline.
        $consumeCodePoint = function (int $i) use ($text, &$line, &$column) {
            $codeUnit = $text[$i];

            if (
                $codeUnit === "\n" ||
                // A carriage return counts as a newline, but only if it's not
                // followed by a line feed.
                ($codeUnit === "\r" && ($i + 1 === \strlen($text) || $text[$i + 1] !== "\n"))
            ) {
                $line += 1;
                $column = 0;
            } else {
                $column += 1;
            }
        };

        for ($i = 0; $i < $start; $i++) {
            $consumeCodePoint($i);
        }

        $newStartLocation = new SimpleSourceLocation($startLocation->getOffset() + $start, $span->getSourceUrl(), $line, $column);

        if ($end === null || $end === $span->getLength()) {
            $newEndLocation = $span->getEnd();
        } elseif ($end === $start) {
            $newEndLocation = $newStartLocation;
        } else {
            for ($i = $start; $i < $end; $i++) {
                $consumeCodePoint($i);
            }

            $newEndLocation = new SimpleSourceLocation($startLocation->getOffset() + $end, $span->getSourceUrl(), $line, $column);
        }

        return [$newStartLocation, $newEndLocation];
    }

    /**
     * The starting position of the last match $needle in this string.
     *
     * Finds a match of $needle by searching backward starting at $start.
     * Returns -1 if $needle could not be found in this string.
     * If $start is omitted, search starts from the end of the string.
     */
    public static function lastIndexOf(string $string, string $needle, ?int $start = null): int
    {
        if ($start === null || $start === \strlen($string)) {
            $position = strrpos($string, $needle);
        } else {
            if ($start < 0) {
                throw new \InvalidArgumentException("Start must be a non-negative integer");
            }

            if ($start > \strlen($string)) {
                throw new \InvalidArgumentException("Start must not be greater than the length of the string");
            }

            $position = strrpos($string, $needle, $start - \strlen($string));
        }

        return $position === false ? -1 : $position;
    }

    /**
     * Returns the text of the string from $start to $end (exclusive).
     *
     * If $end isn't passed, it defaults to the end of the string.
     */
    public static function substring(string $text, int $start, ?int $end = null): string
    {
        if ($end === null) {
            return substr($text, $start);
        }

        if ($end < $start) {
            $length = 0;
        } else {
            $length = $end - $start;
        }

        return substr($text, $start, $length);
    }

    public static function isSameUrl(?UriInterface $url1, ?UriInterface $url2): bool
    {
        if ($url1 === null) {
            return $url2 === null;
        }

        if ($url2 === null) {
            return false;
        }

        return (string) $url1 === (string) $url2;
    }

    /**
     * Finds the first index in the list that satisfies the provided $test.
     *
     * @template E
     *
     * @param list<E> $list
     * @param callable(E): bool $test
     */
    public static function indexWhere(array $list, callable $test): ?int
    {
        foreach ($list as $index => $element) {
            if ($test($element)) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Check that a range represents a slice of an indexable object.
     *
     * Throws if the range is not valid for an indexable object with
     * the given length.
     * A range is valid for an indexable object with a given $length
     * if `0 <= $start <= $end <= $length`.
     * An `end` of `null` is considered equivalent to `length`.
     *
     * @throws \OutOfRangeException
     */
    public static function checkValidRange(int $start, ?int $end, int $length, ?string $startName = null, ?string $endName = null): void
    {
        if ($start < 0 || $start > $length) {
            $startName ??= 'start';
            $startNameDisplay = $startName ? " $startName" : '';

            throw new \OutOfRangeException("Invalid value:$startNameDisplay must be between 0 and $length: $start.");
        }

        if ($end !== null) {
            if ($end < $start || $end > $length) {
                $endName ??= 'end';
                $endNameDisplay = $endName ? " $endName" : '';

                throw new \OutOfRangeException("Invalid value:$endNameDisplay must be between $start and $length: $end.");
            }
        }
    }

    /**
     * @template T
     *
     * @param list<T> $list
     *
     * @return T
     */
    public static function listLast(array $list)
    {
        $count = count($list);

        if ($count === 0) {
            throw new \LogicException('The list may not be empty.');
        }

        return $list[$count - 1];
    }

    /**
     * Returns a pretty URI for a path
     */
    public static function prettyUri(string|UriInterface $path): string
    {
        if ($path instanceof UriInterface) {
            if ($path->getScheme() !== 'file') {
                return (string) $path;
            }

            $path = self::pathFromUri($path);
        }

        $normalizedPath = $path;
        $normalizedRootDirectory = getcwd() . '/';

        if (\DIRECTORY_SEPARATOR === '\\') {
            $normalizedRootDirectory = str_replace('\\', '/', $normalizedRootDirectory);
            $normalizedPath = str_replace('\\', '/', $path);
        }

        if (str_starts_with($normalizedPath, $normalizedRootDirectory)) {
            return substr($path, \strlen($normalizedRootDirectory));
        }

        return $path;
    }

    private static function pathFromUri(UriInterface $uri): string
    {
        if (\DIRECTORY_SEPARATOR === '\\') {
            return BaseUri::from($uri)->windowsPath() ?? throw new \InvalidArgumentException("Uri $uri must have scheme 'file:'.");
        }

        return BaseUri::from($uri)->unixPath() ?? throw new \InvalidArgumentException("Uri $uri must have scheme 'file:'.");
    }
}
