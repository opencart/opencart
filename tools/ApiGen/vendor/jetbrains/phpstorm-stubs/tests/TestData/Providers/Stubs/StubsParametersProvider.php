<?php
declare(strict_types=1);

namespace StubTests\TestData\Providers\Stubs;

use Generator;
use RuntimeException;
use StubTests\Model\PHPClass;
use StubTests\Model\PHPInterface;
use StubTests\Model\PHPMethod;
use StubTests\Model\StubProblemType;
use StubTests\Parsers\ParserUtils;
use StubTests\TestData\Providers\EntitiesFilter;
use StubTests\TestData\Providers\PhpStormStubsSingleton;

class StubsParametersProvider
{
    /**
     * @throws RuntimeException
     */
    public static function parametersForScalarTypeHintTestsProvider(): ?Generator
    {
        $filterFunction = self::getFilterFunctionForLanguageLevel(7);
        return self::yieldFilteredMethodParameters($filterFunction, StubProblemType::PARAMETER_HAS_SCALAR_TYPEHINT);
    }

    /**
     * @throws RuntimeException
     */
    public static function parametersForNullableTypeHintTestsProvider(): ?Generator
    {
        $filterFunction = self::getFilterFunctionForLanguageLevel(7.1);
        return self::yieldFilteredMethodParameters($filterFunction, StubProblemType::HAS_NULLABLE_TYPEHINT);
    }

    /**
     * @throws RuntimeException
     */
    public static function parametersForUnionTypeHintTestsProvider(): ?Generator
    {
        $filterFunction = self::getFilterFunctionForLanguageLevel(8);
        return self::yieldFilteredMethodParameters($filterFunction, StubProblemType::HAS_UNION_TYPEHINT);
    }

    /**
     * @throws RuntimeException
     */
    public static function parametersForAllowedScalarTypeHintTestsProvider(): ?Generator
    {
        $filterFunction = EntitiesFilter::getFilterFunctionForAllowedTypeHintsInLanguageLevel(7);
        return self::yieldFilteredMethodParameters($filterFunction, StubProblemType::PARAMETER_TYPE_MISMATCH);
    }

    /**
     * @throws RuntimeException
     */
    public static function parametersForAllowedNullableTypeHintTestsProvider(): ?Generator
    {
        $filterFunction = EntitiesFilter::getFilterFunctionForAllowedTypeHintsInLanguageLevel(7.1);
        return self::yieldFilteredMethodParameters($filterFunction, StubProblemType::PARAMETER_TYPE_MISMATCH);
    }

    /**
     * @throws RuntimeException
     */
    public static function parametersForAllowedUnionTypeHintTestsProvider(): ?Generator
    {
        $filterFunction = EntitiesFilter::getFilterFunctionForAllowedTypeHintsInLanguageLevel(8);
        return self::yieldFilteredMethodParameters($filterFunction, StubProblemType::HAS_UNION_TYPEHINT);
    }

    /**
     * @throws RuntimeException
     */
    private static function yieldFilteredMethodParameters(callable $filterFunction, int ...$problemTypes): ?Generator
    {
        $coreClassesAndInterfaces = PhpStormStubsSingleton::getPhpStormStubs()->getCoreClasses() +
            PhpStormStubsSingleton::getPhpStormStubs()->getCoreInterfaces();
        foreach (EntitiesFilter::getFiltered($coreClassesAndInterfaces) as $class) {
            foreach (EntitiesFilter::getFilteredFunctions($class, false) as $method) {
                foreach (EntitiesFilter::getFilteredParameters($method, null, ...$problemTypes) as $parameter) {
                    if (!empty($parameter->availableVersionsRangeFromAttribute)) {
                        $firstSinceVersion = max(ParserUtils::getDeclaredSinceVersion($method), min($parameter->availableVersionsRangeFromAttribute));
                    } else {
                        $firstSinceVersion = ParserUtils::getDeclaredSinceVersion($method);
                    }
                    if ($filterFunction($class, $method, $firstSinceVersion) === true) {
                        yield "method $class->name::$method->name($parameter->name)" => [$class, $method, $parameter];
                    }
                }
            }
        }
    }

    private static function getFilterFunctionForLanguageLevel(float $languageVersion): callable
    {
        return fn (PHPClass|PHPInterface $class, PHPMethod $method, ?float $firstSinceVersion) => !$method->isFinal &&
            !$class->isFinal && $firstSinceVersion !== null && $firstSinceVersion < $languageVersion;
    }
}
