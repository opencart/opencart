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
 * A negated condition.
 *
 * @internal
 */
final class SupportsNegation implements SupportsCondition
{
    /**
     * The condition that's been negated.
     */
    private readonly SupportsCondition $condition;

    private readonly FileSpan $span;

    public function __construct(SupportsCondition $condition, FileSpan $span)
    {
        $this->condition = $condition;
        $this->span = $span;
    }

    public function getCondition(): SupportsCondition
    {
        return $this->condition;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function __toString(): string
    {
        if ($this->condition instanceof SupportsNegation || $this->condition instanceof SupportsOperation) {
            return "not ($this->condition)";
        }

        return 'not ' . $this->condition;
    }
}
