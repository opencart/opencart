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
 * A plain CSS comment.
 *
 * This is always a multi-line comment.
 *
 * @internal
 */
interface CssComment extends CssNode
{
    /**
     * The contents of this comment, including `/*` and `* /`.
     */
    public function getText(): string;

    /**
     * Whether this comment starts with `/*!` and so should be preserved even in
     * compressed mode.
     */
    public function isPreserved(): bool;
}
