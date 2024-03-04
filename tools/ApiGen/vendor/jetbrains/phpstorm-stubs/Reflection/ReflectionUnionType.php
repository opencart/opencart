<?php

use JetBrains\PhpStorm\Pure;

/**
 * @since 8.0
 */
class ReflectionUnionType extends ReflectionType
{
    /**
     * Get list of named types of union type
     *
     * @return ReflectionNamedType[]
     */
    #[Pure]
    public function getTypes(): array {}
}
