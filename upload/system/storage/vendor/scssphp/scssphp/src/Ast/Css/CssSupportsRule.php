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
 * A plain CSS `@supports` rule.
 *
 * @internal
 */
interface CssSupportsRule extends CssParentNode
{
    /**
     * The supports condition.
     *
     * @return CssValue<string>
     */
    public function getCondition(): CssValue;
}
