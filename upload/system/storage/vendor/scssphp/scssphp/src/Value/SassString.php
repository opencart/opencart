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

namespace ScssPhp\ScssPhp\Value;

use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Visitor\ValueVisitor;

/**
 * A SassScript string.
 *
 * Strings can either be quoted or unquoted. Unquoted strings are usually CSS
 * identifiers, but they may contain any text.
 */
final class SassString extends Value
{
    /**
     * The contents of the string.
     *
     * For quoted strings, this is the semantic content—any escape sequences that
     * were been written in the source text are resolved to their Unicode values.
     * For unquoted strings, though, escape sequences are preserved as literal
     * backslashes.
     *
     * This difference allows us to distinguish between identifiers with escapes,
     * such as `url\u28 http://example.com\u29`, and unquoted strings that
     * contain characters that aren't valid in identifiers, such as
     * `url(http://example.com)`. Unfortunately, it also means that we don't
     * consider `foo` and `f\6F\6F` the same string.
     */
    private readonly string $text;

    /**
     * Whether this string has quotes.
     */
    private readonly bool $quotes;

    public function __construct(string $text, bool $quotes = true)
    {
        $this->text = $text;
        $this->quotes = $quotes;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function hasQuotes(): bool
    {
        return $this->quotes;
    }

    public function getSassLength(): int
    {
        return mb_strlen($this->text, 'UTF-8');
    }

    public function isSpecialNumber(): bool
    {
        if ($this->quotes) {
            return false;
        }

        if (\strlen($this->text) < \strlen('min(_)')) {
            return false;
        }

        $first = $this->text[0];

        if ($first === 'c' || $first === 'C') {
            $second = $this->text[1];

            if ($second === 'l' || $second === 'L') {
                return ($this->text[2] === 'a' || $this->text[2] === 'A')
                    && ($this->text[3] === 'm' || $this->text[3] === 'M')
                    && ($this->text[4] === 'p' || $this->text[4] === 'P')
                    && $this->text[5] === '(';
            }

            if ($second === 'a' || $second === 'A') {
                return ($this->text[2] === 'l' || $this->text[2] === 'L')
                    && ($this->text[3] === 'c' || $this->text[3] === 'C')
                    && $this->text[4] === '(';
            }

            return false;
        }

        if ($first === 'v' || $first === 'V') {
            return ($this->text[1] === 'a' || $this->text[1] === 'A')
                && ($this->text[2] === 'r' || $this->text[2] === 'R')
                && $this->text[3] === '(';
        }

        if ($first === 'e' || $first === 'E') {
            return ($this->text[1] === 'n' || $this->text[1] === 'N')
                && ($this->text[2] === 'v' || $this->text[2] === 'V')
                && $this->text[3] === '(';
        }

        if ($first === 'm' || $first === 'M') {
            $second = $this->text[1];

            if ($second === 'a' || $second === 'A') {
                return ($this->text[2] === 'x' || $this->text[2] === 'X')
                    && $this->text[3] === '(';
            }

            if ($second === 'i' || $second === 'I') {
                return ($this->text[2] === 'n' || $this->text[2] === 'N')
                    && $this->text[3] === '(';
            }

            return false;
        }

        return false;
    }

    public function isVar(): bool
    {
        if ($this->quotes) {
            return false;
        }

        if (\strlen($this->text) < \strlen('var(--_)')) {
            return false;
        }

        return ($this->text[0] === 'v' || $this->text[0] === 'V')
            && ($this->text[1] === 'a' || $this->text[1] === 'A')
            && ($this->text[2] === 'r' || $this->text[2] === 'R')
            && $this->text[3] === '(';
    }

    public function isBlank(): bool
    {
        return !$this->quotes && $this->text === '';
    }

    /**
     * Converts $sassIndex into a PHP-style index into {@see text}.
     *
     * Sass indexes are one-based, while PHP indexes are zero-based. Sass
     * indexes may also be negative in order to index from the end of the string.
     *
     * In addition, Sass indices refer to Unicode code points while PHP string
     * indices refer to bytes. For example, the character U+1F60A,
     * Smiling Face With Smiling Eyes, is a single Unicode code point but is
     * represented in UTF-8 as several bytes (`0xF0`, `0x9F`, `0x98` and `0x8A`). So in
     * PHP, `substr("a😊b", 1, 1)` returns `"\xF0"`, whereas in Sass
     * `str-slice("a😊b", 1, 1)` returns `"😊"`.
     *
     * @throws SassScriptException if $sassIndex isn't a number, if that
     * number isn't an integer, or if that integer isn't a valid index for this
     * string. If $sassIndex came from a function argument, $name is the
     * argument name (without the `$`). It's used for error reporting.
     */
    public function sassIndexToStringIndex(Value $sassIndex, ?string $name = null): int
    {
        $codepointIndex = $this->sassIndexToCodePointIndex($sassIndex, $name);

        if ($codepointIndex === 0) {
            return 0;
        }

        return \strlen(mb_substr($this->text, 0, $codepointIndex, 'UTF-8'));
    }

    /**
     * Converts $sassIndex into a PHP-style index into codepoints.
     *
     * This index is suitable to use with functions dealing with codepoints
     * (i.e. the mbstring functions).
     *
     * Sass indexes are one-based, while PHP indexes are zero-based. Sass
     * indexes may also be negative in order to index from the end of the string.
     *
     * See also {@see sassIndexToStringIndex}, which is an index into {@see getText} directly.
     *
     * @throws SassScriptException if $sassIndex isn't a number, if that
     * number isn't an integer, or if that integer isn't a valid index for this
     * string. If $sassIndex came from a function argument, $name is the
     * argument name (without the `$`). It's used for error reporting.
     */
    public function sassIndexToCodePointIndex(Value $sassIndex, ?string $name = null): int
    {
        $index = $sassIndex->assertNumber($name)->assertInt($name);

        if ($index === 0) {
            throw SassScriptException::forArgument('String index may not be 0.', $name);
        }

        $sassLength = $this->getSassLength();

        if (abs($index) > $sassLength) {
            throw SassScriptException::forArgument("Invalid index $sassIndex for a string with $sassLength characters.", $name);
        }

        return $index < 0 ? $sassLength + $index : $index - 1;
    }

    public function accept(ValueVisitor $visitor)
    {
        return $visitor->visitString($this);
    }

    public function assertString(?string $name = null): SassString
    {
        return $this;
    }

    public function plus(Value $other): Value
    {
        if ($other instanceof SassString) {
            return new SassString($this->text . $other->getText(), $this->quotes);
        }

        return new SassString($this->text . $other->toCssString(), $this->quotes);
    }

    public function equals(object $other): bool
    {
        return $other instanceof SassString && $this->text === $other->text;
    }
}
