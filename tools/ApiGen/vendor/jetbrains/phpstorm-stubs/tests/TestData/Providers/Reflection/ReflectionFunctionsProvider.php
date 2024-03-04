<?php
declare(strict_types=1);

namespace StubTests\TestData\Providers\Reflection;

use Generator;
use StubTests\Model\StubProblemType;
use StubTests\TestData\Providers\EntitiesFilter;
use StubTests\TestData\Providers\ReflectionStubsSingleton;

class ReflectionFunctionsProvider
{
    public static function allFunctionsProvider(): ?Generator
    {
        foreach (EntitiesFilter::getFiltered(ReflectionStubsSingleton::getReflectionStubs()->getFunctions()) as $function) {
            yield "function $function->name" => [$function];
        }
    }

    public static function functionsForReturnTypeHintsTestProvider(): ?Generator
    {
        foreach (EntitiesFilter::getFiltered(
            ReflectionStubsSingleton::getReflectionStubs()->getFunctions(),
            null,
            StubProblemType::WRONG_RETURN_TYPEHINT
        ) as $function) {
            yield "function $function->name" => [$function];
        }
    }

    public static function functionsForDeprecationTestsProvider(): ?Generator
    {
        foreach (EntitiesFilter::getFiltered(
            ReflectionStubsSingleton::getReflectionStubs()->getFunctions(),
            null,
            StubProblemType::FUNCTION_IS_DEPRECATED
        ) as $function) {
            yield "function $function->name" => [$function];
        }
    }

    public static function functionsForParamsAmountTestsProvider(): ?Generator
    {
        foreach (EntitiesFilter::getFiltered(
            ReflectionStubsSingleton::getReflectionStubs()->getFunctions(),
            null,
            StubProblemType::FUNCTION_PARAMETER_MISMATCH,
            StubProblemType::HAS_DUPLICATION
        ) as $function) {
            yield "function $function->name" => [$function];
        }
    }
}
