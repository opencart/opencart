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

namespace ScssPhp\ScssPhp\Visitor;

use ScssPhp\ScssPhp\Ast\Sass\ArgumentInvocation;
use ScssPhp\ScssPhp\Ast\Sass\Expression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\BinaryOperationExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\BooleanExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\ColorExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\FunctionExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\IfExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\InterpolatedFunctionExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\ListExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\MapExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\NullExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\NumberExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\ParenthesizedExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\SelectorExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\StringExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\SupportsExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\UnaryOperationExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\ValueExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\VariableExpression;
use ScssPhp\ScssPhp\Ast\Sass\Interpolation;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsDeclaration;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsInterpolation;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsNegation;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition\SupportsOperation;

/**
 * A visitor that recursively traverses each expression in a SassScript AST and
 * replaces its contents with the values returned by nested recursion.
 *
 * In addition to the methods from {@see ExpressionVisitor}, this has more general
 * protected methods that can be overridden to add behavior for a wide variety
 * of AST nodes:
 *
 * * {@see visitArgumentInvocation}
 * * {@see visitSupportsCondition}
 * * {@see visitInterpolation}
 *
 * @template-implements ExpressionVisitor<Expression>
 *
 * @internal
 */
abstract class ReplaceExpressionVisitor implements ExpressionVisitor
{
    public function visitBinaryOperationExpression(BinaryOperationExpression $node): Expression
    {
        return new BinaryOperationExpression($node->getOperator(), $node->getLeft()->accept($this), $node->getRight()->accept($this));
    }

    public function visitBooleanExpression(BooleanExpression $node): Expression
    {
        return $node;
    }

    public function visitColorExpression(ColorExpression $node): Expression
    {
        return $node;
    }

    public function visitFunctionExpression(FunctionExpression $node): Expression
    {
        return new FunctionExpression(
            $node->getOriginalName(),
            $this->visitArgumentInvocation($node->getArguments()),
            $node->getSpan(),
            $node->getNamespace()
        );
    }

    public function visitInterpolatedFunctionExpression(InterpolatedFunctionExpression $node): Expression
    {
        return new InterpolatedFunctionExpression(
            $this->visitInterpolation($node->getName()),
            $this->visitArgumentInvocation($node->getArguments()),
            $node->getSpan()
        );
    }

    public function visitIfExpression(IfExpression $node): Expression
    {
        return new IfExpression($this->visitArgumentInvocation($node->getArguments()), $node->getSpan());
    }

    public function visitListExpression(ListExpression $node): Expression
    {
        return new ListExpression(
            array_map(fn(Expression $item) => $item->accept($this), $node->getContents()),
            $node->getSeparator(),
            $node->getSpan(),
            $node->hasBrackets()
        );
    }

    public function visitMapExpression(MapExpression $node): Expression
    {
        return new MapExpression(
            array_map(fn(array $pair) => [$pair[0]->accept($this), $pair[1]->accept($this)], $node->getPairs()),
            $node->getSpan()
        );
    }

    public function visitNullExpression(NullExpression $node): Expression
    {
        return $node;
    }

    public function visitNumberExpression(NumberExpression $node): Expression
    {
        return $node;
    }

    public function visitParenthesizedExpression(ParenthesizedExpression $node): Expression
    {
        return new ParenthesizedExpression($node->getExpression()->accept($this), $node->getSpan());
    }

    public function visitSelectorExpression(SelectorExpression $node): Expression
    {
        return $node;
    }

    public function visitStringExpression(StringExpression $node): Expression
    {
        return new StringExpression($this->visitInterpolation($node->getText()), $node->hasQuotes());
    }

    public function visitSupportsExpression(SupportsExpression $node): Expression
    {
        return new SupportsExpression($this->visitSupportsCondition($node->getCondition()));
    }

    public function visitUnaryOperationExpression(UnaryOperationExpression $node): Expression
    {
        return new UnaryOperationExpression($node->getOperator(), $node->getOperand()->accept($this), $node->getSpan());
    }

    public function visitValueExpression(ValueExpression $node): Expression
    {
        return $node;
    }

    public function visitVariableExpression(VariableExpression $node): Expression
    {
        return $node;
    }

    /**
     * Replaces each expression in an invocation.
     *
     * The default implementation of the visit methods calls this to replace any
     * argument invocation in an expression.
     */
    protected function visitArgumentInvocation(ArgumentInvocation $invocation): ArgumentInvocation
    {
        return new ArgumentInvocation(
            array_map(fn(Expression $expression) => $expression->accept($this), $invocation->getPositional()),
            array_map(fn(Expression $expression) => $expression->accept($this), $invocation->getNamed()),
            $invocation->getSpan(),
            $invocation->getRest()?->accept($this),
            $invocation->getKeywordRest()?->accept($this)
        );
    }

    /**
     * Replaces each expression in $condition.
     *
     * The default implementation of the visit methods call this to visit any
     * {@see SupportsCondition} they encounter.
     */
    protected function visitSupportsCondition(SupportsCondition $condition): SupportsCondition
    {
        if ($condition instanceof SupportsOperation) {
            return new SupportsOperation(
                $this->visitSupportsCondition($condition->getLeft()),
                $this->visitSupportsCondition($condition->getRight()),
                $condition->getOperator(),
                $condition->getSpan()
            );
        }

        if ($condition instanceof SupportsNegation) {
            return new SupportsNegation($this->visitSupportsCondition($condition->getCondition()), $condition->getSpan());
        }

        if ($condition instanceof SupportsInterpolation) {
            return new SupportsInterpolation($condition->getExpression()->accept($this), $condition->getSpan());
        }

        if ($condition instanceof SupportsDeclaration) {
            return new SupportsDeclaration($condition->getName()->accept($this), $condition->getValue()->accept($this), $condition->getSpan());
        }

        throw new \UnexpectedValueException('BUG: Unknown SupportsCondition ' . get_class($condition));
    }

    protected function visitInterpolation(Interpolation $interpolation): Interpolation
    {
        return new Interpolation(array_map(function ($node) {
            return $node instanceof Expression ? $node->accept($this) : $node;
        }, $interpolation->getContents()), $interpolation->getSpan());
    }
}
