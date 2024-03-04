<?php

use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Pure;

/**
 * The ReflectionReference class provides information about a reference.
 *
 * Note: Unlike the description in the documentation, the class itself is not final.
 *
 * @link https://www.php.net/manual/en/class.reflectionreference.php
 * @since 7.4
 */
class ReflectionReference
{
    /**
     * ReflectionReference cannot be created explicitly.
     */
    private function __construct() {}

    /**
     * Returns ReflectionReference if array element is a reference, {@see null} otherwise
     *
     * @link https://php.net/manual/en/reflectionreference.fromarrayelement.php
     * @param array $array The array which contains the potential reference.
     * @param int|string $key The key; either an integer or a string.
     * @return self|null
     */
    public static function fromArrayElement(
        array $array,
        #[LanguageLevelTypeAware(['8.0' => 'string|int'], default: '')] $key
    ): ?ReflectionReference {}

    /**
     * Returns unique identifier for the reference. The return value format is unspecified
     *
     * @link https://php.net/manual/en/reflectionreference.getid.php
     * @return int|string Returns an integer or string of unspecified format.
     */
    #[Pure]
    public function getId(): string {}

    /**
     * ReflectionReference cannot be cloned
     *
     * @return void
     */
    private function __clone(): void {}
}
