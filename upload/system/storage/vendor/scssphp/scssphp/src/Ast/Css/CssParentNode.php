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
 * A {@see CssNode} that can have child statements.
 *
 * @internal
 */
interface CssParentNode extends CssNode
{
    /**
     * The child statements of this node.
     *
     * @return list<CssNode>
     */
    public function getChildren(): array;

    /**
     * Whether the rule has no children and should be emitted without curly
     * braces.
     *
     * This implies `children.isEmpty`, but the reverse is not true—for a rule
     * like `@foo {}`, {@see getChildren} is empty but {@see isChildless} is `false`.
     */
    public function isChildless(): bool;
}
