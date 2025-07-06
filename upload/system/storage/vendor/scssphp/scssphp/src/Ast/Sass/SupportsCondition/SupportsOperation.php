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

namespace ScssPhp\ScssPhp\Ast\Sass\SupportsCondition;

use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition;
use SourceSpan\FileSpan;

/**
 * An operation defining the relationship between two conditions.
 *
 * @internal
 */
final class SupportsOperation implements SupportsCondition
{
    /**
     * The left-hand operand.
     */
    private readonly SupportsCondition $left;

    /**
     * The right-hand operand.
     */
    private readonly SupportsCondition $right;

    private readonly string $operator;

    private readonly FileSpan $span;

    public function __construct(SupportsCondition $left, SupportsCondition $right, string $operator, FileSpan $span)
    {
        $this->left = $left;
        $this->right = $right;
        $this->operator = $operator;
        $this->span = $span;
    }

    public function getLeft(): SupportsCondition
    {
        return $this->left;
    }

    public function getRight(): SupportsCondition
    {
        return $this->right;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function __toString(): string
    {
        return $this->parenthesize($this->left) . ' ' . $this->operator . ' ' . $this->parenthesize($this->right);
    }

    private function parenthesize(SupportsCondition $condition): string
    {
        if ($condition instanceof SupportsNegation || $condition instanceof SupportsOperation && $condition->operator === $this->operator) {
            return "($condition)";
        }

        return (string) $condition;
    }
}
