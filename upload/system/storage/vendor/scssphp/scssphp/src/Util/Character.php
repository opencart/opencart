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
final class Character
{
    /**
     * The difference between upper- and lowercase ASCII letters.
     *
     * `0b100000` can be bitwise-ORed with uppercase ASCII letters to get their
     * lowercase equivalents.
     */
    private const ASCII_CASE_BIT = 0x20;

    /**
     * Returns whether $character is an ASCII whitespace character.
     */
    public static function isWhitespace(?string $character): bool
    {
        return $character === ' ' || $character === "\t" || $character === "\n" || $character === "\r" || $character === "\f";
    }

    /**
     * Returns whether $character is a space or a tab character.
     */
    public static function isSpaceOrTab(?string $character): bool
    {
        return $character === ' ' || $character === "\t";
    }

    /**
     * Returns whether $character is an ASCII newline character.
     */
    public static function isNewline(?string $character): bool
    {
        return $character === "\n" || $character === "\r" || $character === "\f";
    }

    /**
     * Returns whether $character is a letter or a number.
     */
    public static function isAlphanumeric(string $character): bool
    {
        return self::isAlphabetic($character) || self::isDigit($character);
    }

    /**
     * Returns whether $character is a letter.
     */
    public static function isAlphabetic(string $character): bool
    {
        $charCode = \ord($character);

        return ($charCode >= \ord('a') && $charCode <= \ord('z')) || ($charCode >= \ord('A') && $charCode <= \ord('Z'));
    }

    /**
     * Returns whether $character is a digit.
     */
    public static function isDigit(?string $character): bool
    {
        if ($character === null) {
            return false;
        }

        $charCode = \ord($character);

        return $charCode >= \ord('0') && $charCode <= \ord('9');
    }

    /**
     * Returns whether $character is legal as the start of a Sass identifier.
     */
    public static function isNameStart(string $character): bool
    {
        return $character === '_' || self::isAlphabetic($character) || \ord($character) >= 0x80;
    }

    /**
     * Returns whether $character is legal in the body of a Sass identifier.
     */
    public static function isName(string $character): bool
    {
        return self::isNameStart($character) || self::isDigit($character) || $character === '-';
    }

    /**
     * Returns whether $character is a hexadecimal digit.
     */
    public static function isHex(?string $character): bool
    {
        if ($character === null) {
            return false;
        }

        if (self::isDigit($character)) {
            return true;
        }

        $charCode = \ord($character);

        if ($charCode >= \ord('a') && $charCode <= \ord('f')) {
            return true;
        }

        if ($charCode >= \ord('A') && $charCode <= \ord('F')) {
            return true;
        }

        return false;
    }

    /**
     * Returns whether $identifier is module-private.
     *
     * Assumes $identifier is a valid Sass identifier.
     */
    public static function isPrivate(string $identifier): bool
    {
        $first = $identifier[0];

        return $first === '-' || $first === '_';
    }

    /**
     * Assumes that $character is a left-hand brace-like character, and returns
     * the right-hand version.
     */
    public static function opposite(string $character): string
    {
        return match ($character) {
            '(' => ')',
            '{' => '}',
            '[' => ']',
            default => throw new \InvalidArgumentException(sprintf('Expected a brace character. Got "%s"', $character)),
        };
    }

    public static function equalsIgnoreCase(string $character1, string $character2): bool
    {
        if ($character1 === $character2) {
            return true;
        }

        // If this check fails, the characters are definitely different. If it
        // succeeds *and* either character is an ASCII letter, they're equivalent.
        if ((\ord($character1) ^ \ord($character2)) !== self::ASCII_CASE_BIT) {
            return false;
        }

        // Now we just need to verify that one of the characters is an ASCII letter.
        $upperCase1 = \ord($character1) & ~self::ASCII_CASE_BIT;

        return $upperCase1 >= \ord('A') && $upperCase1 <= \ord('Z');
    }
}
