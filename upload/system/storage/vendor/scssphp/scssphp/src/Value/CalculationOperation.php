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

use ScssPhp\ScssPhp\Serializer\Serializer;
use ScssPhp\ScssPhp\Util\Equatable;

/**
 * A binary operation that can appear in a {@see SassCalculation}.
 */
final class CalculationOperation implements Equatable, \Stringable
{
    private readonly CalculationOperator $operator;

    /**
     * The left-hand operand.
     *
     * This is either a {@see SassNumber}, a {@see SassCalculation}, an unquoted
     * {@see SassString}, or a {@see CalculationOperation}.
     */
    private readonly object $left;

    /**
     * The right-hand operand.
     *
     * This is either a {@see SassNumber}, a {@see SassCalculation}, an unquoted
     * {@see SassString}, or a {@see CalculationOperation}.
     */
    private readonly object $right;

    public function __construct(CalculationOperator $operator, object $left, object $right)
    {
        $this->operator = $operator;
        $this->left = $left;
        $this->right = $right;
    }

    public function getOperator(): CalculationOperator
    {
        return $this->operator;
    }

    public function getLeft(): object
    {
        return $this->left;
    }

    public function getRight(): object
    {
        return $this->right;
    }

    public function equals(object $other): bool
    {
        assert($this->left instanceof Equatable);
        assert($this->right instanceof Equatable);

        return $other instanceof CalculationOperation && $this->operator === $other->operator && $this->left->equals($other->left) && $this->right->equals($other->right);
    }

    public function __toString(): string
    {
        $parenthesized = Serializer::serializeValue(SassCalculation::unsimplified('', [$this]), true);

        return substr($parenthesized, 1, \strlen($parenthesized) - 2);
    }
}
