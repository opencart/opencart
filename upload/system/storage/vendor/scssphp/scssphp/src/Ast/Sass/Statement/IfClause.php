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

use ScssPhp\ScssPhp\Ast\Sass\Expression;
use ScssPhp\ScssPhp\Ast\Sass\Statement;

/**
 * An `@if` or `@else if` clause in an `@if` rule.
 *
 * @internal
 */
final class IfClause extends IfRuleClause
{
    private readonly Expression $expression;

    /**
     * @param Statement[] $children
     */
    public function __construct(Expression $expression, array $children)
    {
        $this->expression = $expression;
        parent::__construct($children);
    }

    public function getExpression(): Expression
    {
        return $this->expression;
    }
}
