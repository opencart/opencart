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
 * An unknown plain CSS at-rule.
 *
 * @internal
 */
interface CssAtRule extends CssParentNode
{
    /**
     * The name of this rule.
     *
     * @return CssValue<string>
     */
    public function getName(): CssValue;

    /**
     * The value of this rule.
     *
     * @return CssValue<string>|null
     */
    public function getValue(): ?CssValue;
}
