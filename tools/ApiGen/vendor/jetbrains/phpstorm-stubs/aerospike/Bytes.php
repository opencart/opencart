<?php
/**
 * Copyright 2013-2016 Aerospike, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category   Database
 * @author     Ronen Botzer <rbotzer@aerospike.com>
 * @copyright  Copyright 2013-2016 Aerospike, Inc.
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2
 * @link       https://github.com/aerospike/aerospike-client-php/blob/master/doc/README.md#handling-unsupported-types
 * @filesource
 */

namespace Aerospike;

/**
 * \Aerospike\Bytes is a utility for wrapping PHP strings containing
 * potentially harmful bytes such as \0. By wrapping the binary-string, the
 * Aerospike client will serialize the data into an as_bytes rather than an
 * as_string.
 * This ensures that the string will not get truncated or otherwise lose data.
 * The main difference is that strings in the Aerospike cluster can have a
 * secondary index built over them, and queries executed against the index,
 * while bytes data cannot.
 *
 * @package    Aerospike
 * @author     Ronen Botzer <rbotzer@aerospike.com>
 */
class Bytes implements \Serializable
{
    /**
     * The container for the binary-string
     * @var string
     */
    public $s;

    /**
     * Constructor for \Aerospike\Bytes class.
     *
     * @param string $bin_str a PHP binary-string such as gzdeflate() produces.
     */
    public function __construct($bin_str) {
        $this->s = $bin_str;
    }

    /**
     * Returns a serialized representation of the binary-string.
     * Called by serialize()
     *
     * @return string
     */
    public function serialize() {
        return $this->s;
    }

    /**
     * Re-wraps the binary-string when called by unserialize().
     *
     * @param string $bin_str a PHP binary-string. Called by unserialize().
     * @return string
     */
    public function unserialize($bin_str) {
        return $this->s = $bin_str;
    }

    /**
     * Returns the binary-string held in the \Aerospike\Bytes object.
     *
     * @return string
     */
    public function __toString() {
        return $this->s;
    }

    /**
     * Unwraps an \Aerospike\Bytes object, returning the binary-string inside.
     *
     * @param \Aerospike\Bytes $bytes_wrap
     * @return string
     */
    public static function unwrap(Bytes $bytes_wrap) {
        return $bytes_wrap->s;
    }
}
