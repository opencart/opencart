<?php

namespace Parle;

use JetBrains\PhpStorm\Immutable;

class RParser
{
    /* Constants */
    public const ACTION_ERROR = 0;
    public const ACTION_SHIFT = 1;
    public const ACTION_REDUCE = 2;
    public const ACTION_GOTO = 3;
    public const ACTION_ACCEPT = 4;
    public const ERROR_SYNTAX = 0;
    public const ERROR_NON_ASSOCIATIVE = 1;
    public const ERROR_UNKNOWN_TOKEN = 2;

    /* Properties */
    /**
     * @var int Current parser action that matches one of the action class constants, readonly.
     */
    #[Immutable]
    public $action = 0;

    /**
     * @var int Grammar rule id just processed in the reduce action. The value corresponds either to a token or to a production id. Readonly.
     */
    #[Immutable]
    public $reduceId = 0;

    /* Methods */
    /**
     * Process next parser rule.
     *
     * @link https://php.net/manual/en/parle-rparser.advance.php
     * @return void
     */
    public function advance(): void {}

    /**
     * Finalize the grammar rules
     *
     * Any tokens and grammar rules previously added are finalized.
     * The rule set becomes readonly and the parser is ready to start.
     *
     * @link https://php.net/manual/en/parle-rparser.build.php
     * @return void
     */
    public function build(): void {}

    /**
     * Consume the data for parsing.
     *
     * @link https://php.net/manual/en/parle-rparser.consume.php
     * @param string $data Data to be parsed.
     * @param Lexer $lexer A lexer object containing the lexing rules prepared for the particular grammar.
     * @return void
     */
    public function consume(string $data, Lexer $lexer): void {}

    /**
     * Dump the current grammar to stdout.
     *
     * @link https://php.net/manual/en/parle-rparser.dump.php
     * @return void
     */
    public function dump(): void {}

    /**
     * Retrieve the error information in case Parle\RParser::action() returned the error action.
     *
     * @link https://php.net/manual/en/parle-rparser.errorinfo.php
     * @return ErrorInfo
     */
    public function errorInfo(): ErrorInfo {}

    /**
     * Declare a terminal with left associativity.
     *
     * @link https://php.net/manual/en/parle-rparser.left.php
     * @param string $token Token name.
     * @return void
     */
    public function left(string $token): void {}

    /**
     * Declare a token with no associativity
     *
     * Declare a terminal, that cannot appear more than once in the row.
     *
     * @link https://php.net/manual/en/parle-rparser.nonassoc.php
     * @param string $token Token name.
     * @return void
     */
    public function nonassoc(string $token): void {}

    /**
     * Declare a precedence rule
     *
     * Declares a precedence rule for a fictitious terminal symbol.
     * This rule can be later used in the specific grammar rules.
     *
     * @link https://php.net/manual/en/parle-rparser.precedence.php
     * @param string $token
     * @return void
     */
    public function precedence(string $token): void {}

    /**
     * Push a grammar rule.
     *
     * The production id returned can be used later in the parsing process to identify the rule matched.
     *
     * @link https://php.net/manual/en/parle-rparser.push.php
     * @param string $name Rule name.
     * @param string $rule The rule to be added. The syntax is Bison compatible.
     * @return int Returns integer representing the rule index.
     */
    public function push(string $name, string $rule): int {}

    /**
     * Reset parser state using the given token id.
     *
     * @link https://php.net/manual/en/parle-rparser.reset.php
     * @param int $tokenId Token id.
     * @return void
     */
    public function reset(int $tokenId): void {}

    /**
     * Declare a token with right-associativity
     *
     * @link https://php.net/manual/en/parle-rparser.right.php
     * @param string $token Token name.
     * @return void
     */
    public function right(string $token): void {}

    /**
     * Retrieve a matching part of a rule
     *
     * Retrieve a part of the match by a rule.
     * This method is equivalent to the pseudo variable functionality in Bison.
     *
     * @link https://php.net/manual/en/parle-rparser.sigil.php
     * @param int $idx Match index, zero based.
     * @return string Returns a string with the matched part.
     */
    public function sigil(int $idx): string {}

    /**
     * Declare a token
     *
     * Declare a terminal to be used in the grammar.
     *
     * @link https://php.net/manual/en/parle-rparser.token.php
     * @param string $token Token name.
     * @return void
     */
    public function token(string $token): void {}

    /**
     * Get token id
     *
     * Retrieve the id of the named token.
     *
     * @link https://php.net/manual/en/parle-rparser.tokenid.php
     * @param string $token Name of the token as used in Parle\Parser::token().
     * @return int Returns integer representing the token id.
     * @see Parser::token()
     */
    public function tokenId(string $token): int {}

    /**
     * Trace the parser operation
     *
     * Retrieve the current parser operation description.
     * This can be especially useful for studying the parser and to optimize the grammar.
     *
     * @link https://php.net/manual/en/parle-rparser.trace.php
     * @return string Returns a string with the trace information.
     */
    public function trace(): string {}

    /**
     * Validate an input string.
     *
     * The string is parsed internally, thus this method is useful for the quick input validation.
     *
     * @link https://php.net/manual/en/parle-rparser.validate.php
     * @param string $data String to be validated.
     * @param RLexer $lexer A lexer object containing the lexing rules prepared for the particular grammar.
     * @return bool Returns boolean witnessing whether the input chimes or not with the defined rules.
     */
    public function validate(string $data, RLexer $lexer): bool {}
}
