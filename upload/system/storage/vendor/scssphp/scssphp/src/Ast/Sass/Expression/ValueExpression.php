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
use ScssPhp\ScssPhp\Value\Value;
use ScssPhp\ScssPhp\Visitor\ExpressionVisitor;
use SourceSpan\FileSpan;

/**
 * An expression that directly embeds a value.
 *
 * This is never constructed by the parser. It's only used when ASTs are
 * constructed dynamically, as for the `call()` function.
 *
 * @internal
 */
final class ValueExpression implements Expression
{
    private readonly Value $value;

    private readonly FileSpan $span;

    public function __construct(Value $value, FileSpan $span)
    {
        $this->value = $value;
        $this->span = $span;
    }

    public function getValue(): Value
    {
        return $this->value;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(ExpressionVisitor $visitor)
    {
        return $visitor->visitValueExpression($this);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
