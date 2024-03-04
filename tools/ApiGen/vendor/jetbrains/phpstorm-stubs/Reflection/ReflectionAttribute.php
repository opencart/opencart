<?php

use JetBrains\PhpStorm\Pure;

/**
 * @since 8.0
 *
 * @template T of object
 */
class ReflectionAttribute implements Reflector
{
    /**
     * Indicates that the search for a suitable attribute should not be by
     * strict comparison, but by the inheritance chain.
     *
     * Used for the argument of flags of the "getAttribute" method.
     *
     * @since 8.0
     */
    public const IS_INSTANCEOF = 2;

    /**
     * ReflectionAttribute cannot be created explicitly.
     * @since 8.0
     */
    private function __construct() {}

    /**
     * Gets attribute name
     *
     * @return string The name of the attribute parameter.
     * @since 8.0
     */
    #[Pure]
    public function getName(): string {}

    /**
     * Returns the target of the attribute as a bit mask format.
     *
     * @return int
     * @since 8.0
     */
    #[Pure]
    public function getTarget(): int {}

    /**
     * Returns {@see true} if the attribute is repeated.
     *
     * @return bool
     * @since 8.0
     */
    #[Pure]
    public function isRepeated(): bool {}

    /**
     * Gets list of passed attribute's arguments.
     *
     * @return array
     * @since 8.0
     */
    #[Pure]
    public function getArguments(): array {}

    /**
     * Creates a new instance of the attribute with passed arguments
     *
     * @return T
     * @since 8.0
     */
    public function newInstance(): object {}

    /**
     * ReflectionAttribute cannot be cloned
     *
     * @return void
     * @since 8.0
     */
    private function __clone(): void {}

    public function __toString(): string {}

    public static function export() {}
}
