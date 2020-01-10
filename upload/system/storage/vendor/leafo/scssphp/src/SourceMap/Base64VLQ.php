<?php
/**
 * SCSSPHP
 *
 * @copyright 2012-2015 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://leafo.github.io/scssphp
 */

namespace Leafo\ScssPhp\SourceMap;

use Leafo\ScssPhp\SourceMap\Base64;

/**
 * Base 64 VLQ
 *
 * Based on the Base 64 VLQ implementation in Closure Compiler:
 * https://github.com/google/closure-compiler/blob/master/src/com/google/debugging/sourcemap/Base64VLQ.java
 *
 * Copyright 2011 The Closure Compiler Authors.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author John Lenz <johnlenz@google.com>
 * @author Anthon Pang <anthon.pang@gmail.com>
 */
class Base64VLQ
{
    // A Base64 VLQ digit can represent 5 bits, so it is base-32.
    const VLQ_BASE_SHIFT = 5;

    // A mask of bits for a VLQ digit (11111), 31 decimal.
    const VLQ_BASE_MASK = 31;

    // The continuation bit is the 6th bit.
    const VLQ_CONTINUATION_BIT = 32;

    /**
     * Returns the VLQ encoded value.
     *
     * @param integer $value
     *
     * @return string
     */
    public static function encode($value)
    {
        $encoded = '';
        $vlq = self::toVLQSigned($value);

        do {
            $digit = $vlq & self::VLQ_BASE_MASK;
            $vlq >>= self::VLQ_BASE_SHIFT;

            if ($vlq > 0) {
                $digit |= self::VLQ_CONTINUATION_BIT;
            }

            $encoded .= Base64::encode($digit);
        } while ($vlq > 0);

        return $encoded;
    }

    /**
     * Decodes VLQValue.
     *
     * @param string $str
     * @param integer $index
     *
     * @return integer
     */
    public static function decode($str, &$index)
    {
        $result = 0;
        $shift = 0;

        do {
            $c = $str[$index++];
            $digit = Base64::decode($c);
            $continuation = ($digit & self::VLQ_CONTINUATION_BIT) != 0;
            $digit &= self::VLQ_BASE_MASK;
            $result = $result + ($digit << $shift);
            $shift = $shift + self::VLQ_BASE_SHIFT;
        } while ($continuation);

        return self::fromVLQSigned($result);
    }

    /**
     * Converts from a two-complement value to a value where the sign bit is
     * is placed in the least significant bit.  For example, as decimals:
     *   1 becomes 2 (10 binary), -1 becomes 3 (11 binary)
     *   2 becomes 4 (100 binary), -2 becomes 5 (101 binary)
     *
     * @param integer $value
     *
     * @return integer
     */
    private static function toVLQSigned($value)
    {
        if ($value < 0) {
            return ((-$value) << 1) + 1;
        }

        return ($value << 1) + 0;
    }

    /**
     * Converts to a two-complement value from a value where the sign bit is
     * is placed in the least significant bit.  For example, as decimals:
     *   2 (10 binary) becomes 1, 3 (11 binary) becomes -1
     *   4 (100 binary) becomes 2, 5 (101 binary) becomes -2
     *
     * @param integer $value
     *
     * @return integer
     */
    private static function fromVLQSigned($value)
    {
        $negate = ($value & 1) === 1;
        $value = $value >> 1;

        return $negate ? -$value : $value;
    }
}
