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

use ScssPhp\ScssPhp\Ast\Sass\Expression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\StringExpression;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition;
use SourceSpan\FileSpan;

/**
 * A condition that selects for browsers where a given declaration is
 * supported.
 *
 * @internal
 */
final class SupportsDeclaration implements SupportsCondition
{
    /**
     * The name of the declaration being tested.
     */
    private readonly Expression $name;

    /**
     * The value of the declaration being tested.
     */
    private readonly Expression $value;

    private readonly FileSpan $span;

    public function __construct(Expression $name, Expression $value, FileSpan $span)
    {
        $this->name = $name;
        $this->value = $value;
        $this->span = $span;
    }

    public function getName(): Expression
    {
        return $this->name;
    }

    public function getValue(): Expression
    {
        return $this->value;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    /**
     * Returns whether this is a CSS Custom Property declaration.
     *
     * Note that this can return `false` for declarations that will ultimately be
     * serialized as custom properties if they aren't *parsed as* custom
     * properties, such as `#{--foo}: ...`.
     *
     * If this is `true`, then `value` will be a {@see StringExpression}.
     */
    public function isCustomProperty(): bool
    {
        return $this->name instanceof StringExpression && !$this->name->hasQuotes() && str_starts_with($this->name->getText()->getInitialPlain(), '--');
    }

    public function __toString(): string
    {
        return "($this->name: $this->value)";
    }
}
