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

namespace ScssPhp\ScssPhp\Parser;

use League\Uri\Contracts\UriInterface;
use SourceSpan\FileLocation;
use SourceSpan\FileSpan;
use SourceSpan\SourceFile;

/**
 * A port of Dart's string_scanner package to be used by the parser.
 *
 * The scanner only supports UTF-8 strings.
 *
 * Differences with Dart:
 * - reading a character is reading a byte, not a UTF-16 code unit (as PHP strings are not UTF-16). The
 *   {@see readUtf8Char} method can be used to consume a UTF-8 char.
 * - characters are represented as a single-char string, not as an integer with their UTF-16 char code
 * - offsets are based on bytes, not on UTF-16 code units. In practice, parsing Sass generally needs
 *   to peak following chars only when already knowing that the current char is an ASCII one, which
 *   makes this safe. When this assumption does not hold anymore, a different logic should be used
 * - as strings and regexp cannot be used interchangeably in PHP (in Dart, regexps are a different
 *   object, and both String and Regexp are implementing a Pattern interface for matching), the scanner
 *   exposes supports only strings in scan() and expect(). Should we need support for regexps, a
 *   separate method will be added.
 *
 * @internal
 */
class StringScanner
{
    private readonly string $string;

    private int $position = 0;

    private readonly SourceFile $sourceFile;

    public function __construct(string $content, ?UriInterface $sourceUrl = null)
    {
        $this->string = $content;
        $this->sourceFile = SourceFile::fromString($content, $sourceUrl);
    }

    public function getString(): string
    {
        return $this->string;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function spanFrom(int $start, ?int $end = null): FileSpan
    {
        return $this->sourceFile->span($start, $end ?? $this->position);
    }

    /**
     * The current location of the scanner.
     */
    public function getLocation(): FileLocation
    {
        return $this->sourceFile->location($this->position);
    }

    /**
     * Returns an empty span at the current location.
     */
    public function getEmptySpan(): FileSpan
    {
        return $this->sourceFile->span($this->position, $this->position);
    }

    public function isDone(): bool
    {
        return $this->position === \strlen($this->string);
    }

    /**
     * @throws FormatException if the end of the string is reached
     *
     * @phpstan-impure
     */
    public function readChar(): string
    {
        if ($this->position === \strlen($this->string)) {
            $this->fail('more input');
        }

        return $this->string[$this->position++];
    }

    /**
     * @throws FormatException if the end of the string is reached
     *
     * @phpstan-impure
     */
    public function readUtf8Char(): string
    {
        if ($this->position === \strlen($this->string)) {
            $this->fail('more input');
        }

        if (\ord($this->string[$this->position]) < 0x80) {
            return $this->string[$this->position++];
        }

        if (!preg_match('/./usA', $this->string, $m, 0, $this->position)) {
            $this->fail('utf-8 char');
        }

        $this->position += \strlen($m[0]);

        return $m[0];
    }

    /**
     * Consumes the next character in the string if it is the provided character.
     *
     * @return bool Whether the character was consumed.
     *
     * @phpstan-impure
     */
    public function scanChar(string $char): bool
    {
        if ($this->position === \strlen($this->string)) {
            return false;
        }

        if ($this->string[$this->position] !== $char) {
            return false;
        }

        ++$this->position;

        return true;
    }

    /**
     * Consumes the provided string if it appears at the current position.
     *
     * @return bool Whether the string was consumed.
     *
     * @phpstan-impure
     */
    public function scan(string $string): bool
    {
        if (!$this->matches($string)) {
            return false;
        }

        $this->position += \strlen($string);

        return true;
    }

    /**
     * Returns whether or not the provided string appears at the current position.
     *
     * This doesn't move the scan pointer forward.
     */
    public function matches(string $string): bool
    {
        if ($this->position - 1 + \strlen($string) >= \strlen($this->string)) {
            return false;
        }

        return substr($this->string, $this->position, \strlen($string)) === $string;
    }

    /**
     * If the next character in the string is $character, consumes it.
     *
     * If $character could not be consumed, throws an exception
     * describing the position of the failure. $name is used in this error as
     * the expected name of the character being matched; if it's `null`, the
     * character itself is used instead.
     *
     * @throws FormatException
     *
     * @phpstan-impure
     */
    public function expectChar(string $character, ?string $name = null): void
    {
        if ($this->scanChar($character)) {
            return;
        }

        if ($name === null) {
            $name = '"' . $character . '"';
        }

        $this->fail($name);
    }

    /**
     * @throws FormatException
     *
     * @phpstan-impure
     */
    public function expect(string $string): void
    {
        if ($this->scan($string)) {
            return;
        }

        $this->fail('"' . $string . '"');
    }

    /**
     * @throws FormatException
     */
    public function expectDone(): void
    {
        if ($this->isDone()) {
            return;
        }

        $this->fail('no more input');
    }

    /**
     * Returns the character at the given offset of the current position.
     *
     * The offset can be negative to peek already seen characters.
     * Returns null if the offset goes out of range.
     * This does not affect the position or the last match.
     */
    public function peekChar(int $offset = 0): ?string
    {
        $pos = $this->position + $offset;

        if ($pos < 0 || $pos >= \strlen($this->string)) {
            return null;
        }

        return $this->string[$pos];
    }

    /**
     * Returns the substring of the string between $start and $end (excluded).
     *
     * $end defaults to the current position.
     */
    public function substring(int $start, ?int $end = null): string
    {
        if ($end === null) {
            $end = $this->position;
        }

        if ($end < $start) {
            return '';
        }

        return substr($this->string, $start, $end - $start);
    }

    /**
     * The scanner's current (zero-based) line number.
     */
    public function getLine(): int
    {
        return $this->sourceFile->getLine($this->position);
    }

    /**
     * The scanner's current (zero-based) column number.
     */
    public function getColumn(): int
    {
        return $this->sourceFile->getColumn($this->position);
    }

    /**
     * @throws FormatException
     */
    public function error(string $message, ?int $position = null, ?int $length = null): never
    {
        $position ??= $this->position;
        $length ??= 0;

        $span = $this->sourceFile->span($position, $position + $length);

        throw new FormatException($message, $span);
    }

    /**
     * @throws FormatException
     */
    private function fail(string $message): never
    {
        $this->error("expected $message.");
    }
}
