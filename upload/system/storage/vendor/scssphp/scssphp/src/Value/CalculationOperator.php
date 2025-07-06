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

namespace ScssPhp\ScssPhp\Value;

/**
 * An enumeration of possible operators for {@see CalculationOperation}.
 */
enum CalculationOperator
{
    case PLUS;
    case MINUS;
    case TIMES;
    case DIVIDED_BY;

    public function getOperator(): string
    {
        return match ($this) {
            self::PLUS => '+',
            self::MINUS => '-',
            self::TIMES => '*',
            self::DIVIDED_BY => '/',
        };
    }

    /**
     * The precedence of the operator
     *
     * An operator with higher precedence binds tighter.
     *
     * @internal
     */
    public function getPrecedence(): int
    {
        return match ($this) {
            self::PLUS, self::MINUS => 1,
            self::TIMES, self::DIVIDED_BY => 2,
        };
    }
}
