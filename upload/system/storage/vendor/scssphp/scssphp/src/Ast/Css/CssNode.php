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

use ScssPhp\ScssPhp\Ast\AstNode;
use ScssPhp\ScssPhp\Visitor\CssVisitor;

/**
 * A statement in a plain CSS syntax tree.
 *
 * @internal
 */
interface CssNode extends AstNode
{
    /**
     * The node that contains this, or `null` for the root {@see CssStylesheet} node.
     */
    public function getParent(): ?CssParentNode;

    /**
     * Whether this was generated from the last node in a nested Sass tree that
     * got flattened during evaluation.
     */
    public function isGroupEnd(): bool;

    /**
     * Calls the appropriate visit method on $visitor.
     *
     * @template T
     *
     * @param CssVisitor<T> $visitor
     *
     * @return T
     */
    public function accept(CssVisitor $visitor);

    /**
     * Whether this is invisible and won't be emitted to the compiled stylesheet.
     *
     * Note that this doesn't consider nodes that contain loud comments to be
     * invisible even though they're omitted in compressed mode.
     */
    public function isInvisible(): bool;

    /**
     * Whether this node would be invisible even if style rule selectors within it
     * didn't have bogus combinators.
     *
     * Note that this doesn't consider nodes that contain loud comments to be
     * invisible even though they're omitted in compressed mode.
     */
    public function isInvisibleOtherThanBogusCombinators(): bool;

    /**
     * Whether this node will be invisible when loud comments are stripped.
     */
    public function isInvisibleHidingComments(): bool;
}
