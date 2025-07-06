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

namespace ScssPhp\ScssPhp\Util;

/**
 * @internal
 */
final class StringUtil
{
    /**
     * @param non-empty-array<string> $iter
     */
    public static function toSentence(array $iter, string $conjunction = 'and'): string
    {
        if (\count($iter) === 1) {
            return $iter[array_key_first($iter)];
        }

        $last = array_pop($iter);

        return implode(', ', $iter) . ' ' . $conjunction . ' ' . $last;
    }

    /**
     * Returns $name if $number is 1, or the plural of $name otherwise.
     *
     * By default, this just adds "s" to the end of $name to get the plural. If
     * $plural is passed, that's used instead.
     */
    public static function pluralize(string $name, int $number, ?string $plural = null): string
    {
        if ($number === 1) {
            return $name;
        }

        if ($plural !== null) {
            return $plural;
        }

        return $name . 's';
    }

    public static function trimAscii(string $string, bool $excludeEscape = false): string
    {
        $start = self::firstNonWhitespace($string);

        if ($start === null) {
            return '';
        }

        $end = self::lastNonWhitespace($string, $excludeEscape);
        assert($end !== null);

        return substr($string, $start, $end + 1);
    }

    public static function trimAsciiRight(string $string, bool $excludeEscape = false): string
    {
        $end = self::lastNonWhitespace($string, $excludeEscape);

        if ($end === null) {
            return '';
        }

        return substr($string, 0, $end + 1);
    }

    /**
     * Returns the index of the first character in $string that's not ASCII
     * whitespace, or `null` if $string is entirely spaces.
     *
     * If $excludeEscape is `true`, this doesn't move past whitespace that's
     * included in a CSS escape.
     */
    private static function firstNonWhitespace(string $string): ?int
    {
        for ($i = 0; $i < \strlen($string); $i++) {
            $char = $string[$i];

            if (!Character::isWhitespace($char)) {
                return $i;
            }
        }

        return null;
    }

    /**
     * Returns the index of the last character in $string that's not ASCII
     * whitespace, or `null` if $string is entirely spaces.
     *
     * If $excludeEscape is `true`, this doesn't move past whitespace that's
     * included in a CSS escape.
     */
    private static function lastNonWhitespace(string $string, bool $excludeEscape = false): ?int
    {
        for ($i = \strlen($string) - 1; $i >= 0; $i--) {
            $char = $string[$i];

            if (!Character::isWhitespace($char)) {
                if ($excludeEscape && $i !== 0 && $i !== \strlen($string) && $char === '\\') {
                    return $i + 1;
                }

                return $i;
            }
        }

        return null;
    }

    /**
     * Returns whether $string1 and $string2 are equal, ignoring ASCII case.
     */
    public static function equalsIgnoreCase(?string $string1, string $string2): bool
    {
        if ($string1 === $string2) {
            return true;
        }

        if ($string1 === null) {
            return false;
        }

        return self::toAsciiLowerCase($string1) === self::toAsciiLowerCase($string2);
    }

    /**
     * Returns whether $string starts with $prefix, ignoring ASCII case.
     */
    public static function startsWithIgnoreCase(string $string, string $prefix): bool
    {
        if (\strlen($string) < \strlen($prefix)) {
            return false;
        }

        for ($i = 0; $i < \strlen($prefix); $i++) {
            if (!Character::equalsIgnoreCase($string[$i], $prefix[$i])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Converts all ASCII chars to lowercase in the input string.
     *
     * This does not use `strtolower` because `strtolower` is locale-dependant
     * rather than operating on ASCII.
     * Passing an input string in an encoding that it is not ASCII compatible is
     * unsupported, and will probably generate garbage.
     */
    public static function toAsciiLowerCase(string $string): string
    {
        return strtr($string, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
    }

    /**
     * Converts all ASCII chars to uppercase in the input string.
     *
     * This does not use `strtoupper` because `strtoupper` is locale-dependant
     * rather than operating on ASCII.
     * Passing an input string in an encoding that it is not ASCII compatible is
     * unsupported, and will probably generate garbage.
     */
    public static function toAsciiUpperCase(string $string): string
    {
        return strtr($string, 'abcdefghijklmnopqrstuvwxyz', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
    }
}
