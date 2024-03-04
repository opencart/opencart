<?php

namespace StubTests;

use RuntimeException;
use StubTests\Model\PHPClass;
use StubTests\Model\PHPInterface;
use StubTests\Model\PHPMethod;
use StubTests\Model\PHPProperty;
use StubTests\TestData\Providers\PhpStormStubsSingleton;

class StubsPhp81Tests extends AbstractBaseStubsTestCase
{
    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionPropertiesProvider::classReadonlyPropertiesProvider
     * @throws RuntimeException
     */
    public function testPropertyReadonly(PHPClass $class, PHPProperty $property)
    {
        $className = $class->name;
        $stubProperty = PhpStormStubsSingleton::getPhpStormStubs()->getClass($class->name)->getProperty($property->name);
        static::assertEquals(
            $property->isReadonly,
            $stubProperty->isReadonly,
            "Property $className::$property->name readonly modifier is incorrect"
        );
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionMethodsProvider::classMethodsWithTentitiveReturnTypeProvider
     * @throws RuntimeException
     */
    public function testTentativeReturnTypeHints(PHPClass|PHPInterface $class, PHPMethod $method)
    {
        $functionName = $method->name;
        if ($class instanceof PHPClass) {
            $stubMethod = PhpStormStubsSingleton::getPhpStormStubs()->getClass($class->name)->getMethod($functionName);
        } else {
            $stubMethod = PhpStormStubsSingleton::getPhpStormStubs()->getInterface($class->name)->getMethod($functionName);
        }
        $unifiedStubsReturnTypes = [];
        $unifiedStubsAttributesReturnTypes = [];
        $unifiedReflectionReturnTypes = [];
        self::convertNullableTypesToUnion($method->returnTypesFromSignature, $unifiedReflectionReturnTypes);
        if (!empty($stubMethod->returnTypesFromSignature)) {
            self::convertNullableTypesToUnion($stubMethod->returnTypesFromSignature, $unifiedStubsReturnTypes);
        } else {
            foreach ($stubMethod->returnTypesFromAttribute as $languageVersion => $listOfTypes) {
                $unifiedStubsAttributesReturnTypes[$languageVersion] = [];
                self::convertNullableTypesToUnion($listOfTypes, $unifiedStubsAttributesReturnTypes[$languageVersion]);
            }
        }
        $conditionToCompareWithSignature = AbstractBaseStubsTestCase::isReflectionTypesMatchSignature(
            $unifiedReflectionReturnTypes,
            $unifiedStubsReturnTypes
        );
        $typesFromAttribute = [];
        if (!empty($unifiedStubsAttributesReturnTypes)) {
            $typesFromAttribute = !empty($unifiedStubsAttributesReturnTypes[getenv('PHP_VERSION')]) ?
                $unifiedStubsAttributesReturnTypes[getenv('PHP_VERSION')] :
                $unifiedStubsAttributesReturnTypes['default'];
        }
        $conditionToCompareWithAttribute = AbstractBaseStubsTestCase::isReflectionTypesExistInAttributes($unifiedReflectionReturnTypes, $typesFromAttribute);
        $testCondition = $conditionToCompareWithSignature || $conditionToCompareWithAttribute;
        self::assertTrue(
            $testCondition,
            "Method $class->name::$functionName has invalid return type. Reflection method has return type " .
            implode('|', $method->returnTypesFromSignature) .
            ' but stubs has return type ' . implode('|', $stubMethod->returnTypesFromSignature) .
            ' in signature and attribute has types ' . implode('|', $typesFromAttribute)
        );
        self::assertTrue($stubMethod->isReturnTypeTentative, "Reflection method $class->name::$functionName has " .
            "tentative return type but stub's method isn't declared as tentative");
    }
}
