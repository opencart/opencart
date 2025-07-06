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

namespace ScssPhp\ScssPhp\Ast\Selector;

use ScssPhp\ScssPhp\Visitor\SelectorVisitor;
use SourceSpan\FileSpan;

/**
 * An attribute selector.
 *
 * This selects for elements with the given attribute, and optionally with a
 * value matching certain conditions as well.
 *
 * @internal
 */
final class AttributeSelector extends SimpleSelector
{
    /**
     * The name of the attribute being selected for.
     */
    private readonly QualifiedName $name;

    /**
     * The operator that defines the semantics of {@see value}.
     *
     * If this is `null`, this matches any element with the given property,
     * regardless of this value. It's `null` if and only if {@see value} is `null`.
     */
    private readonly ?AttributeOperator $op;

    /**
     * An assertion about the value of {@see name}.
     *
     * The precise semantics of this string are defined by {@see op}.
     *
     * If this is `null`, this matches any element with the given property,
     * regardless of this value. It's `null` if and only if {@see op} is `null`.
     */
    private readonly ?string $value;

    /**
     * The modifier which indicates how the attribute selector should be
     * processed.
     *
     * See for example [case-sensitivity][] modifiers.
     *
     * [case-sensitivity]: https://www.w3.org/TR/selectors-4/#attribute-case
     *
     * If {@see op} is `null`, this is always `null` as well.
     */
    private readonly ?string $modifier;

    /**
     * Creates an attribute selector that matches any element with a property of
     * the given name.
     */
    public static function create(QualifiedName $name, FileSpan $span): AttributeSelector
    {
        return new AttributeSelector($name, $span, null, null, null);
    }

    /**
     * Creates an attribute selector that matches an element with a property
     * named $name, whose value matches $value based on the semantics of $op.
     */
    public static function withOperator(QualifiedName $name, ?AttributeOperator $op, ?string $value, FileSpan $span, ?string $modifier = null): AttributeSelector
    {
        return new AttributeSelector($name, $span, $op, $value, $modifier);
    }

    private function __construct(QualifiedName $name, FileSpan $span, ?AttributeOperator $op, ?string $value, ?string $modifier)
    {
        $this->name = $name;
        $this->op = $op;
        $this->value = $value;
        $this->modifier = $modifier;
        parent::__construct($span);
    }

    public function getName(): QualifiedName
    {
        return $this->name;
    }

    public function getOp(): ?AttributeOperator
    {
        return $this->op;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getModifier(): ?string
    {
        return $this->modifier;
    }

    public function accept(SelectorVisitor $visitor)
    {
        return $visitor->visitAttributeSelector($this);
    }

    public function equals(object $other): bool
    {
        return $other instanceof AttributeSelector &&
            $other->name->equals($this->name) &&
            $other->op === $this->op &&
            $other->value === $this->value &&
            $other->modifier === $this->modifier;
    }
}
