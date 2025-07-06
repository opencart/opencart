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

/**
 * @internal
 */
enum UnaryOperator
{
    case PLUS;
    case MINUS;
    case DIVIDE;
    case NOT;

    /**
     * The Sass syntax for this operator
     */
    public function getOperator(): string
    {
        return match ($this) {
            self::PLUS => '+',
            self::MINUS => '-',
            self::DIVIDE => '/',
            self::NOT => 'not',
        };
    }
}
