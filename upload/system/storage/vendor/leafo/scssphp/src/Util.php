<?php
/**
 * SCSSPHP
 *
 * @copyright 2012-2018 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://leafo.github.io/scssphp
 */

namespace Leafo\ScssPhp;

use Leafo\ScssPhp\Base\Range;
use Leafo\ScssPhp\Exception\RangeException;

/**
 * Utilty functions
 *
 * @author Anthon Pang <anthon.pang@gmail.com>
 */
class Util
{
    /**
     * Asserts that `value` falls within `range` (inclusive), leaving
     * room for slight floating-point errors.
     *
     * @param string                    $name  The name of the value. Used in the error message.
     * @param \Leafo\ScssPhp\Base\Range $range Range of values.
     * @param array                     $value The value to check.
     * @param string                    $unit  The unit of the value. Used in error reporting.
     *
     * @return mixed `value` adjusted to fall within range, if it was outside by a floating-point margin.
     *
     * @throws \Leafo\ScssPhp\Exception\RangeException
     */
    public static function checkRange($name, Range $range, $value, $unit = '')
    {
        $val = $value[1];
        $grace = new Range(-0.00001, 0.00001);

        if ($range->includes($val)) {
            return $val;
        }

        if ($grace->includes($val - $range->first)) {
            return $range->first;
        }

        if ($grace->includes($val - $range->last)) {
            return $range->last;
        }

        throw new RangeException("$name {$val} must be between {$range->first} and {$range->last}$unit");
    }

    /**
     * Encode URI component
     *
     * @param string $string
     *
     * @return string
     */
    public static function encodeURIComponent($string)
    {
        $revert = ['%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')'];

        return strtr(rawurlencode($string), $revert);
    }
}
