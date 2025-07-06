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

namespace ScssPhp\ScssPhp\Value;

/**
 * An enum of list separator types.
 */
enum ListSeparator
{
    case COMMA;
    case SPACE;
    case SLASH;
    case UNDECIDED;

    public function getSeparator(): ?string
    {
        return match ($this) {
            self::COMMA => ',',
            self::SPACE => ' ',
            self::SLASH => '/',
            self::UNDECIDED => null,
        };
    }
}
