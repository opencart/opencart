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

namespace ScssPhp\ScssPhp\Ast\Sass\Statement;

use ScssPhp\ScssPhp\Ast\Sass\Expression;
use ScssPhp\ScssPhp\Ast\Sass\Expression\StringExpression;
use ScssPhp\ScssPhp\Ast\Sass\Interpolation;
use ScssPhp\ScssPhp\Ast\Sass\Statement;
use ScssPhp\ScssPhp\Visitor\StatementVisitor;
use SourceSpan\FileSpan;

/**
 * A declaration (that is, a `name: value` pair).
 *
 * @extends ParentStatement<Statement[]|null>
 *
 * @internal
 */
final class Declaration extends ParentStatement
{
    private readonly Interpolation $name;

    /**
     * The value of this declaration.
     *
     * If {@see getChildren} is `null`, this is never `null`. Otherwise, it may or may
     * not be `null`.
     */
    private readonly ?Expression $value;

    private readonly FileSpan $span;

    /**
     * @param Statement[]|null $children
     */
    private function __construct(Interpolation $name, ?Expression $value, FileSpan $span, ?array $children = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->span = $span;
        parent::__construct($children);
    }

    public static function create(Interpolation $name, Expression $value, FileSpan $span): self
    {
        return new self($name, $value, $span);
    }

    /**
     * @param Statement[] $children
     */
    public static function nested(Interpolation $name, array $children, FileSpan $span, ?Expression $value = null): self
    {
        return new self($name, $value, $span, $children);
    }

    public function getName(): Interpolation
    {
        return $this->name;
    }

    public function getValue(): ?Expression
    {
        return $this->value;
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
        return str_starts_with($this->name->getInitialPlain(), '--');
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitDeclaration($this);
    }

    public function __toString(): string
    {
        $buffer = $this->name . ':';

        if ($this->value !== null) {
            if (!$this->isCustomProperty()) {
                $buffer .= ' ';
            }
            $buffer .= $this->value;
        }

        $children = $this->getChildren();

        if ($children === null) {
            return $buffer . ';';
        }

        return $buffer . '{' . implode(' ', $children) . '}';
    }
}
