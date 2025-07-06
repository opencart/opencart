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

/**
 * A modifiable version of {@see CssParentNode} for use in the evaluation step.
 *
 * @internal
 */
abstract class ModifiableCssParentNode extends ModifiableCssNode implements CssParentNode
{
    /**
     * @var list<ModifiableCssNode>
     */
    private array $children;

    /**
     * @param list<ModifiableCssNode> $children
     */
    public function __construct(array $children = [])
    {
        $this->children = $children;
    }

    /**
     * @return list<ModifiableCssNode>
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function isChildless(): bool
    {
        return false;
    }

    /**
     * Returns whether $this is equal to $other, ignoring their child nodes.
     */
    abstract public function equalsIgnoringChildren(ModifiableCssNode $other): bool;

    /**
     * Returns a copy of $this with an empty {@see children} list.
     *
     * This is *not* a deep copy. If other parts of this node are modifiable,
     * they are shared between the new and old nodes.
     */
    abstract public function copyWithoutChildren(): ModifiableCssParentNode;

    public function addChild(ModifiableCssNode $child): void
    {
        $child->setParent($this, \count($this->children));
        $this->children[] = $child;
    }

    /**
     * @internal
     */
    public function removeChildAt(int $index): void
    {
        array_splice($this->children, $index, 1);
    }

    /**
     * Destructively removes all elements from {@see children}.
     */
    public function clearChildren(): void
    {
        foreach ($this->children as $child) {
            $child->resetParentReferences();
        }
        $this->children = [];
    }
}
