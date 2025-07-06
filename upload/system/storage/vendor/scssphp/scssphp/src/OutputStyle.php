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

enum OutputStyle: string
{
    case EXPANDED = 'expanded';
    case COMPRESSED = 'compressed';

    /**
     * Converts a string to an output style.
     *
     * Using this method allows to write code which will support both
     * versions 1.12+ and 2.0 of Scssphp. In 1.x, OutputStyle was using
     * string constants.
     */
    public static function fromString(string $string): OutputStyle
    {
        return match ($string) {
            'expanded' => self::EXPANDED,
            'compressed' => self::COMPRESSED,
            default => throw new \InvalidArgumentException('Invalid output style'),
        };
    }

    /**
     * Converts an output style to a string supported by {@see OutputStyle::fromString()}.
     *
     * Using this method allows to write code which will support both
     * versions 1.12+ and 2.0 of Scssphp.
     * The returned string representation is guaranteed to be compatible
     * between 1.12 and 2.0.
     */
    public static function toString(OutputStyle $outputStyle): string
    {
        return $outputStyle->value;
    }
}
