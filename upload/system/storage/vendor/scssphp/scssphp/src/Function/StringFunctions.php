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

namespace ScssPhp\ScssPhp\Function;

use ScssPhp\ScssPhp\Util\StringUtil;
use ScssPhp\ScssPhp\Value\SassNull;
use ScssPhp\ScssPhp\Value\SassNumber;
use ScssPhp\ScssPhp\Value\SassString;
use ScssPhp\ScssPhp\Value\Value;

/**
 * @internal
 */
final class StringFunctions
{
    private static ?int $previousId = null;

    /**
     * @param list<Value> $arguments
     */
    public static function unquote(array $arguments): Value
    {
        $string = $arguments[0]->assertString('string');

        if (!$string->hasQuotes()) {
            return $string;
        }

        return new SassString($string->getText(), false);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function quote(array $arguments): Value
    {
        $string = $arguments[0]->assertString('string');

        if ($string->hasQuotes()) {
            return $string;
        }

        return new SassString($string->getText(), true);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function length(array $arguments): Value
    {
        $string = $arguments[0]->assertString('string');

        return SassNumber::create($string->getSassLength());
    }

    /**
     * @param list<Value> $arguments
     */
    public static function insert(array $arguments): Value
    {
        $string = $arguments[0]->assertString('string');
        $insert = $arguments[1]->assertString('insert');
        $index = $arguments[2]->assertNumber('index');
        $index->assertNoUnits('index');

        $indexInt = $index->assertInt('index');

        // str-insert has unusual behavior for negative inputs. It guarantees that
        // the `$insert` string is at `$index` in the result, which means that we
        // want to insert before `$index` if it's positive and after if it's
        // negative.
        if ($indexInt < 0) {
            // +1 because negative indexes start counting from -1 rather than 0, and
            // another +1 because we want to insert *after* that index.
            $indexInt = max($string->getSassLength() + $indexInt + 2, 0);
        }

        $codepointIndex = self::codepointForIndex($indexInt, $string->getSassLength());

        return new SassString(
            mb_substr($string->getText(), 0, $codepointIndex) . $insert->getText() . mb_substr($string->getText(), $codepointIndex),
            $string->hasQuotes()
        );
    }

    /**
     * @param list<Value> $arguments
     */
    public static function index(array $arguments): Value
    {
        $string = $arguments[0]->assertString('string');
        $substring = $arguments[1]->assertString('substring');

        $codepointIndex = mb_strpos($string->getText(), $substring->getText());

        if ($codepointIndex === false) {
            return SassNull::create();
        }

        return SassNumber::create($codepointIndex + 1);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function slice(array $arguments): Value
    {
        $string = $arguments[0]->assertString('string');
        $start = $arguments[1]->assertNumber('start-at');
        $end = $arguments[2]->assertNumber('end-at');
        $start->assertNoUnits('start-at');
        $end->assertNoUnits('end-at');

        $lengthInCodepoints = $string->getSassLength();

        // No matter what the start index is, an end index of 0 will produce an
        // empty string.
        $endInt = $end->assertInt('end-at');
        if ($endInt === 0) {
            return new SassString('', $string->hasQuotes());
        }

        $startCodepoint = self::codepointForIndex($start->assertInt('start-at'), $lengthInCodepoints);
        $endCodepoint = self::codepointForIndex($endInt, $lengthInCodepoints, true);

        if ($endCodepoint === $lengthInCodepoints) {
            $endCodepoint--;
        }

        if ($endCodepoint < $startCodepoint) {
            return new SassString('', $string->hasQuotes());
        }

        return new SassString(
            mb_substr($string->getText(), $startCodepoint, $endCodepoint + 1 - $startCodepoint),
            $string->hasQuotes()
        );
    }

    /**
     * @param list<Value> $arguments
     */
    public static function toUpperCase(array $arguments): Value
    {
        $string = $arguments[0]->assertString('string');

        return new SassString(StringUtil::toAsciiUpperCase($string->getText()), $string->hasQuotes());
    }

    /**
     * @param list<Value> $arguments
     */
    public static function toLowerCase(array $arguments): Value
    {
        $string = $arguments[0]->assertString('string');

        return new SassString(StringUtil::toAsciiLowerCase($string->getText()), $string->hasQuotes());
    }

    /**
     * @param list<Value> $arguments
     */
    public static function uniqueId(array $arguments): Value
    {
        if (self::$previousId === null) {
            self::$previousId = random_int(0, 36 ** 6);
        }

        // Make it difficult to guess the next ID by randomizing the increase.
        self::$previousId += random_int(0, 36) + 1;

        if (self::$previousId > 36 ** 6) {
            self::$previousId %= 36 ** 6;
        }

        // The leading "u" ensures that the result is a valid identifier.
        return new SassString('u' . str_pad(base_convert((string) self::$previousId, 10, 36), 6, '0', STR_PAD_LEFT), false);
    }

    /**
     * Converts a Sass string index into a codepoint index into a string which
     * has length $lengthInCodepoints measured in codepoints (with `mb_strlen`).
     *
     * A Sass string index is one-based, and uses negative numbers to count
     * backwards from the end of the string.
     *
     * If $index is negative and it points before the beginning of
     * $lengthInCodepoints, this will return `0` if $allowNegative is `false` and
     * the index if it's `true`.
     */
    private static function codepointForIndex(int $index, int $lengthInCodepoints, bool $allowNegative = false): int
    {
        if ($index === 0) {
            return 0;
        }

        if ($index > 0) {
            return min($index - 1, $lengthInCodepoints);
        }

        $result = $lengthInCodepoints + $index;

        if ($result < 0 && !$allowNegative) {
            return 0;
        }

        return $result;
    }
}
