<?php

namespace JetBrains\PhpStorm;

use Attribute;

/**
 * Mark a property (or all class properties in the case of a class) as immutable.
 * By default, an IDE highlights write accesses on such properties if they are located outside a constructor (this scope is customizable, see below).
 *
 * You can provide a custom allowed write scope by using the following values:
 * <ul>
 * <li>{@link Immutable::CONSTRUCTOR_WRITE_SCOPE}: write is allowed only in containing class constructor (default choice)</li>
 * <li>{@link Immutable::PRIVATE_WRITE_SCOPE}: write is allowed only in places where the property would be accessible if it had 'private' visibility modifier</li>
 * <li>{@link Immutable::PROTECTED_WRITE_SCOPE}: write is allowed only in places where the property would be accessible if it had 'protected' visibility modifier</li>
 * </ul>
 * @since 8.0
 */
#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_CLASS)]
class Immutable
{
    public const CONSTRUCTOR_WRITE_SCOPE = "constructor";
    public const PRIVATE_WRITE_SCOPE = "private";
    public const PROTECTED_WRITE_SCOPE = "protected";

    public function __construct(#[ExpectedValues(valuesFromClass: Immutable::class)] $allowedWriteScope = self::CONSTRUCTOR_WRITE_SCOPE) {}
}
