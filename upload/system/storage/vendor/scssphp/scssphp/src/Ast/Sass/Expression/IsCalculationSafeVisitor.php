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
use ScssPhp\ScssPhp\Util\IterableUtil;
use ScssPhp\ScssPhp\Value\ListSeparator;
use ScssPhp\ScssPhp\Visitor\ExpressionVisitor;

/**
 * @template-implements ExpressionVisitor<bool>
 *
 * @internal
 */
final class IsCalculationSafeVisitor implements ExpressionVisitor
{
    public function visitBinaryOperationExpression(BinaryOperationExpression $node): bool
    {
        return \in_array($node->getOperator(), [BinaryOperator::TIMES, BinaryOperator::DIVIDED_BY, BinaryOperator::PLUS, BinaryOperator::MINUS], true) && ($node->getLeft()->accept($this) || $node->getRight()->accept($this));
    }

    public function visitBooleanExpression(BooleanExpression $node): bool
    {
        return false;
    }

    public function visitColorExpression(ColorExpression $node): bool
    {
        return false;
    }

    public function visitFunctionExpression(FunctionExpression $node): bool
    {
        return true;
    }

    public function visitInterpolatedFunctionExpression(InterpolatedFunctionExpression $node): bool
    {
        return true;
    }

    public function visitIfExpression(IfExpression $node): bool
    {
        return true;
    }

    public function visitListExpression(ListExpression $node): bool
    {
        return $node->getSeparator() === ListSeparator::SPACE && !$node->hasBrackets() && \count($node->getContents()) > 1 && IterableUtil::every($node->getContents(), fn(Expression $expression) => $expression->accept($this));
    }

    public function visitMapExpression(MapExpression $node): bool
    {
        return false;
    }

    public function visitNullExpression(NullExpression $node): bool
    {
        return false;
    }

    public function visitNumberExpression(NumberExpression $node): bool
    {
        return true;
    }

    public function visitParenthesizedExpression(ParenthesizedExpression $node): bool
    {
        return $node->getExpression()->accept($this);
    }

    public function visitSelectorExpression(SelectorExpression $node): bool
    {
        return false;
    }

    public function visitStringExpression(StringExpression $node): bool
    {
        if ($node->hasQuotes()) {
            return false;
        }

        /**
         * Exclude non-identifier constructs that are parsed as {@see StringExpression}s.
         * We could just check if they parse as valid identifiers, but this is
         * cheaper.
         */
        $text = $node->getText()->getInitialPlain();

        // !important
        return !str_starts_with($text, '!')
            // ID-style identifiers
            && !str_starts_with($text, '#')
            // Unicode ranges
            && ($text[1] ?? null) !== '+'
            // url()
            && ($text[3] ?? null) !== '(';
    }

    public function visitSupportsExpression(SupportsExpression $node): bool
    {
        return false;
    }

    public function visitUnaryOperationExpression(UnaryOperationExpression $node): bool
    {
        return false;
    }

    public function visitValueExpression(ValueExpression $node): bool
    {
        return false;
    }

    public function visitVariableExpression(VariableExpression $node): bool
    {
        return true;
    }
}
