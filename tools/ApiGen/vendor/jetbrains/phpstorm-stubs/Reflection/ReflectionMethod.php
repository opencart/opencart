<?php

use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;
use JetBrains\PhpStorm\Pure;

/**
 * The <b>ReflectionMethod</b> class reports
 * information about a method.
 *
 * @link https://php.net/manual/en/class.reflectionmethod.php
 */
class ReflectionMethod extends ReflectionFunctionAbstract
{
    /**
     * @var string Name of the method, same as calling the {@see ReflectionMethod::getName()} method
     */
    #[Immutable]
    public $name;

    /**
     * @var string Fully qualified class name where this method was defined
     */
    #[Immutable]
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $class;

    /**
     * Indicates that the method is static.
     */
    public const IS_STATIC = 16;

    /**
     * Indicates that the method is public.
     */
    public const IS_PUBLIC = 1;

    /**
     * Indicates that the method is protected.
     */
    public const IS_PROTECTED = 2;

    /**
     * Indicates that the method is private.
     */
    public const IS_PRIVATE = 4;

    /**
     * Indicates that the method is abstract.
     */
    public const IS_ABSTRACT = 64;

    /**
     * Indicates that the method is final.
     */
    public const IS_FINAL = 32;

    /**
     * Constructs a ReflectionMethod
     *
     * <code>
     * $reflection = new ReflectionMethod(new Example(), 'method');
     * $reflection = new ReflectionMethod(Example::class, 'method');
     * $reflection = new ReflectionMethod('Example::method');
     * </code>
     *
     * @link https://php.net/manual/en/reflectionmethod.construct.php
     * @param string|object $objectOrMethod Classname, object
     * (instance of the class) that contains the method or class name and
     * method name delimited by ::.
     * @param string|null $method Name of the method if the first argument is a
     * classname or an object.
     * @throws ReflectionException if the class or method does not exist.
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'object|string'], default: '')] $objectOrMethod,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $method = null
    ) {}

    /**
     * Export a reflection method.
     *
     * @link https://php.net/manual/en/reflectionmethod.export.php
     * @param string $class The class name.
     * @param string $name The name of the method.
     * @param bool $return Setting to {@see true} will return the export,
     * as opposed to emitting it. Setting to {@see false} (the default) will do the
     * opposite.
     * @return string|null If the $return parameter is set to {@see true}, then
     * the export is returned as a string, otherwise {@see null} is returned.
     * @removed 8.0
     */
    #[Deprecated(since: '7.4')]
    public static function export($class, $name, $return = false) {}

    /**
     * Returns the string representation of the ReflectionMethod object.
     *
     * @link https://php.net/manual/en/reflectionmethod.tostring.php
     * @return string A string representation of this {@see ReflectionMethod} instance.
     */
    #[TentativeType]
    public function __toString(): string {}

    /**
     * Checks if method is public
     *
     * @link https://php.net/manual/en/reflectionmethod.ispublic.php
     * @return bool Returns {@see true} if the method is public, otherwise {@see false}
     */
    #[Pure]
    #[TentativeType]
    public function isPublic(): bool {}

    /**
     * Checks if method is private
     *
     * @link https://php.net/manual/en/reflectionmethod.isprivate.php
     * @return bool Returns {@see true} if the method is private, otherwise {@see false}
     */
    #[Pure]
    #[TentativeType]
    public function isPrivate(): bool {}

    /**
     * Checks if method is protected
     *
     * @link https://php.net/manual/en/reflectionmethod.isprotected.php
     * @return bool Returns {@see true} if the method is protected, otherwise {@see false}
     */
    #[Pure]
    #[TentativeType]
    public function isProtected(): bool {}

    /**
     * Checks if method is abstract
     *
     * @link https://php.net/manual/en/reflectionmethod.isabstract.php
     * @return bool Returns {@see true} if the method is abstract, otherwise {@see false}
     */
    #[Pure]
    #[TentativeType]
    public function isAbstract(): bool {}

    /**
     * Checks if method is final
     *
     * @link https://php.net/manual/en/reflectionmethod.isfinal.php
     * @return bool Returns {@see true} if the method is final, otherwise {@see false}
     */
    #[Pure]
    #[TentativeType]
    public function isFinal(): bool {}

    /**
     * Checks if method is static
     *
     * @link https://php.net/manual/en/reflectionmethod.isstatic.php
     * @return bool Returns {@see true} if the method is static, otherwise {@see false}
     */
    #[Pure]
    #[TentativeType]
    public function isStatic(): bool {}

    /**
     * Checks if method is a constructor
     *
     * @link https://php.net/manual/en/reflectionmethod.isconstructor.php
     * @return bool Returns {@see true} if the method is a constructor, otherwise {@see false}
     */
    #[Pure]
    #[TentativeType]
    public function isConstructor(): bool {}

    /**
     * Checks if method is a destructor
     *
     * @link https://php.net/manual/en/reflectionmethod.isdestructor.php
     * @return bool Returns {@see true} if the method is a destructor, otherwise {@see false}
     */
    #[Pure]
    #[TentativeType]
    public function isDestructor(): bool {}

    /**
     * Returns a dynamically created closure for the method
     *
     * @link https://php.net/manual/en/reflectionmethod.getclosure.php
     * @param object $object Forbidden for static methods, required for other methods or nothing.
     * @return Closure|null Returns {@see Closure} or {@see null} in case of an error.
     * @since 5.4
     */
    #[Pure]
    #[TentativeType]
    public function getClosure(
        #[PhpStormStubsElementAvailable(from: '5.3', to: '7.3')] $object,
        #[PhpStormStubsElementAvailable(from: '7.4')] #[LanguageLevelTypeAware(['8.0' => 'object|null'], default: '')] $object = null
    ): Closure {}

    /**
     * Gets the method modifiers
     *
     * @link https://php.net/manual/en/reflectionmethod.getmodifiers.php
     * @return int A numeric representation of the modifiers. The modifiers are
     * listed below. The actual meanings of these modifiers are described in the
     * predefined constants.
     *
     * ReflectionMethod modifiers:
     *
     *  - {@see ReflectionMethod::IS_STATIC} - Indicates that the method is static.
     *  - {@see ReflectionMethod::IS_PUBLIC} - Indicates that the method is public.
     *  - {@see ReflectionMethod::IS_PROTECTED} - Indicates that the method is protected.
     *  - {@see ReflectionMethod::IS_PRIVATE} - Indicates that the method is private.
     *  - {@see ReflectionMethod::IS_ABSTRACT} - Indicates that the method is abstract.
     *  - {@see ReflectionMethod::IS_FINAL} - Indicates that the method is final.
     */
    #[Pure]
    #[TentativeType]
    public function getModifiers(): int {}

    /**
     * Invokes a reflected method.
     *
     * @link https://php.net/manual/en/reflectionmethod.invoke.php
     * @param object|null $object The object to invoke the method on. For static
     * methods, pass {@see null} to this parameter.
     * @param mixed ...$args Zero or more parameters to be passed to the
     * method. It accepts a variable number of parameters which are passed to
     * the method.
     * @return mixed Returns the method result.
     * @throws ReflectionException if the object parameter does not contain an
     * instance of the class that this method was declared in or the method
     * invocation failed.
     */
    public function invoke($object, ...$args) {}

    /**
     * Invokes the reflected method and pass its arguments as array.
     *
     * @link https://php.net/manual/en/reflectionmethod.invokeargs.php
     * @param object|null $object The object to invoke the method on. In case
     * of static methods, you can pass {@see null} to this parameter.
     * @param array $args The parameters to be passed to the function, as an {@see array}.
     * @return mixed the method result.
     * @throws ReflectionException if the object parameter does not contain an
     * instance of the class that this method was declared in or the method
     * invocation failed.
     */
    #[TentativeType]
    public function invokeArgs(#[LanguageLevelTypeAware(['8.0' => 'object|null'], default: '')] $object, array $args): mixed {}

    /**
     * Gets declaring class for the reflected method.
     *
     * @link https://php.net/manual/en/reflectionmethod.getdeclaringclass.php
     * @return ReflectionClass A {@see ReflectionClass} object of the class that the
     * reflected method is part of.
     */
    #[Pure]
    #[TentativeType]
    public function getDeclaringClass(): ReflectionClass {}

    /**
     * Gets the method prototype (if there is one).
     *
     * @link https://php.net/manual/en/reflectionmethod.getprototype.php
     * @return ReflectionMethod A {@see ReflectionMethod} instance of the method prototype.
     * @throws ReflectionException if the method does not have a prototype
     */
    #[Pure]
    #[TentativeType]
    public function getPrototype(): ReflectionMethod {}

    /**
     * Set method accessibility
     *
     * @link https://php.net/manual/en/reflectionmethod.setaccessible.php
     * @param bool $accessible {@see true} to allow accessibility, or {@see false}
     * @return void No value is returned.
     * @since 5.3.2
     */
    #[PhpStormStubsElementAvailable(to: "8.0")]
    #[TentativeType]
    public function setAccessible(#[LanguageLevelTypeAware(['8.0' => 'bool'], default: '')] $accessible): void {}

    /**
     * Set method accessibility
     * This method is no-op starting from PHP 8.1
     *
     * @link https://php.net/manual/en/reflectionmethod.setaccessible.php
     * @param bool $accessible {@see true} to allow accessibility, or {@see false}
     * @return void No value is returned.
     */
    #[Pure]
    #[PhpStormStubsElementAvailable(from: "8.1")]
    #[TentativeType]
    public function setAccessible(bool $accessible): void {}

    #[PhpStormStubsElementAvailable(from: '8.2')]
    public function hasPrototype(): bool {}
}
