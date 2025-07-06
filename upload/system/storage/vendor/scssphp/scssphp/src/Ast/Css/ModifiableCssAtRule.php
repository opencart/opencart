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

namespace ScssPhp\ScssPhp\Ast\Css;

use ScssPhp\ScssPhp\Util\EquatableUtil;
use ScssPhp\ScssPhp\Visitor\ModifiableCssVisitor;
use SourceSpan\FileSpan;

/**
 * A modifiable version of {@see CssAtRule} for use in the evaluation step.
 *
 * @internal
 */
final class ModifiableCssAtRule extends ModifiableCssParentNode implements CssAtRule
{
    /**
     * @var CssValue<string>
     */
    private readonly CssValue $name;

    /**
     * @var CssValue<string>|null
     */
    private readonly ?CssValue $value;

    private readonly bool $childless;

    private readonly FileSpan $span;

    /**
     * @param CssValue<string> $name
     * @param CssValue<string>|null $value
     */
    public function __construct(CssValue $name, FileSpan $span, bool $childless = false, ?CssValue $value = null)
    {
        parent::__construct();

        $this->name = $name;
        $this->value = $value;
        $this->childless = $childless;
        $this->span = $span;
    }

    public function getName(): CssValue
    {
        return $this->name;
    }

    public function getValue(): ?CssValue
    {
        return $this->value;
    }

    public function isChildless(): bool
    {
        return $this->childless;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(ModifiableCssVisitor $visitor)
    {
        return $visitor->visitCssAtRule($this);
    }

    public function equalsIgnoringChildren(ModifiableCssNode $other): bool
    {
        return $other instanceof ModifiableCssAtRule && EquatableUtil::equals($this->name, $other->name) && EquatableUtil::equals($this->value, $other->value) && $this->childless === $other->childless;
    }

    public function copyWithoutChildren(): ModifiableCssAtRule
    {
        return new ModifiableCssAtRule($this->name, $this->span, $this->childless, $this->value);
    }

    public function addChild(ModifiableCssNode $child): void
    {
        if ($this->childless) {
            throw new \LogicException('Cannot add a child in a childless at-rule.');
        }

        parent::addChild($child);
    }
}
