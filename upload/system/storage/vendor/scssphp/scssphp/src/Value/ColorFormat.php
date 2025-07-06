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

use JiriPudil\SealedClasses\Sealed;

/**
 * @internal
 */
#[Sealed(permits: [ColorFormatEnum::class, SpanColorFormat::class])]
interface ColorFormat
{
}
