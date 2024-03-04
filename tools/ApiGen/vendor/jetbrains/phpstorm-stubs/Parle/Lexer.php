<?php

namespace Parle;

use JetBrains\PhpStorm\Immutable;

/**
 * Single state lexer class.
 *
 * Lexemes can be defined on the fly.
 * If the particular lexer instance is meant to be used with Parle\Parser, the token IDs need to be taken from there.
 * Otherwise, arbitrary token IDs can be supplied. This lexer can give a certain performance advantage over Parle\RLexer,
 * if no multiple states are required. Note, that Parle\RParser is not compatible with this lexer.
 *
 * @see Parser
 * @package Parle
 */
class Lexer
{
    /* Constants */
    public const ICASE = 1;
    public const DOT_NOT_LF = 2;
    public const DOT_NOT_CRLF = 4;
    public const SKIP_WS = 8;
    public const MATCH_ZERO_LEN = 16;

    /* Properties */
    /**
     * @var bool Start of input flag.
     */
    public $bol = false;

    /**
     * @var int Lexer flags.
     */
    public $flags = 0;

    /**
     * @var int Current lexer state, readonly.
     */
    #[Immutable]
    public $state = 0;

    /**
     * @var int Position of the latest token match, readonly.
     */
    #[Immutable]
    public $marker = 0;

    /**
     * @var int Current input offset, readonly.
     */
    #[Immutable]
    public $cursor = 0;

    /* Methods */

    /**
     * Processes the next rule and prepares the resulting token data.
     *
     * @link https://php.net/manual/en/parle-lexer.advance.php
     * @return void
     */
    public function advance(): void {}

    /**
     * Finalize the lexer rule set
     *
     * Rules, previously added with Parle\Lexer::push() are finalized.
     * This method call has to be done after all the necessary rules was pushed.
     * The rule set becomes read only. The lexing can begin.
     *
     * @see Lexer::push()
     * @link https://php.net/manual/en/parle-lexer.build.php
     * @return void
     */
    public function build(): void {}

    /**
     * Define token callback
     *
     * Define a callback to be invoked once lexer encounters a particular token.
     *
     * @link https://php.net/manual/en/parle-lexer.callout.php
     * @param int $id Token id.
     * @param callable $callback Callable to be invoked. The callable doesn't receive any arguments and its return value is ignored.
     * @return void
     */
    public function callout(int $id, callable $callback): void {}

    /**
     * Consume the data for lexing.
     *
     * @link https://php.net/manual/en/parle-lexer.consume.php
     * @param string $data Data to be lexed.
     * @return void
     */
    public function consume(string $data): void {}

    /**
     * Dump the current state machine to stdout.
     *
     * @link https://php.net/manual/en/parle-lexer.dump.php
     * @return void
     */
    public function dump(): void {}

    /**
     * Retrieve the current token.
     *
     * @return Token
     */
    public function getToken(): Token {}

    /**
     * Insert a regex macro, that can be later used as a shortcut and included in other regular expressions.
     *
     * @see https://php.net/manual/en/parle-lexer.insertmacro.php
     * @param string $name Name of the macros.
     * @param string $regex Regular expression.
     * @return void
     */
    public function insertMacro(string $name, string $regex): void {}

    /**
     * Push a pattern for lexeme recognition.
     *
     * @param string $regex Regular expression used for token matching.
     * @param int $id Token id. If the lexer instance is meant to be used standalone, this can be an arbitrary number. If the lexer instance is going to be passed to the parser, it has to be an id returned by Parle\Parser::tokenid().
     * @return void
     */
    public function push(string $regex, int $id): void {}

    /**
     * Reset lexing optionally supplying the desired offset.
     *
     * @param int $pos Reset position.
     */
    public function reset(int $pos): void {}
}
