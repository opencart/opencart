<?php
declare(strict_types=1);

namespace StubTests;

use PHPUnit\Framework\Exception;
use RuntimeException;
use StubTests\TestData\Providers\PhpStormStubsSingleton;

/**
 * Class to test typehints of some Reflection* classes as reflection for these classes returns null.
 */
class StubsReflectionClassesTest extends AbstractBaseStubsTestCase
{
    /**
     * @throws Exception|RuntimeException
     */
    public function testReflectionFunctionAbstractGetReturnTypeMethod()
    {
        $getReturnTypeMethod = PhpStormStubsSingleton::getPhpStormStubs()->getClass('ReflectionFunctionAbstract')->getMethod('getReturnType');
        $allReturnTypes = array_unique(Model\CommonUtils::flattenArray($getReturnTypeMethod->returnTypesFromAttribute +
            $getReturnTypeMethod->returnTypesFromSignature + $getReturnTypeMethod->returnTypesFromPhpDoc, false));
        self::assertContains(
            'ReflectionNamedType',
            $allReturnTypes,
            'method ReflectionFunctionAbstract::getReturnType should have ReflectionNamedType in return types for php 7.1+'
        );
        self::assertContains(
            'ReflectionUnionType',
            $allReturnTypes,
            'method ReflectionFunctionAbstract::getReturnType should have ReflectionUnionType in return types for php 8.0+'
        );
        self::assertContains(
            'ReflectionType',
            $allReturnTypes,
            'method ReflectionFunctionAbstract::getReturnType should have ReflectionType in return types for php 7.0'
        );
    }

    /**
     * @throws Exception|RuntimeException
     */
    public function testReflectionPropertyGetTypeMethod()
    {
        $getTypeMethod = PhpStormStubsSingleton::getPhpStormStubs()->getClass('ReflectionProperty')->getMethod('getType');
        $allReturnTypes = array_unique(Model\CommonUtils::flattenArray($getTypeMethod->returnTypesFromAttribute +
            $getTypeMethod->returnTypesFromSignature + $getTypeMethod->returnTypesFromPhpDoc, false));
        self::assertContains(
            'ReflectionNamedType',
            $allReturnTypes,
            'method ReflectionProperty::getType should have ReflectionNamedType in return types for php 7.1+'
        );
        self::assertContains(
            'ReflectionUnionType',
            $allReturnTypes,
            'method ReflectionProperty::getType should have ReflectionUnionType in return types for php 8.0+'
        );
    }

    /**
     * @throws Exception|RuntimeException
     */
    public function testReflectionParameterGetTypeMethod()
    {
        $getTypeMethod = PhpStormStubsSingleton::getPhpStormStubs()->getClass('ReflectionParameter')->getMethod('getType');
        $allReturnTypes = array_unique(Model\CommonUtils::flattenArray($getTypeMethod->returnTypesFromAttribute +
            $getTypeMethod->returnTypesFromSignature + $getTypeMethod->returnTypesFromPhpDoc, false));
        self::assertContains(
            'ReflectionNamedType',
            $allReturnTypes,
            'method ReflectionParameter::getType should have ReflectionNamedType in return types'
        );
        self::assertContains(
            'ReflectionUnionType',
            $allReturnTypes,
            'method ReflectionParameter::getType should have ReflectionUnionType in return types'
        );
    }
}
