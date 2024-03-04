<?php

namespace JetBrains\PhpStorm\Internal;

use Attribute;

/**
 * For PhpStorm internal use only
 * @since 8.1
 * @internal
 */
#[Attribute(Attribute::TARGET_METHOD)]
class TentativeType {}
