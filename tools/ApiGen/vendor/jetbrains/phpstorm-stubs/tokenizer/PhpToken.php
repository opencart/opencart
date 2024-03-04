<?php

/**
 * A class for working with PHP tokens, which is an alternative to
 * the {@see token_get_all()} function.
 *
 * @since 8.0
 */
class PhpToken implements Stringable
{
    /**
     * One of the T_* constants, or an integer < 256 representing a
     * single-char token.
     */
    public int $id;

    /**
     * The textual content of the token.
     */
    public string $text;

    /**
     * The starting line number (1-based) of the token.
     */
    public int $line;

    /**
     * The starting position (0-based) in the tokenized string.
     */
    public int $pos;

    /**
     * @param int $id An integer identifier
     * @param string $text Textual content
     * @param int $line Strating line
     * @param int $pos Straring position (line offset)
     */
    final public function __construct(int $id, string $text, int $line = -1, int $pos = -1) {}

    /**
     * Get the name of the token.
     *
     * @return string|null
     */
    public function getTokenName(): ?string {}

    /**
     * Same as {@see token_get_all()}, but returning array of {@see PhpToken}
     * or an instance of a child class.
     *
     * @param string $code An a PHP source code
     * @param int $flags
     * @return static[]
     */
    public static function tokenize(string $code, int $flags = 0): array {}

    /**
     * Whether the token has the given ID, the given text, or has an ID/text
     * part of the given array.
     *
     * @param int|string|array $kind
     * @return bool
     */
    public function is($kind): bool {}

    /**
     * Whether this token would be ignored by the PHP parser.
     *
     * @return bool
     */
    public function isIgnorable(): bool {}

    /**
     * {@inheritDoc}
     */
    public function __toString(): string {}
}
