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

use ScssPhp\ScssPhp\Ast\Selector\SelectorList;

/**
 * A plain CSS style rule.
*  *
*  * This applies style declarations to elements that match a given selector.
*  * Note that this isn't *strictly* plain CSS, since {@see getSelector} may still
*  * contain placeholder selectors.
 *
 * @internal
 */
interface CssStyleRule extends CssParentNode
{
    /**
     * The selector for this rule.
     */
    public function getSelector(): SelectorList;

    /**
     * The selector for this rule, before any extensions were applied.
     */
    public function getOriginalSelector(): SelectorList;

    /**
     * Whether this style rule was originally defined in a plain CSS stylesheet.
     *
     * @internal
     */
    public function isFromPlainCss(): bool;
}
