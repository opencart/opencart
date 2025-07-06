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
 * A target segment entry read from a source map
 *
 * @internal
 */
final class TargetEntry
{
    public function __construct(
        public readonly int $column,
        public readonly ?int $sourceUrlId = null,
        public readonly ?int $sourceLine = null,
        public readonly ?int $sourceColumn = null,
    ) {
    }
}
