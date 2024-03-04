<?php
declare(strict_types=1);

namespace StubTests\TestData\Providers;

use RuntimeException;
use StubTests\Model\BasePHPElement;
use StubTests\Model\PHPClass;
use StubTests\Model\PHPFunction;
use StubTests\Model\PHPInterface;
use StubTests\Model\PHPMethod;
use StubTests\Model\PHPParameter;
use StubTests\Model\StubProblemType;

class EntitiesFilter
{
    /**
     * @param BasePHPElement[] $entities
     * @return BasePHPElement[]
     */
    public static function getFiltered(array $entities, callable $additionalFilter = null, int ...$problemTypes): array
    {
        $resultArray = [];
        $hasProblem = false;
        foreach ($entities as $key => $entity) {
            foreach ($problemTypes as $problemType) {
                if ($entity->hasMutedProblem($problemType)) {
                    $hasProblem = true;
                }
            }
            if ($entity->hasMutedProblem(StubProblemType::STUB_IS_MISSED) ||
                $additionalFilter !== null && $additionalFilter($entity) === true) {
                $hasProblem = true;
            }
            if ($hasProblem) {
                $hasProblem = false;
            } else {
                $resultArray[$key] = $entity;
            }
        }

        return $resultArray;
    }

    /**
     * @return PHPFunction[]
     * @throws RuntimeException
     */
    public static function getFilteredFunctions(PHPInterface|PHPClass $class = null, bool $shouldSuitCurrentPhpVersion = true): array
    {
        if ($class === null) {
            $allFunctions = ReflectionStubsSingleton::getReflectionStubs()->getFunctions();
        } else {
            $allFunctions = $class->methods;
        }
        /** @var PHPFunction[] $resultArray */
        $resultArray = [];
        $allFunctions = array_filter(
            $allFunctions,
            fn ($function) => !$shouldSuitCurrentPhpVersion || BasePHPElement::entitySuitsCurrentPhpVersion($function)
        );
        foreach (self::getFiltered($allFunctions, null, StubProblemType::HAS_DUPLICATION, StubProblemType::FUNCTION_PARAMETER_MISMATCH) as $function) {
            $resultArray[] = $function;
        }
        return $resultArray;
    }

    public static function getFilteredParameters(PHPFunction $function, callable $additionalFilter = null, int ...$problemType): array
    {
        /** @var PHPParameter[] $resultArray */
        $resultArray = [];
        foreach (self::getFiltered(
            $function->parameters,
            $additionalFilter,
            StubProblemType::FUNCTION_PARAMETER_MISMATCH,
            ...$problemType
        ) as $parameter) {
            $resultArray[] = $parameter;
        }
        return $resultArray;
    }

    public static function getFilterFunctionForAllowedTypeHintsInLanguageLevel(float $languageVersion): callable
    {
        return function (PHPClass|PHPInterface $stubClass, PHPMethod $stubMethod, ?float $firstSinceVersion) use ($languageVersion) {
            $reflectionClass = ReflectionStubsSingleton::getReflectionStubs()->getClass($stubClass->name);
            $reflectionMethod = null;
            if ($reflectionClass !== null) {
                $reflectionMethods = array_filter(
                    $reflectionClass->methods,
                    fn (PHPMethod $method) => $stubMethod->name === $method->name
                );
                $reflectionMethod = array_pop($reflectionMethods);
            }
            return $reflectionMethod !== null && ($stubMethod->isFinal || $stubClass->isFinal || $firstSinceVersion !== null &&
                    $firstSinceVersion > $languageVersion);
        };
    }
}
