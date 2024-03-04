<?php

use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

/**
 * The <b>ReflectionObject</b> class reports
 * information about an object.
 *
 * @link https://php.net/manual/en/class.reflectionobject.php
 */
class ReflectionObject extends ReflectionClass
{
    /**
     * Constructs a ReflectionObject
     *
     * @link https://php.net/manual/en/reflectionobject.construct.php
     * @param object $object An object instance.
     */
    public function __construct(#[LanguageLevelTypeAware(['8.0' => 'object'], default: '')] $object) {}

    /**
     * Export
     *
     * @link https://php.net/manual/en/reflectionobject.export.php
     * @param string $argument The reflection to export.
     * @param bool $return Setting to {@see true} will return the export,
     * as opposed to emitting it. Setting to {@see false} (the default) will do
     * the opposite.
     * @return string|null If the $return parameter is set to {@see true}, then
     * the export is returned as a string, otherwise {@see null} is returned.
     * @removed 8.0
     */
    #[Deprecated(since: '7.4')]
    public static function export($argument, $return = false) {}
}
