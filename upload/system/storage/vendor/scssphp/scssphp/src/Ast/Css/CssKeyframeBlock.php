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
 * A block within a `@keyframes` rule.
 *
 * For example, `10% {opacity: 0.5}`.
 *
 * @internal
 */
interface CssKeyframeBlock extends CssParentNode
{
    /**
     * The selector for this block.
     *
     * @return CssValue<list<string>>
     */
    public function getSelector(): CssValue;
}
