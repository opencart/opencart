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

final class OutputStyle
{
    const EXPANDED = 'expanded';
    const COMPRESSED = 'compressed';

    /**
     * Converts a string to an output style.
     *
     * Using this method allows to write code which will support both
     * versions 1.12+ and 2.0 of Scssphp. In 2.0, OutputStyle will be
     * an enum instead of using string constants.
     *
     * @param string $string
     *
     * @return self::*
     */
    public static function fromString($string)
    {
        switch ($string) {
            case 'expanded':
                return self::EXPANDED;

            case 'compressed':
                return self::COMPRESSED;

            default:
                throw new \InvalidArgumentException('Invalid output style');
        }
    }

    /**
     * Converts an output style to a string supported by {@see OutputStyle::fromString()}.
     *
     * Using this method allows to write code which will support both
     * versions 1.12+ and 2.0 of Scssphp. In 2.0, OutputStyle will be
     * an enum instead of using string constants.
     * The returned string representation is guaranteed to be compatible
     * between 1.12 and 2.0.
     *
     * @param self::* $outputStyle
     *
     * @return string
     */
    public static function toString($outputStyle)
    {
        return $outputStyle;
    }
}
