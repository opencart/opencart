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

use ScssPhp\ScssPhp\Ast\Css\CssMediaQuery;
use ScssPhp\ScssPhp\Exception\SassFormatException;

/**
 * A parser for `@media` queries.
 *
 * @internal
 */
final class MediaQueryParser extends Parser
{
    /**
     * @return list<CssMediaQuery>
     *
     * @throws SassFormatException when parsing fails
     */
    public function parse(): array
    {
        return $this->wrapSpanFormatException(function () {
            $queries = [];

            do {
                $this->whitespace();
                $queries[] = $this->mediaQuery();
                $this->whitespace();
            } while ($this->scanner->scanChar(','));
            $this->scanner->expectDone();

            return $queries;
        });
    }

    /**
     * Consumes a single media query.
     */
    private function mediaQuery(): CssMediaQuery
    {
        if ($this->scanner->peekChar() === '(') {
            $conditions = [$this->mediaInParens()];
            $this->whitespace();

            $conjunction = true;

            if ($this->scanIdentifier('and')) {
                $this->expectWhitespace();
                $conditions = array_merge($conditions, $this->mediaLogicSequence('and'));
            } elseif ($this->scanIdentifier('or')) {
                $this->expectWhitespace();
                $conjunction = false;
                $conditions = array_merge($conditions, $this->mediaLogicSequence('or'));
            }

            return CssMediaQuery::condition($conditions, $conjunction);
        }
        $modifier = null;
        $type = null;

        $identifier1 = $this->identifier();

        if (strtolower($identifier1) === 'not') {
            $this->expectWhitespace();

            if (!$this->lookingAtIdentifier()) {
                // For example, "@media not (...) {"
                return CssMediaQuery::condition(['(not ' . $this->mediaInParens() . ')']);
            }
        }

        $this->whitespace();

        if (!$this->lookingAtIdentifier()) {
            // For example, "@media screen {"
            return CssMediaQuery::type($identifier1);
        }

        $identifier2 = $this->identifier();

        if (strtolower($identifier2) === 'and') {
            $this->expectWhitespace();
            // For example, "@media screen and ..."
            $type = $identifier1;
        } else {
            $this->whitespace();
            $modifier = $identifier1;
            $type = $identifier2;

            if ($this->scanIdentifier('and')) {
                // For example, "@media only screen and ..."
                $this->expectWhitespace();
            } else {
                // For example, "@media only screen {"
                return CssMediaQuery::type($type, $modifier);
            }
        }

        // We've consumed either `IDENTIFIER "and"` or
        // `IDENTIFIER IDENTIFIER "and"`.

        if ($this->scanIdentifier('not')) {
            $this->expectWhitespace();
            // For example, "@media screen and not (...) {"
            return CssMediaQuery::type($type, $modifier, ['(not ' . $this->mediaInParens() . ')']);
        }

        return CssMediaQuery::type($type, $modifier, $this->mediaLogicSequence('and'));
    }

    /**
     * Consumes one or more `<media-in-parens>` expressions separated by
     * $operator and returns them.
     *
     * @return list<string>
     */
    private function mediaLogicSequence(string $operator): array
    {
        $result = [];
        while (true) {
            $result[] = $this->mediaInParens();
            $this->whitespace();

            if (!$this->scanIdentifier($operator)) {
                return $result;
            }
            $this->expectWhitespace();
        }
    }

    /**
     * Consumes a `<media-in-parens>` expression and returns it, parentheses
     * included.
     */
    private function mediaInParens(): string
    {
        $this->scanner->expectChar('(', 'media condition in parentheses');
        $result = '(' . $this->declarationValue() . ')';
        $this->scanner->expectChar(')');

        return $result;
    }
}
