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

use ScssPhp\ScssPhp\Serializer\Serializer;
use ScssPhp\ScssPhp\Visitor\ModifiableCssVisitor;

/**
 * A modifiable version of {@see CssNode}.
 *
 * Almost all CSS nodes are the modifiable classes under the covers. However,
 * modification should only be done within the evaluation step, so the
 * unmodifiable types are used elsewhere to enforce that constraint.
 *
 * @internal
 */
abstract class ModifiableCssNode implements CssNode
{
    private ?ModifiableCssParentNode $parent = null;

    /**
     * The index of `$this` in parent's children.
     *
     * This makes {@see remove} more efficient.
     */
    private ?int $indexInParent = null;

    private bool $groupEnd = false;

    public function getParent(): ?ModifiableCssParentNode
    {
        return $this->parent;
    }

    protected function setParent(ModifiableCssParentNode $parent, int $indexInParent): void
    {
        $this->parent = $parent;
        $this->indexInParent = $indexInParent;
    }

    public function isGroupEnd(): bool
    {
        return $this->groupEnd;
    }

    public function setGroupEnd(bool $groupEnd): void
    {
        $this->groupEnd = $groupEnd;
    }

    /**
     * Whether this node has a visible sibling after it.
     */
    public function hasFollowingSibling(): bool
    {
        $parent = $this->parent;

        if ($parent === null) {
            return false;
        }

        assert($this->indexInParent !== null);
        $siblings = $parent->getChildren();

        for ($i = $this->indexInParent + 1; $i < \count($siblings); $i++) {
            $sibling = $siblings[$i];

            if (!$sibling->isInvisible()) {
                return true;
            }
        }

        return false;
    }

    public function isInvisible(): bool
    {
        return $this->accept(new IsInvisibleVisitor(true, false));
    }

    public function isInvisibleOtherThanBogusCombinators(): bool
    {
        return $this->accept(new IsInvisibleVisitor(false, false));
    }

    public function isInvisibleHidingComments(): bool
    {
        return $this->accept(new IsInvisibleVisitor(true, true));
    }

    /**
     * Calls the appropriate visit method on $visitor.
     *
     * @template T
     *
     * @param ModifiableCssVisitor<T> $visitor
     *
     * @return T
     */
    abstract public function accept(ModifiableCssVisitor $visitor);

    /**
     * Removes $this from {@see parent}'s child list.
     *
     * @throws \LogicException if {@see parent} is `null`.
     */
    public function remove(): void
    {
        $parent = $this->parent;

        if ($parent === null) {
            throw new \LogicException("Can't remove a node without a parent.");
        }

        assert($this->indexInParent !== null);

        $parent->removeChildAt($this->indexInParent);
        $children = $parent->getChildren();

        for ($i = $this->indexInParent; $i < \count($children); $i++) {
            $child = $children[$i];
            assert($child->indexInParent !== null);
            $child->indexInParent = $child->indexInParent - 1;
        }
        $this->parent = null;
        $this->indexInParent = null;
    }

    /**
     * @@internal
     */
    protected function resetParentReferences(): void
    {
        $this->parent = null;
        $this->indexInParent = null;
    }

    public function __toString(): string
    {
        return Serializer::serialize($this, true)->css;
    }
}
