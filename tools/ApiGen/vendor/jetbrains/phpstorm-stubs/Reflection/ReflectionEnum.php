<?php

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

/**
 * @link https://php.net/manual/en/class.reflectionenum.php
 * @since 8.1
 */
class ReflectionEnum extends ReflectionClass
{
    public function __construct(object|string $objectOrClass) {}

    /**
     * @param string $name
     * @return bool
     */
    public function hasCase(string $name): bool {}

    /**
     * @return ReflectionEnumPureCase[]|ReflectionEnumBackedCase[]
     */
    public function getCases(): array {}

    /**
     * @return ReflectionEnumPureCase|ReflectionEnumBackedCase
     * @throws ReflectionException If no found single reflection object for the corresponding case
     */
    public function getCase(string $name): ReflectionEnumUnitCase {}

    /**
     * @return bool
     */
    public function isBacked(): bool {}

    /**
     * @return ReflectionType|null
     */
    #[LanguageLevelTypeAware(['8.2' => 'null|ReflectionNamedType'], default: 'null|ReflectionType')]
    public function getBackingType() {}
}
