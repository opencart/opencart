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
use ScssPhp\ScssPhp\Exception\MultiSpanSassFormatException;
use ScssPhp\ScssPhp\Exception\SassFormatException;
use ScssPhp\ScssPhp\Exception\SimpleSassFormatException;
use ScssPhp\ScssPhp\Logger\LoggerInterface;
use ScssPhp\ScssPhp\Logger\QuietLogger;
use ScssPhp\ScssPhp\SourceSpan\LazyFileSpan;
use ScssPhp\ScssPhp\Util\Character;
use ScssPhp\ScssPhp\Util\ParserUtil;
use SourceSpan\FileLocation;
use SourceSpan\FileSpan;

/**
 * @internal
 */
class Parser
{
    protected readonly StringScanner $scanner;

    protected readonly LoggerInterface $logger;

    /**
     * A map used to map source spans in the text being parsed back to their
     * original locations in the source file, if this isn't being parsed directly
     * from source.
     */
    private readonly ?InterpolationMap $interpolationMap;

    /**
     * Parses $text as a CSS identifier and returns the result.
     *
     * @throws SassFormatException if parsing fails.
     */
    public static function parseIdentifier(string $text, ?LoggerInterface $logger = null): string
    {
        return (new Parser($text, $logger))->doParseIdentifier();
    }

    /**
     * Returns whether $text is a valid CSS identifier.
     */
    public static function isIdentifier(string $text, ?LoggerInterface $logger = null): bool
    {
        try {
            self::parseIdentifier($text, $logger);

            return true;
        } catch (SassFormatException) {
            return false;
        }
    }

    public function __construct(string $contents, ?LoggerInterface $logger = null, ?UriInterface $sourceUrl = null, ?InterpolationMap $interpolationMap = null)
    {
        $this->scanner = new StringScanner($contents, $sourceUrl);
        $this->logger = $logger ?? new QuietLogger();
        $this->interpolationMap = $interpolationMap;
    }

    /**
     * @throws SassFormatException
     */
    private function doParseIdentifier(): string
    {
        return $this->wrapSpanFormatException(function () {
            $result = $this->identifier();
            $this->scanner->expectDone();

            return $result;
        });
    }

    /**
     * Consumes whitespace, including any comments.
     */
    protected function whitespace(): void
    {
        do {
            $this->whitespaceWithoutComments();
        } while ($this->scanComment());
    }

    /**
     * Consumes whitespace, but not comments.
     */
    protected function whitespaceWithoutComments(): void
    {
        while (!$this->scanner->isDone() && Character::isWhitespace($this->scanner->peekChar())) {
            $this->scanner->readChar();
        }
    }

    /**
     * Consumes spaces and tabs.
     */
    protected function spaces(): void
    {
        while (!$this->scanner->isDone() && Character::isSpaceOrTab($this->scanner->peekChar())) {
            $this->scanner->readChar();
        }
    }

    /**
     * Consumes and ignores a comment if possible.
     *
     * Returns whether the comment was consumed.
     */
    protected function scanComment(): bool
    {
        if ($this->scanner->peekChar() !== '/') {
            return false;
        }

        $next = $this->scanner->peekChar(1);

        if ($next === '/') {
            return $this->silentComment();
        }

        if ($next === '*') {
            $this->loudComment();
            return true;
        }

        return false;
    }

    /**
     * Like {@see whitespace}, but throws an error if no whitespace is consumed.
     */
    protected function expectWhitespace(): void
    {
        if ($this->scanner->isDone() || !(Character::isWhitespace($this->scanner->peekChar()) || $this->scanComment())) {
            $this->scanner->error('Expected whitespace.');
        }

        $this->whitespace();
    }

    /**
     * Consumes and ignores a single silent (Sass-style) comment, not including
     * the trailing newline.
     *
     * Returns whether the comment was consumed.
     */
    protected function silentComment(): bool
    {
        $this->scanner->expect('//');

        while (!$this->scanner->isDone() && !Character::isNewline($this->scanner->peekChar())) {
            $this->scanner->readChar();
        }

        return true;
    }

    /**
     * Consumes and ignores a loud (CSS-style) comment.
     */
    protected function loudComment(): void
    {
        $this->scanner->expect('/*');

        while (true) {
            $next = $this->scanner->readChar();

            if ($next !== '*') {
                continue;
            }

            do {
                $next = $this->scanner->readChar();
            } while ($next === '*');

            if ($next === '/') {
                break;
            }
        }
    }

    /**
     * Consumes a plain CSS identifier.
     *
     * If $normalize is `true`, this converts underscores into hyphens.
     *
     * If $unit is `true`, this doesn't parse a `-` followed by a digit. This
     * ensures that `1px-2px` parses as subtraction rather than the unit
     * `px-2px`.
     */
    protected function identifier(bool $normalize = false, bool $unit = false): string
    {
        $text = '';

        if ($this->scanner->scanChar('-')) {
            $text .= '-';


            if ($this->scanner->scanChar('-')) {
                $text .= '-';
                $text .= $this->consumeIdentifierBody($normalize, $unit);

                return $text;
            }
        }

        $first = $this->scanner->peekChar();

        if ($first === null) {
            $this->scanner->error('Expected identifier.');
        }

        if ($normalize && $first === '_') {
            $this->scanner->readChar();
            $text .= '-';
        } elseif (Character::isNameStart($first)) {
            $text .= $this->scanner->readUtf8Char();
        } elseif ($first === '\\') {
            $text .= $this->escape(true);
        } else {
            $this->scanner->error('Expected identifier.');
        }

        $text .= $this->consumeIdentifierBody($normalize, $unit);

        return $text;
    }

    /**
     * Consumes a chunk of a plain CSS identifier after the name start.
     */
    public function identifierBody(): string
    {
        $text = $this->consumeIdentifierBody();

        if ($text === '') {
            $this->scanner->error('Expected identifier body.');
        }

        return $text;
    }

    private function consumeIdentifierBody(bool $normalize = false, bool $unit = false): string
    {
        $text = '';

        while (true) {
            $next = $this->scanner->peekChar();

            if ($next === null) {
                break;
            }

            if ($unit && $next === '-') {
                $second = $this->scanner->peekChar(1);

                if ($second !== null && ($second === '.' || Character::isDigit($second))) {
                    break;
                }

                $text .= $this->scanner->readChar();
            } elseif ($normalize && $next === '_') {
                $this->scanner->readChar();
                $text .= '-';
            } elseif (Character::isName($next)) {
                $text .= $this->scanner->readUtf8Char();
            } elseif ($next === '\\') {
                $text .= $this->escape();
            } else {
                break;
            }
        }

        return $text;
    }

    /**
     * Consumes a plain CSS string.
     *
     * This returns the parsed contents of the string—that is, it doesn't include
     * quotes and its escapes are resolved.
     */
    protected function string(): string
    {
        $quote = $this->scanner->readChar();

        if ($quote !== '"' && $quote !== "'") {
            $this->scanner->error('Expected string.');
        }

        $buffer = '';

        while (true) {
            $next = $this->scanner->peekChar();

            if ($next === $quote) {
                $this->scanner->readChar();
                break;
            }

            if ($next === null || Character::isNewline($next)) {
                $this->scanner->error("Expected $quote.");
            }

            if ($next === '\\') {
                $second = $this->scanner->peekChar(1);

                if ($second !== null && Character::isNewline($second)) {
                    $this->scanner->readChar();
                    $this->scanner->readChar();
                } else {
                    $buffer .= $this->escapeCharacter();
                }
            } else {
                $buffer .= $this->scanner->readUtf8Char();
            }
        }

        return $buffer;
    }

    /**
     * Consumes and returns a natural number (that is, a non-negative integer) as a double.
     *
     * Doesn't support scientific notation.
     */
    protected function naturalNumber(): float
    {
        $first = $this->scanner->readChar();

        if (!Character::isDigit($first)) {
            $this->scanner->error('Expected digit.', $this->scanner->getPosition() - 1);
        }

        $number = (float) intval($first);

        while (Character::isDigit($this->scanner->peekChar())) {
            $number *= 10;
            $number += intval($this->scanner->readChar());
        }

        return $number;
    }

    /**
     * Consumes tokens until it reaches a top-level `";"`, `")"`, `"]"`,
     * or `"}"` and returns their contents as a string.
     *
     * If $allowEmpty is `false` (the default), this requires at least one token.
     */
    protected function declarationValue(bool $allowEmpty = false): string
    {
        $buffer = '';
        $brackets = [];
        $wroteNewline = false;

        while (true) {
            $next = $this->scanner->peekChar();

            if ($next === null) {
                break;
            }

            switch ($next) {
                case '\\':
                    $buffer .= $this->escape(true);
                    $wroteNewline = false;
                    break;

                case '"':
                case "'":
                    $buffer .= $this->rawText($this->string(...));
                    $wroteNewline = false;
                    break;

                case '/':
                    if ($this->scanner->peekChar(1) === '*') {
                        $buffer .= $this->rawText($this->loudComment(...));
                    } else {
                        $buffer .= $this->scanner->readChar();
                    }
                    $wroteNewline = false;
                    break;

                case ' ':
                case "\t":
                    $second = $this->scanner->peekChar(1);
                    if ($wroteNewline || $second === null || !Character::isWhitespace($second)) {
                        $buffer .= ' ';
                    }
                    $this->scanner->readChar();
                    break;

                case "\n":
                case "\r":
                case "\f":
                    $prev = $this->scanner->peekChar(-1);
                    if ($prev === null || !Character::isNewline($prev)) {
                        $buffer .= "\n";
                    }
                    $this->scanner->readChar();
                    $wroteNewline = true;
                    break;

                case '(':
                case '{':
                case '[':
                    $buffer .= $next;
                    $brackets[] = Character::opposite($this->scanner->readChar());
                    $wroteNewline = false;
                    break;

                case ')':
                case '}':
                case ']':
                    if (empty($brackets)) {
                        break 2;
                    }

                    $buffer .= $next;
                    $this->scanner->expectChar(array_pop($brackets));
                    $wroteNewline = false;
                    break;

                case ';':
                    if (empty($brackets)) {
                        break 2;
                    }

                    $buffer .= $this->scanner->readChar();
                    break;

                case 'u':
                case 'U':
                    $url = $this->tryUrl();

                    if ($url !== null) {
                        $buffer .= $url;
                    } else {
                        $buffer .= $this->scanner->readChar();
                    }

                    $wroteNewline = false;
                    break;

                default:
                    if ($this->lookingAtIdentifier()) {
                        $buffer .= $this->identifier();
                    } else {
                        $buffer .= $this->scanner->readUtf8Char();
                    }
                    $wroteNewline = false;
                    break;
            }
        }

        if (!empty($brackets)) {
            $this->scanner->expectChar(array_pop($brackets));
        }

        if (!$allowEmpty && $buffer === '') {
            $this->scanner->error('Expected token.');
        }

        return $buffer;
    }

    /**
     * Consumes a `url()` token if possible, and returns `null` otherwise.
     */
    protected function tryUrl(): ?string
    {
        $start = $this->scanner->getPosition();

        if (!$this->scanIdentifier('url')) {
            return null;
        }

        if (!$this->scanner->scanChar('(')) {
            $this->scanner->setPosition($start);

            return null;
        }

        $this->whitespace();

        $buffer = 'url(';

        while (true) {
            $next = $this->scanner->peekChar();

            if ($next === null) {
                break;
            }

            $nextCharCode = \ord($next);

            if ($next === '\\') {
                $buffer .= $this->escape();
            } elseif ($next === '%' || $next === '&' || $next === '#' || ($nextCharCode >= \ord('*') && $nextCharCode <= \ord('~')) || $nextCharCode >= 0x80) {
                $buffer .= $this->scanner->readUtf8Char();
            } elseif (Character::isWhitespace($next)) {
                $this->whitespace();

                if ($this->scanner->peekChar() !== ')') {
                    break;
                }
            } elseif ($next === ')') {
                $buffer .= $this->scanner->readChar();

                return $buffer;
            } else {
                break;
            }
        }

        $this->scanner->setPosition($start);

        return null;
    }

    /**
     * Consumes a Sass variable name, and returns its name without the dollar sign.
     */
    protected function variableName(): string
    {
        $this->scanner->expectChar('$');

        return $this->identifier(true);
    }

    /**
     * Consumes an escape sequence and returns the text that defines it.
     *
     * If $identifierStart is true, this normalizes the escape sequence as
     * though it were at the beginning of an identifier.
     */
    protected function escape(bool $identifierStart = false): string
    {
        $start = $this->scanner->getPosition();

        $this->scanner->expectChar('\\');

        $first = $this->scanner->peekChar();

        if ($first === null) {
            $this->scanner->error('Expected escape sequence.');
        }

        if (Character::isNewline($first)) {
            $this->scanner->error('Expected escape sequence.');
        }

        if (Character::isHex($first)) {
            $value = 0;
            for ($i = 0; $i < 6; $i++) {
                $next = $this->scanner->peekChar();

                if ($next === null || !Character::isHex($next)) {
                    break;
                }

                $value *= 16;
                $value += hexdec($this->scanner->readChar());
                assert(\is_int($value));
            }

            $this->scanCharIf(Character::isWhitespace(...));
            $valueText = mb_chr($value, 'UTF-8');
        } else {
            $valueText = $this->scanner->readUtf8Char();
            $value = mb_ord($valueText, 'UTF-8');
        }

        if ($valueText === false) {
            $this->scanner->error('Invalid Unicode code point.', $start);
        }

        if ($identifierStart ? Character::isNameStart($valueText) : Character::isName($valueText)) {
            if ($value > 0x10ffff) {
                $this->scanner->error('Invalid Unicode code point.', $start);
            }

            return $valueText;
        }

        if ($value <= 0x1f || $valueText === "\x7f" || ($identifierStart && Character::isDigit($valueText))) {
            $hexValueText = $value === 0 ? '0' : ltrim(bin2hex($valueText), '0');
            return '\\' . $hexValueText . ' ';
        }

        return '\\' . $valueText;
    }

    /**
     * Consumes an escape sequence and returns the character it represents.
     */
    protected function escapeCharacter(): string
    {
        return ParserUtil::consumeEscapedCharacter($this->scanner);
    }

    /**
     * @param callable(string): bool $condition
     *
     * @param-immediately-invoked-callable $condition
     *
     * @phpstan-impure
     */
    protected function scanCharIf(callable $condition): bool
    {
        $next = $this->scanner->peekChar();

        if ($next === null || !$condition($next)) {
            return false;
        }

        $this->scanner->readChar();

        return true;
    }

    /**
     * Consumes the next character or escape sequence if it matches $character.
     *
     * Matching will be case-insensitive unless $caseSensitive is true.
     * When matching case-insensitively, $character must be passed in lowercase.
     *
     * This only supports ASCII identifier characters.
     */
    protected function scanIdentChar(string $character, bool $caseSensitive = false): bool
    {
        $matches = function (string $actual) use ($character, $caseSensitive): bool {
            if ($caseSensitive) {
                return $actual === $character;
            }

            return \strtolower($actual) === $character;
        };

        $next = $this->scanner->peekChar();

        if ($next !== null && $matches($next)) {
            $this->scanner->readChar();

            return true;
        }

        if ($next === '\\') {
            $start = $this->scanner->getPosition();

            if ($matches($this->escapeCharacter())) {
                return true;
            }

            $this->scanner->setPosition($start);
        }

        return false;
    }

    /**
     * Consumes the next character or escape sequence and asserts it matches $char.
     *
     * Matching will be case-insensitive unless $caseSensitive is true.
     * When matching case-insensitively, $char must be passed in lowercase.
     *
     * This only supports ASCII identifier characters.
     */
    protected function expectIdentChar(string $char, bool $caseSensitive = false): void
    {
        if ($this->scanIdentChar($char, $caseSensitive)) {
            return;
        }

        $this->scanner->error("Expected \"$char\"");
    }

    /**
     * Returns whether the scanner is immediately before a number.
     *
     * This follows [the CSS algorithm][].
     *
     * [the CSS algorithm]: https://drafts.csswg.org/css-syntax-3/#starts-with-a-number
     */
    protected function lookingAtNumber(): bool
    {
        $first = $this->scanner->peekChar();

        if ($first === null) {
            return false;
        }

        if (Character::isDigit($first)) {
            return true;
        }

        if ($first === '.') {
            $second = $this->scanner->peekChar(1);

            return $second !== null && Character::isDigit($second);
        }

        if ($first === '+' || $first === '-') {
            $second = $this->scanner->peekChar(1);

            if ($second === null) {
                return false;
            }

            if (Character::isDigit($second)) {
                return true;
            }

            if ($second !== '.') {
                return false;
            }

            $third = $this->scanner->peekChar(2);

            return $third !== null && Character::isDigit($third);
        }

        return false;
    }

    /**
     * Returns whether the scanner is immediately before a plain CSS identifier.
     *
     * If $forward is passed, this looks that many characters forward instead.
     *
     * This is based on [the CSS algorithm][], but it assumes all backslashes
     * start escapes.
     *
     * [the CSS algorithm]: https://drafts.csswg.org/css-syntax-3/#would-start-an-identifier
     */
    protected function lookingAtIdentifier(int $forward = 0): bool
    {
        $first = $this->scanner->peekChar($forward);

        if ($first === null) {
            return false;
        }

        if ($first === '\\' || Character::isNameStart($first)) {
            return true;
        }

        if ($first !== '-') {
            return false;
        }

        $second = $this->scanner->peekChar($forward + 1);

        if ($second === null) {
            return false;
        }

        return $second === '\\' || $second === '-' || Character::isNameStart($second);
    }

    /**
     * Returns whether the scanner is immediately before a sequence of characters
     * that could be part of a plain CSS identifier body.
     */
    protected function lookingAtIdentifierBody(): bool
    {
        $next = $this->scanner->peekChar();

        return $next !== null && ($next === '\\' || Character::isName($next));
    }

    /**
     * Consumes an identifier if its name exactly matches $text.
     *
     * When matching case-insensitively, $text must be passed in lowercase.
     *
     * This only supports ASCII identifiers.
     */
    protected function scanIdentifier(string $text, bool $caseSensitive = false): bool
    {
        if (!$this->lookingAtIdentifier()) {
            return false;
        }

        $start = $this->scanner->getPosition();

        if ($this->consumeIdentifier($text, $caseSensitive) && !$this->lookingAtIdentifierBody()) {
            return true;
        }

        $this->scanner->setPosition($start);

        return false;
    }

    /**
     * Returns whether an identifier whose name exactly matches $text is at the
     * current scanner position.
     *
     * This doesn't move the scan pointer forward
     */
    protected function matchesIdentifier(string $text, bool $caseSensitive = false): bool
    {
        if (!$this->lookingAtIdentifier()) {
            return false;
        }

        $start = $this->scanner->getPosition();
        $result = $this->consumeIdentifier($text, $caseSensitive) && !$this->lookingAtIdentifierBody();
        $this->scanner->setPosition($start);

        return $result;
    }

    /**
     * Consumes $text as an identifier, but doesn't verify whether there's
     * additional identifier text afterwards.
     *
     * Returns `true` if the full $text is consumed and `false` otherwise, but
     * doesn't reset the scan pointer.
     */
    private function consumeIdentifier(string $text, bool $caseSensitive): bool
    {
        for ($i = 0; $i < \strlen($text); $i++) {
            if (!$this->scanIdentChar($text[$i], $caseSensitive)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Consumes an identifier asserts that its name exactly matches $text.
     *
     * When matching case-insensitively, $text must be passed in lowercase.
     *
     * This only supports ASCII identifiers.
     */
    protected function expectIdentifier(string $text, ?string $name = null, bool $caseSensitive = false): void
    {
        $name ??= "\"$text\"";

        $start = $this->scanner->getPosition();

        for ($i = 0; $i < \strlen($text); $i++) {
            if ($this->scanIdentChar($text[$i], $caseSensitive)) {
                continue;
            }

            $this->scanner->error("Expected $name.", $start);
        }

        if (!$this->lookingAtIdentifierBody()) {
            return;
        }

        $this->scanner->error("Expected $name.", $start);
    }

    /**
     * Runs $consumer and returns the source text that it consumes.
     *
     * @param callable(): (mixed|void) $consumer
     *
     * @param-immediately-invoked-callable $consumer
     */
    protected function rawText(callable $consumer): string
    {
        $start = $this->scanner->getPosition();
        $consumer();

        return $this->scanner->substring($start);
    }

    /**
     * Like {@see StringScanner::spanFrom()} but passes the span through {@see $interpolationMap} if it's available.
     */
    protected function spanFrom(int $position): FileSpan
    {
        $span = $this->scanner->spanFrom($position);

        if ($this->interpolationMap === null) {
            return $span;
        }

        $interpolationMap = $this->interpolationMap;

        return new LazyFileSpan(static fn() => $interpolationMap->mapSpan($span));
    }

    /**
     * Prints a warning to standard error, associated with $span.
     */
    protected function warn(string $message, FileSpan $span): void
    {
        $this->logger->warn($message, null, $span);
    }

    /**
     * Throws an error associated with $position.
     *
     * @throws FormatException
     */
    protected function error(string $message, FileSpan $span, ?\Throwable $previous = null): never
    {
        throw new FormatException($message, $span, $previous);
    }

    /**
     * Runs $callback and wraps any {@see FormatException} it throws in a
     * {@see SassFormatException}
     *
     * @template T
     * @param callable(): T $callback
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     *
     * @throws SassFormatException
     */
    protected function wrapSpanFormatException(callable $callback)
    {
        try {
            try {
                return $callback();
            } catch (FormatException $e) {
                if ($this->interpolationMap === null) {
                    throw $e;
                }

                throw $this->interpolationMap->mapException($e);
            }
        } catch (MultiSourceFormatException $error) {
            $span = $error->getSpan();
            $secondarySpans = $error->secondarySpans;

            if (0 === stripos($error->getMessage(), 'expected')) {
                $span = $this->adjustExceptionSpan($span);
                $secondarySpans = array_map(fn (FileSpan $span) => $this->adjustExceptionSpan($span), $secondarySpans);
            }

            throw new MultiSpanSassFormatException($error->getMessage(), $span, $error->primaryLabel, $secondarySpans, $error);
        } catch (FormatException $error) {
            $span = $error->getSpan();

            if (0 === stripos($error->getMessage(), 'expected')) {
                $span = $this->adjustExceptionSpan($span);
            }

            throw new SimpleSassFormatException($error->getMessage(), $span, $error);
        }
    }

    /**
     * Moves span to {@see firstNewlineBefore} if necessary.
     */
    private function adjustExceptionSpan(FileSpan $span): FileSpan
    {
        if ($span->getLength() > 0) {
            return $span;
        }

        $start = $this->firstNewlineBefore($span->getStart());

        if ($start === $span->getStart()) {
            return $span;
        }

        return $start->pointSpan();
    }

    /**
     * If $location is separated from the previous non-whitespace character in
     * `$scanner->getString()` by one or more newlines, returns the location of the last
     * separating newline.
     *
     * Otherwise returns $location.
     *
     * This helps avoid missing token errors pointing at the next closing bracket
     * rather than the line where the problem actually occurred.
     */
    private function firstNewlineBefore(FileLocation $location): FileLocation
    {
        $text = $location->getFile()->getText(0, $location->getOffset());
        $index = $location->getOffset() - 1;
        $lastNewline = null;

        while ($index >= 0) {
            $char = $text[$index];

            if (!Character::isWhitespace($char)) {
                return $lastNewline === null ? $location : $location->getFile()->location($lastNewline);
            }

            if (Character::isNewline($char)) {
                $lastNewline = $index;
            }
            $index--;
        }

        // If the document *only* contains whitespace before $location, always
        // return $location.

        return $location;
    }
}
