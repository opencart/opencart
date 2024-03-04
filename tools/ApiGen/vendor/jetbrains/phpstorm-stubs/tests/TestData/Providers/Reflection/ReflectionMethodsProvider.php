<?php
declare(strict_types=1);

namespace StubTests\TestData\Providers\Reflection;

use Generator;
use StubTests\Model\PHPMethod;
use StubTests\Model\StubProblemType;
use StubTests\TestData\Providers\EntitiesFilter;
use StubTests\TestData\Providers\ReflectionStubsSingleton;

class ReflectionMethodsProvider
{
    public static function classMethodsProvider(): ?Generator
    {
        return self::yieldFilteredMethods();
    }

    public static function classMethodsWithReturnTypeHintProvider(): ?Generator
    {
        return self::yieldFilteredMethods(StubProblemType::WRONG_RETURN_TYPEHINT);
    }

    public static function classMethodsWithAccessProvider(): ?Generator
    {
        return self::yieldFilteredMethods(StubProblemType::FUNCTION_ACCESS);
    }

    public static function classFinalMethodsProvider(): ?Generator
    {
        return self::yieldFilteredMethods(StubProblemType::WRONG_FINAL_MODIFIER);
    }

    public static function classStaticMethodsProvider(): ?Generator
    {
        return self::yieldFilteredMethods(StubProblemType::WRONG_STATIC_MODIFIER);
    }

    public static function classMethodsWithParametersProvider(): ?Generator
    {
        return self::yieldFilteredMethods(
            StubProblemType::HAS_DUPLICATION,
            StubProblemType::FUNCTION_PARAMETER_MISMATCH
        );
    }

    public static function classMethodsWithoutTentitiveReturnTypeProvider(): ?Generator
    {
        $classesAndInterfaces = ReflectionStubsSingleton::getReflectionStubs()->getClasses() +
            ReflectionStubsSingleton::getReflectionStubs()->getInterfaces();
        foreach (EntitiesFilter::getFiltered($classesAndInterfaces) as $class) {
            foreach (EntitiesFilter::getFiltered(
                $class->methods,
                fn (PHPMethod $method) => $method->isReturnTypeTentative,
                StubProblemType::HAS_DUPLICATION,
                StubProblemType::FUNCTION_PARAMETER_MISMATCH,
                StubProblemType::WRONG_RETURN_TYPEHINT
            ) as $method) {
                yield "Method $class->name::$method->name" => [$class, $method];
            }
        }
    }

    public static function classMethodsWithTentitiveReturnTypeProvider(): ?Generator
    {
        $classesAndInterfaces = ReflectionStubsSingleton::getReflectionStubs()->getClasses() +
            ReflectionStubsSingleton::getReflectionStubs()->getInterfaces();
        foreach (EntitiesFilter::getFiltered($classesAndInterfaces) as $class) {
            foreach (EntitiesFilter::getFiltered(
                $class->methods,
                fn (PHPMethod $method) => !$method->isReturnTypeTentative,
                StubProblemType::HAS_DUPLICATION,
                StubProblemType::FUNCTION_PARAMETER_MISMATCH,
                StubProblemType::WRONG_RETURN_TYPEHINT
            ) as $method) {
                yield "Method $class->name::$method->name" => [$class, $method];
            }
        }
    }

    private static function yieldFilteredMethods(int ...$problemTypes): ?Generator
    {
        $classesAndInterfaces = ReflectionStubsSingleton::getReflectionStubs()->getClasses() +
            ReflectionStubsSingleton::getReflectionStubs()->getInterfaces();
        foreach (EntitiesFilter::getFiltered($classesAndInterfaces) as $class) {
            foreach (EntitiesFilter::getFiltered($class->methods, null, ...$problemTypes) as $method) {
                yield "Method $class->name::$method->name" => [$class, $method];
            }
        }
    }
}
