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

namespace ScssPhp\ScssPhp\Ast\Sass\Statement;

use ScssPhp\ScssPhp\Visitor\StatementSearchVisitor;

/**
 * A visitor for determining whether a {@see MixinRule} recursively contains a
 * {@see ContentRule}.
 *
 * @internal
 *
 * @extends StatementSearchVisitor<bool>
 */
final class HasContentVisitor extends StatementSearchVisitor
{
    public function visitContentRule(ContentRule $node): bool
    {
        return true;
    }
}
