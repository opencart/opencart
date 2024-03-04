<?php
declare(strict_types=1);

namespace StubTests\TestData\Providers\Reflection;

use Generator;
use StubTests\Model\PHPClass;
use StubTests\Model\PHPConst;
use StubTests\Model\PHPInterface;
use StubTests\Model\StubProblemType;
use StubTests\TestData\Providers\EntitiesFilter;
use StubTests\TestData\Providers\ReflectionStubsSingleton;

class ReflectionConstantsProvider
{
    public static function constantProvider(): ?Generator
    {
        foreach (EntitiesFilter::getFiltered(ReflectionStubsSingleton::getReflectionStubs()->getConstants()) as $constant) {
            yield "constant $constant->name" => [$constant];
        }
    }

    public static function constantValuesProvider(): ?Generator
    {
        foreach (self::getFilteredConstants() as $constant) {
            yield "constant $constant->name" => [$constant];
        }
    }

    public static function classConstantProvider(): ?Generator
    {
        $classesAndInterfaces = ReflectionStubsSingleton::getReflectionStubs()->getClasses() +
            ReflectionStubsSingleton::getReflectionStubs()->getInterfaces();
        foreach (EntitiesFilter::getFiltered($classesAndInterfaces) as $class) {
            foreach (EntitiesFilter::getFiltered($class->constants) as $constant) {
                yield "constant $class->name::$constant->name" => [$class, $constant];
            }
        }
    }

    public static function classConstantValuesProvider(): ?Generator
    {
        $classesAndInterfaces = ReflectionStubsSingleton::getReflectionStubs()->getClasses() +
            ReflectionStubsSingleton::getReflectionStubs()->getInterfaces();
        foreach (EntitiesFilter::getFiltered($classesAndInterfaces) as $class) {
            foreach (self::getFilteredConstants($class) as $constant) {
                yield "constant $class->name::$constant->name" => [$class, $constant];
            }
        }
    }

    /**
     * @return PHPConst[]
     */
    public static function getFilteredConstants(PHPInterface|PHPClass $class = null): array
    {
        if ($class === null) {
            $allConstants = ReflectionStubsSingleton::getReflectionStubs()->getConstants();
        } else {
            $allConstants = $class->constants;
        }
        /** @var PHPConst[] $resultArray */
        $resultArray = [];
        foreach (EntitiesFilter::getFiltered($allConstants, null, StubProblemType::WRONG_CONSTANT_VALUE) as $constant) {
            $resultArray[] = $constant;
        }
        return $resultArray;
    }
}
