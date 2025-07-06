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

namespace ScssPhp\ScssPhp\Extend;

/**
 * Different modes in which extension can run.
 *
 * @internal
 */
enum ExtendMode
{
    /**
     * Normal mode, used with the `@extend` rule.
     *
     * This preserves existing selectors and extends each target individually.
     */
    case normal;

    /**
     * Replace mode, used by the `selector-replace()` function.
     *
     * This replaces existing selectors and requires every target to match to
     * extend a given compound selector.
     */
    case replace;

    /**
     * All-targets mode, used by the `selector-extend()` function.
     *
     * This preserves existing selectors but requires every target to match to
     * extend a given compound selector.
     */
    case allTargets;
}
