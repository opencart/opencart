<?php
declare(strict_types=1);

namespace StubTests\TestData\Providers\Stubs;

use Generator;
use RuntimeException;
use StubTests\Model\PHPClass;
use StubTests\Model\PHPFunction;
use StubTests\Model\PHPInterface;
use StubTests\Model\PHPMethod;
use StubTests\Model\StubProblemType;
use StubTests\Parsers\ParserUtils;
use StubTests\TestData\Providers\EntitiesFilter;
use StubTests\TestData\Providers\PhpStormStubsSingleton;

class StubMethodsProvider
{
    public static function allMethodsProvider(): ?Generator
    {
        $classesAndInterfaces = PhpStormStubsSingleton::getPhpStormStubs()->getClasses() +
            PhpStormStubsSingleton::getPhpStormStubs()->getInterfaces();
        foreach ($classesAndInterfaces as $className => $class) {
            foreach ($class->methods as $methodName => $method) {
                yield "method $className::$methodName" => [$method];
            }
        }
    }

    public static function allFunctionAndMethodsWithReturnTypeHintsProvider(): ?Generator
    {
        $coreClassesAndInterfaces = PhpStormStubsSingleton::getPhpStormStubs()->getClasses() +
            PhpStormStubsSingleton::getPhpStormStubs()->getInterfaces();
        $allFunctions = PhpStormStubsSingleton::getPhpStormStubs()->getFunctions();
        $filteredMethods = [];
        foreach (EntitiesFilter::getFiltered($coreClassesAndInterfaces) as $class) {
            $filteredMethods = EntitiesFilter::getFiltered(
                $class->methods,
                fn (PHPMethod $method) => empty($method->returnTypesFromSignature) || empty($method->returnTypesFromPhpDoc)
                    || $method->parentName === '___PHPSTORM_HELPERS\object',
                StubProblemType::TYPE_IN_PHPDOC_DIFFERS_FROM_SIGNATURE
            );
        }
        $filteredMethods += EntitiesFilter::getFiltered(
            $allFunctions,
            fn (PHPFunction $function) => empty($function->returnTypesFromSignature) || empty($function->returnTypesFromPhpDoc),
            StubProblemType::TYPE_IN_PHPDOC_DIFFERS_FROM_SIGNATURE
        );
        foreach ($filteredMethods as $methodName => $method) {
            if ($method instanceof PHPMethod) {
                yield "method $method->parentName::$methodName" => [$method];
            } else {
                yield "function $methodName" => [$method];
            }
        }
    }

    /**
     * @throws RuntimeException
     */
    public static function methodsForReturnTypeHintTestsProvider(): ?Generator
    {
        $filterFunction = self::getFilterFunctionForLanguageLevel(7);
        return self::yieldFilteredMethods(
            $filterFunction,
            StubProblemType::FUNCTION_HAS_RETURN_TYPEHINT,
            StubProblemType::WRONG_RETURN_TYPEHINT
        );
    }

    /**
     * @throws RuntimeException
     */
    public static function methodsForNullableReturnTypeHintTestsProvider(): ?Generator
    {
        $filterFunction = self::getFilterFunctionForLanguageLevel(7.1);
        return self::yieldFilteredMethods(
            $filterFunction,
            StubProblemType::HAS_NULLABLE_TYPEHINT,
            StubProblemType::WRONG_RETURN_TYPEHINT
        );
    }

    /**
     * @throws RuntimeException
     */
    public static function methodsForUnionReturnTypeHintTestsProvider(): ?Generator
    {
        $filterFunction = self::getFilterFunctionForLanguageLevel(8);
        return self::yieldFilteredMethods(
            $filterFunction,
            StubProblemType::HAS_UNION_TYPEHINT,
            StubProblemType::WRONG_RETURN_TYPEHINT
        );
    }

    private static function getFilterFunctionForLanguageLevel(float $languageVersion): callable
    {
        return fn (PHPClass|PHPInterface $class, PHPMethod $method, ?float $firstSinceVersion) => !$method->isFinal &&
            !$class->isFinal && $firstSinceVersion !== null && $firstSinceVersion < $languageVersion && !$method->isReturnTypeTentative;
    }

    /**
     * @throws RuntimeException
     */
    private static function yieldFilteredMethods(callable $filterFunction, int ...$problemTypes): ?Generator
    {
        $coreClassesAndInterfaces = PhpStormStubsSingleton::getPhpStormStubs()->getCoreClasses() +
            PhpStormStubsSingleton::getPhpStormStubs()->getCoreInterfaces();
        foreach (EntitiesFilter::getFiltered($coreClassesAndInterfaces) as $className => $class) {
            foreach (EntitiesFilter::getFiltered(
                $class->methods,
                fn (PHPMethod $method) => $method->parentName === '___PHPSTORM_HELPERS\object',
                ...$problemTypes
            ) as $methodName => $method) {
                $firstSinceVersion = ParserUtils::getDeclaredSinceVersion($method);
                if ($filterFunction($class, $method, $firstSinceVersion) === true) {
                    yield "method $className::$methodName" => [$method];
                }
            }
        }
    }
}
