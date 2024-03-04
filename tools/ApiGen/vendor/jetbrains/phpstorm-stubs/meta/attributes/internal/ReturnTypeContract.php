<?php

namespace JetBrains\PhpStorm\Internal;

use Attribute;

/**
 * For PhpStorm internal use only
 * @since 8.0
 * @internal
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
class ReturnTypeContract
{
    public function __construct(
        string $true = "",
        string $false = "",
        string $exists = "",
        string $notExists = ""
    ) {}
}
