<?php

use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;
use JetBrains\PhpStorm\Pure;

/**
 * A parent class to <b>ReflectionFunction</b>, read its
 * description for details.
 *
 * @link https://php.net/manual/en/class.reflectionfunctionabstract.php
 */
abstract class ReflectionFunctionAbstract implements Reflector
{
    /**
     * @var string Name of the function, same as calling the {@see ReflectionFunctionAbstract::getName()} method
     */
    #[Immutable]
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    public $name;

    /**
     * Clones function
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.clone.php
     * @return void
     */
    #[PhpStormStubsElementAvailable(from: "5.4", to: "8.0")]
    final private function __clone(): void {}

    /**
     * Clones function
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.clone.php
     * @return void
     */
    #[PhpStormStubsElementAvailable(from: "8.1")]
    private function __clone(): void {}

    /**
     * Checks if function in namespace
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.innamespace.php
     * @return bool {@see true} if it's in a namespace, otherwise {@see false}
     */
    #[TentativeType]
    public function inNamespace(): bool {}

    /**
     * Checks if closure
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.isclosure.php
     * @return bool {@see true} if it's a closure, otherwise {@see false}
     */
    #[Pure]
    #[TentativeType]
    public function isClosure(): bool {}

    /**
     * Checks if deprecated
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.isdeprecated.php
     * @return bool {@see true} if it's deprecated, otherwise {@see false}
     */
    #[Pure]
    #[TentativeType]
    public function isDeprecated(): bool {}

    /**
     * Checks if is internal
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.isinternal.php
     * @return bool {@see true} if it's internal, otherwise {@see false}
     */
    #[Pure]
    #[TentativeType]
    public function isInternal(): bool {}

    /**
     * Checks if user defined
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.isuserdefined.php
     * @return bool {@see true} if it's user-defined, otherwise {@see false}
     */
    #[Pure]
    #[TentativeType]
    public function isUserDefined(): bool {}

    /**
     * Returns whether this function is a generator
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.isgenerator.php
     * @return bool {@see true} if the function is generator, otherwise {@see false}
     * @since 5.5
     */
    #[Pure]
    #[TentativeType]
    public function isGenerator(): bool {}

    /**
     * Returns whether this function is variadic
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.isvariadic.php
     * @return bool {@see true} if the function is variadic, otherwise {@see false}
     * @since 5.6
     */
    #[Pure]
    #[TentativeType]
    public function isVariadic(): bool {}

    /**
     * Returns this pointer bound to closure
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getclosurethis.php
     * @return object|null Returns $this pointer or {@see null} in case of an error.
     */
    #[Pure]
    #[TentativeType]
    public function getClosureThis(): ?object {}

    /**
     * Returns the scope associated to the closure
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getclosurescopeclass.php
     * @return ReflectionClass|null Returns the class on success or {@see null}
     * on failure.
     * @since 5.4
     */
    #[Pure]
    #[TentativeType]
    public function getClosureScopeClass(): ?ReflectionClass {}

    /**
     * @return ReflectionClass|null Returns the class on success or {@see null}
     * on failure.
     * @since 8.0
     */
    #[Pure]
    #[TentativeType]
    public function getClosureCalledClass(): ?ReflectionClass {}

    /**
     * Gets doc comment
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getdoccomment.php
     * @return string|false The doc comment if it exists, otherwise {@see false}
     */
    #[Pure]
    #[TentativeType]
    public function getDocComment(): string|false {}

    /**
     * Gets end line number
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getendline.php
     * @return int|false The ending line number of the user defined function,
     * or {@see false} if unknown.
     */
    #[Pure]
    #[TentativeType]
    public function getEndLine(): int|false {}

    /**
     * Gets extension info
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getextension.php
     * @return ReflectionExtension|null The extension information, as a
     * {@see ReflectionExtension} object or {@see null} instead.
     */
    #[Pure]
    #[TentativeType]
    public function getExtension(): ?ReflectionExtension {}

    /**
     * Gets extension name
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getextensionname.php
     * @return string|false The extension's name or {@see false} instead.
     */
    #[Pure]
    #[TentativeType]
    public function getExtensionName(): string|false {}

    /**
     * Gets file name
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getfilename.php
     * @return string|false The file name or {@see false} in case of error.
     */
    #[Pure]
    #[TentativeType]
    public function getFileName(): string|false {}

    /**
     * Gets function name
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getname.php
     * @return string The name of the function.
     */
    #[Pure]
    #[TentativeType]
    public function getName(): string {}

    /**
     * Gets namespace name
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getnamespacename.php
     * @return string The namespace name.
     */
    #[Pure]
    #[TentativeType]
    public function getNamespaceName(): string {}

    /**
     * Gets number of parameters
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getnumberofparameters.php
     * @return int The number of parameters.
     * @since 5.0.3
     */
    #[Pure]
    #[TentativeType]
    public function getNumberOfParameters(): int {}

    /**
     * Gets number of required parameters
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getnumberofrequiredparameters.php
     * @return int The number of required parameters.
     * @since 5.0.3
     */
    #[Pure]
    #[TentativeType]
    public function getNumberOfRequiredParameters(): int {}

    /**
     * Gets parameters
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getparameters.php
     * @return ReflectionParameter[] The parameters, as a ReflectionParameter objects.
     */
    #[Pure]
    #[TentativeType]
    public function getParameters(): array {}

    /**
     * Gets the specified return type of a function
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getreturntype.php
     * @return ReflectionType|null Returns a {@see ReflectionType} object if a
     * return type is specified, {@see null} otherwise.
     * @since 7.0
     */
    #[Pure]
    #[LanguageLevelTypeAware(
        [
            '7.1' => 'ReflectionNamedType|null',
            '8.0' => 'ReflectionNamedType|ReflectionUnionType|null',
            '8.1' => 'ReflectionNamedType|ReflectionUnionType|ReflectionIntersectionType|null'
        ],
        default: 'ReflectionType|null'
    )]
    #[TentativeType]
    public function getReturnType(): ?ReflectionType {}

    /**
     * Gets function short name
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getshortname.php
     * @return string The short name of the function.
     */
    #[Pure]
    #[TentativeType]
    public function getShortName(): string {}

    /**
     * Gets starting line number
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getstartline.php
     * @return int|false The starting line number or {@see false} if unknown.
     */
    #[Pure]
    #[TentativeType]
    public function getStartLine(): int|false {}

    /**
     * Gets static variables
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.getstaticvariables.php
     * @return array An array of static variables.
     */
    #[Pure]
    #[TentativeType]
    public function getStaticVariables(): array {}

    /**
     * Checks if returns reference
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.returnsreference.php
     * @return bool {@see true} if it returns a reference, otherwise {@see false}
     */
    #[TentativeType]
    public function returnsReference(): bool {}

    /**
     * Checks if the function has a specified return type
     *
     * @link https://php.net/manual/en/reflectionfunctionabstract.hasreturntype.php
     * @return bool Returns {@see true} if the function is a specified return
     * type, otherwise {@see false}.
     * @since 7.0
     */
    #[TentativeType]
    public function hasReturnType(): bool {}

    /**
     * @template T
     *
     * Returns an array of function attributes.
     *
     * @param class-string<T>|null $name Name of an attribute class
     * @param int $flags Сriteria by which the attribute is searched.
     * @return ReflectionAttribute<T>[]
     * @since 8.0
     */
    #[Pure]
    public function getAttributes(?string $name = null, int $flags = 0): array {}

    #[PhpStormStubsElementAvailable('8.1')]
    #[Pure]
    public function getClosureUsedVariables(): array {}

    #[PhpStormStubsElementAvailable('8.1')]
    #[Pure]
    public function hasTentativeReturnType(): bool {}

    #[PhpStormStubsElementAvailable('8.1')]
    #[Pure]
    public function getTentativeReturnType(): ?ReflectionType {}

    #[PhpStormStubsElementAvailable('8.1')]
    #[Pure]
    #[TentativeType]
    public function isStatic(): bool {}

    #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')]
    public function __toString() {}
}
