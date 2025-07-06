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

namespace ScssPhp\ScssPhp\Ast\Sass\Expression;

use ScssPhp\ScssPhp\Ast\Sass\Expression;
use ScssPhp\ScssPhp\Visitor\ExpressionVisitor;
use SourceSpan\FileSpan;

/**
 * An expression wrapped in parentheses.
 *
 * @internal
 */
final class ParenthesizedExpression implements Expression
{
    private readonly Expression $expression;

    private readonly FileSpan $span;

    public function __construct(Expression $expression, FileSpan $span)
    {
        $this->expression = $expression;
        $this->span = $span;
    }

    public function getExpression(): Expression
    {
        return $this->expression;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(ExpressionVisitor $visitor)
    {
        return $visitor->visitParenthesizedExpression($this);
    }

    public function __toString(): string
    {
        return '(' . $this->expression . ')';
    }
}
