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
use ScssPhp\ScssPhp\Ast\Sass\Expression\FunctionExpression;

/**
 * @internal
 */
final class AstUtil
{
    /**
     * Converts $expression to an equivalent `calc()`.
     *
     * This assumes that $expression already returns a number. It's intended for
     * use in end-user messaging, and may not produce directly evaluable
     * expressions.
     */
    public static function expressionToCalc(Expression $expression): FunctionExpression
    {
        return new FunctionExpression(
            'calc',
            new ArgumentInvocation([$expression->accept(new MakeExpressionCalculationSafe())], [], $expression->getSpan()),
            $expression->getSpan()
        );
    }
}
