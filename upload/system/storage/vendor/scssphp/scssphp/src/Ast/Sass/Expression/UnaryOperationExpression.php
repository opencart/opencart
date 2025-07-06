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
 * A unary operator, as in `+$var` or `not fn()`.
 *
 * @internal
 */
final class UnaryOperationExpression implements Expression
{
    private readonly UnaryOperator $operator;

    private readonly Expression $operand;

    private readonly FileSpan $span;

    public function __construct(UnaryOperator $operator, Expression $operand, FileSpan $span)
    {
        $this->operator = $operator;
        $this->operand = $operand;
        $this->span = $span;
    }

    public function getOperator(): UnaryOperator
    {
        return $this->operator;
    }

    public function getOperand(): Expression
    {
        return $this->operand;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(ExpressionVisitor $visitor)
    {
        return $visitor->visitUnaryOperationExpression($this);
    }

    public function __toString(): string
    {
        $buffer = $this->operator->getOperator();
        if ($this->operator === UnaryOperator::NOT) {
            $buffer .= ' ';
        }

        $needsParens = $this->operand instanceof BinaryOperationExpression
            || $this->operand instanceof UnaryOperationExpression
            || ($this->operand instanceof ListExpression && !$this->operand->hasBrackets() && \count($this->operand->getContents()) > 1);

        if ($needsParens) {
            $buffer .= '(';
        }

        $buffer .= $this->operand;

        if ($needsParens) {
            $buffer .= ')';
        }

        return $buffer;
    }
}
