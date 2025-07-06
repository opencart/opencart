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

namespace ScssPhp\ScssPhp\Util;

use ScssPhp\ScssPhp\Ast\Sass\ArgumentInvocation;
use ScssPhp\ScssPhp\Ast\Sass\Expression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\BinaryOperationExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\BinaryOperator;
use ScssPhp\ScssPhp\Ast\Sass\Expression\FunctionExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\InterpolatedFunctionExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\NumberExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\UnaryOperationExpression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\UnaryOperator;
use ScssPhp\ScssPhp\Visitor\ReplaceExpressionVisitor;

/**
 * A visitor that replaces constructs that can't be used in a calculation with
 * those that can.
 *
 * @internal
 */
final class MakeExpressionCalculationSafe extends ReplaceExpressionVisitor
{
    public function visitBinaryOperationExpression(BinaryOperationExpression $node): Expression
    {
        // `calc()` doesn't support `%` for modulo but Sass doesn't yet support the
        // `mod()` calculation function because there's no browser support, so we have
        // to work around it by wrapping the call in a Sass function.
        if ($node->getOperator() === BinaryOperator::MODULO) {
            return new FunctionExpression('max', new ArgumentInvocation([$node], [], $node->getSpan()), $node->getSpan(), 'math');
        }

        return parent::visitBinaryOperationExpression($node);
    }

    public function visitInterpolatedFunctionExpression(InterpolatedFunctionExpression $node): Expression
    {
        return $node;
    }

    public function visitUnaryOperationExpression(UnaryOperationExpression $node): Expression
    {
        switch ($node->getOperator()) {
            // `calc()` doesn't support unary operations.
            case UnaryOperator::PLUS:
                return $node->getOperand();

            case UnaryOperator::MINUS:
                return new BinaryOperationExpression(
                    BinaryOperator::TIMES,
                    new NumberExpression(-1, $node->getSpan()),
                    $node->getOperand()
                );

            // Other unary operations don't produce numbers, so keep them as-is to
            // give the user a more useful syntax error after serialization.
            default:
                return parent::visitUnaryOperationExpression($node);
        }
    }
}
