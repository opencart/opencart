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

namespace ScssPhp\ScssPhp;

enum Syntax
{
    /**
     * The CSS-superset SCSS syntax.
     */
    case SCSS;

    /**
     * The whitespace-sensitive indented syntax.
     */
    case SASS;

    /**
     * The plain CSS syntax, which disallows special Sass features.
     */
    case CSS;

    public static function forPath(string $path): self
    {
        if (str_ends_with($path, '.sass')) {
            return self::SASS;
        }

        if (str_ends_with($path, '.css')) {
            return self::CSS;
        }

        return self::SCSS;
    }
}
