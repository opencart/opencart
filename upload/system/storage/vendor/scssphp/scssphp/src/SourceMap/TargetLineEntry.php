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

namespace ScssPhp\ScssPhp\SourceMap;

/**
 * @internal
 */
final class TargetLineEntry
{
    /**
     * @param \ArrayObject<int, TargetEntry> $entries
     */
    public function __construct(
        public readonly int $line,
        public readonly \ArrayObject $entries,
    ) {
    }
}
