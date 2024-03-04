<?php

namespace Parle;

use JetBrains\PhpStorm\Immutable;

/**
 * Multistate lexer class.
 * Lexemes can be defined on the fly. If the particular lexer instance is meant to be used with Parle\RParser,
 * the token IDs need to be taken from there. Otherwise, arbitrary token IDs can be supplied.
 * Note, that Parle\Parser is not compatible with this lexer.
 *
 * @see RParser
 * @package Parle
 */
class RLexer
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
     * @link https://php.net/manual/en/parle-rlexer.advance.php
     * @return void
     */
    public function advance(): void {}

    /**
     * Finalize the lexer rule set
     *
     * Rules, previously added with Parle\RLexer::push() are finalized.
     * This method call has to be done after all the necessary rules was pushed.
     * The rule set becomes read only. The lexing can begin.
     *
     * @link https://php.net/manual/en/parle-rlexer.build.php
     * @see RLexer::push()
     * @return void
     */
    public function build(): void {}

    /**
     * Define token callback
     *
     * Define a callback to be invoked once lexer encounters a particular token.
     *
     * @see https://php.net/manual/en/parle-rlexer.callout.php
     * @param int $id Token id.
     * @param callable $callback Callable to be invoked. The callable doesn't receive any arguments and its return value is ignored.
     * @return void
     */
    public function callout(int $id, callable $callback): void {}

    /**
     * Pass the data for processing
     *
     * Consume the data for lexing.
     *
     * @see https://php.net/manual/en/parle-rlexer.consume.php
     * @param string $data Data to be lexed.
     * @return void
     */
    public function consume(string $data): void {}

    /**
     * Dump the state machine
     *
     * Dump the current state machine to stdout.
     *
     * @see https://php.net/manual/en/parle-rlexer.dump.php
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
     * Add a lexer rule
     *
     * Push a pattern for lexeme recognition.
     * A 'start state' and 'exit state' can be specified by using a suitable signature.
     *
     * @param string $regex Regular expression used for token matching.
     * @param int $id
     * Token id. If the lexer instance is meant to be used standalone, this can be an arbitrary number.
     * If the lexer instance is going to be passed to the parser, it has to be an id returned by Parle\RParser::tokenid().
     * @see RParser::tokenId()
     * @return void
     * @link https://php.net/manual/en/parle-rlexer.push.php
     */
    public function push(string $regex, int $id): void {}

    /**
     * Add a lexer rule
     *
     * Push a pattern for lexeme recognition.
     * A 'start state' and 'exit state' can be specified by using a suitable signature.
     *
     * @param string $state State name. If '*' is used as start state, then the rule is applied to all lexer states.
     * @param string $regex Regular expression used for token matching.
     * @param int $id
     * Token id. If the lexer instance is meant to be used standalone, this can be an arbitrary number.
     * If the lexer instance is going to be passed to the parser, it has to be an id returned by Parle\RParser::tokenid().
     * @see RParser::tokenId()
     * @param string $newState
     * New state name, after the rule was applied.
     * If '.' is specified as the exit state, then the lexer state is unchanged when that rule matches.
     * An exit state with '>' before the name means push. Use the signature without id for either continuation or to
     * start matching, when a continuation or recursion is required.
     * If '<' is specified as exit state, it means pop. In that case, the signature containing the id can be used to
     * identify the match. Note that even in the case an id is specified, the rule will finish first when all the
     * previous pushes popped.
     * @return void
     * @link https://php.net/manual/en/parle-rlexer.push.php
     */
    public function push(string $state, string $regex, int $id, string $newState): void {}

    /**
     * Add a lexer rule
     *
     * Push a pattern for lexeme recognition.
     * A 'start state' and 'exit state' can be specified by using a suitable signature.
     *
     * @param string $state State name. If '*' is used as start state, then the rule is applied to all lexer states.
     * @param string $regex Regular expression used for token matching.
     * @param string $newState
     * New state name, after the rule was applied.
     * If '.' is specified as the exit state, then the lexer state is unchanged when that rule matches.
     * An exit state with '>' before the name means push. Use the signature without id for either continuation or to
     * start matching, when a continuation or recursion is required.
     * If '<' is specified as exit state, it means pop. In that case, the signature containing the id can be used to
     * identify the match. Note that even in the case an id is specified, the rule will finish first when all the
     * previous pushes popped.
     * @return void
     * @link https://php.net/manual/en/parle-rlexer.push.php
     */
    public function push(string $state, string $regex, string $newState): void {}

    /**
     * Push a new start state
     * This lexer type can have more than one state machine.
     * This allows you to lex different tokens depending on context, thus allowing simple parsing to take place.
     * Once a state pushed, it can be used with a suitable Parle\RLexer::push() signature variant.
     *
     * @see RLexer::push()
     * @link https://php.net/manual/en/parle-rlexer.pushstate.php
     * @param string $state Name of the state.
     * @return int
     */
    public function pushState(string $state): int {}

    /**
     * Reset lexer
     *
     * Reset lexing optionally supplying the desired offset.
     *
     * @param int $pos Reset position.
     */
    public function reset(int $pos): void {}
}
