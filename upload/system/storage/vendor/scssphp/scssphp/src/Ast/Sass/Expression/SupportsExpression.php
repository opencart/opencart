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
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition;
use ScssPhp\ScssPhp\Visitor\ExpressionVisitor;
use SourceSpan\FileSpan;

/**
 * An expression-level `@supports` condition.
 *
 * This appears only in the modifiers that come after a plain-CSS `@import`. It
 * doesn't include the function name wrapping the condition.
 *
 * @internal
 */
final class SupportsExpression implements Expression
{
    private readonly SupportsCondition $condition;

    public function __construct(SupportsCondition $condition)
    {
        $this->condition = $condition;
    }

    public function getCondition(): SupportsCondition
    {
        return $this->condition;
    }

    public function getSpan(): FileSpan
    {
        return $this->condition->getSpan();
    }

    public function accept(ExpressionVisitor $visitor)
    {
        return $visitor->visitSupportsExpression($this);
    }

    public function __toString(): string
    {
        return (string) $this->condition;
    }
}
