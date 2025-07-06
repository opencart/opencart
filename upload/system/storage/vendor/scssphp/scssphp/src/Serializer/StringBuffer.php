<?php

/**
 * SCSSPHP
 *
 * @copyright 2018-2020 Anthon Pang
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp\Serializer;

/**
 * @internal
 */
interface StringBuffer extends \Stringable
{
    /**
     * Returns the length of the content that has been accumulated so far.
     */
    public function getLength(): int;

    public function write(string $string): void;

    /**
     * Writes a single char to the buffer.
     */
    public function writeChar(string $char): void;
}
