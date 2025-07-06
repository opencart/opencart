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

use ScssPhp\ScssPhp\Ast\Sass\AtRootQuery;
use ScssPhp\ScssPhp\Exception\SassFormatException;

/**
 * A parser for `@at-root` queries.
 *
 * @internal
 */
final class AtRootQueryParser extends Parser
{
    /**
     * @throws SassFormatException
     */
    public function parse(): AtRootQuery
    {
        return $this->wrapSpanFormatException(function () {
            $this->scanner->expectChar('(');
            $this->whitespace();
            $include = $this->scanIdentifier('with');
            if (!$include) {
                $this->expectIdentifier('without', '"with" or "without"');
            }
            $this->whitespace();
            $this->scanner->expectChar(':');
            $this->whitespace();

            $atRules = [];

            do {
                $atRules[] = strtolower($this->identifier());
                $this->whitespace();
            } while ($this->lookingAtIdentifier());

            $this->scanner->expectChar(')');
            $this->scanner->expectDone();

            return AtRootQuery::create($atRules, $include);
        });
    }
}
