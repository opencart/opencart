<?php
declare(strict_types=1);

namespace StubTests;

use PHPUnit\Framework\Exception;
use RuntimeException;
use StubTests\Model\PHPClass;
use StubTests\Model\PHPInterface;
use StubTests\Model\PHPMethod;
use StubTests\Model\PHPProperty;
use StubTests\Model\PhpVersions;
use StubTests\TestData\Providers\PhpStormStubsSingleton;

class BaseClassesTest extends AbstractBaseStubsTestCase
{
    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionClassesTestDataProviders::classWithParentProvider
     * @throws Exception|RuntimeException
     */
    public function testClassesParent(PHPClass|PHPInterface $class)
    {
        $className = $class->name;
        if ($class instanceof PHPClass) {
            $stubClass = PhpStormStubsSingleton::getPhpStormStubs()->getClass($className);
            static::assertEquals(
                $class->parentClass,
                $stubClass->parentClass,
                empty($class->parentClass) ? "Class $className should not extend $stubClass->parentClass" :
                    "Class $className should extend $class->parentClass"
            );
        } else {
            $stubClass = PhpStormStubsSingleton::getPhpStormStubs()->getInterface($className);
            foreach ($class->parentInterfaces as $parentInterface) {
                static::assertContains(
                    $parentInterface,
                    $stubClass->parentInterfaces,
                    "Interface $className should extend $parentInterface"
                );
            }
        }
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionMethodsProvider::classMethodsProvider
     * @throws Exception|RuntimeException
     */
    public function testClassesMethodsExist(PHPClass|PHPInterface $class, PHPMethod $method)
    {
        $className = $class->name;
        if ($class instanceof PHPClass) {
            $stubClass = PhpStormStubsSingleton::getPhpStormStubs()->getClass($className);
        } else {
            $stubClass = PhpStormStubsSingleton::getPhpStormStubs()->getInterface($className);
        }
        static::assertNotEmpty($stubClass->getMethod($method->name), "Missing method $className::$method->name");
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionMethodsProvider::classFinalMethodsProvider
     * @throws RuntimeException
     */
    public function testClassesFinalMethods(PHPClass|PHPInterface $class, PHPMethod $method)
    {
        $className = $class->name;
        if ($class instanceof PHPClass) {
            $stubMethod = PhpStormStubsSingleton::getPhpStormStubs()->getClass($className)->getMethod($method->name);
        } else {
            $stubMethod = PhpStormStubsSingleton::getPhpStormStubs()->getInterface($className)->getMethod($method->name);
        }
        static::assertEquals(
            $method->isFinal,
            $stubMethod->isFinal,
            "Method $className::$method->name final modifier is incorrect"
        );
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionMethodsProvider::classStaticMethodsProvider
     * @throws RuntimeException
     */
    public function testClassesStaticMethods(PHPClass|PHPInterface $class, PHPMethod $method)
    {
        $className = $class->name;
        if ($class instanceof PHPClass) {
            $stubMethod = PhpStormStubsSingleton::getPhpStormStubs()->getClass($className)->getMethod($method->name);
        } else {
            $stubMethod = PhpStormStubsSingleton::getPhpStormStubs()->getInterface($className)->getMethod($method->name);
        }
        static::assertEquals(
            $method->isStatic,
            $stubMethod->isStatic,
            "Method $className::$method->name static modifier is incorrect"
        );
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionMethodsProvider::classMethodsWithAccessProvider
     * @throws RuntimeException
     */
    public function testClassesMethodsVisibility(PHPClass|PHPInterface $class, PHPMethod $method)
    {
        $className = $class->name;
        if ($class instanceof PHPClass) {
            $stubMethod = PhpStormStubsSingleton::getPhpStormStubs()->getClass($className)->getMethod($method->name);
        } else {
            $stubMethod = PhpStormStubsSingleton::getPhpStormStubs()->getInterface($className)->getMethod($method->name);
        }
        static::assertEquals(
            $method->access,
            $stubMethod->access,
            "Method $className::$method->name access modifier is incorrect"
        );
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionMethodsProvider::classMethodsWithParametersProvider
     * @throws Exception|RuntimeException
     */
    public function testClassMethodsParametersCount(PHPClass|PHPInterface $class, PHPMethod $method)
    {
        $className = $class->name;
        if ($class instanceof PHPClass) {
            $stubMethod = PhpStormStubsSingleton::getPhpStormStubs()->getClass($className)->getMethod($method->name);
        } else {
            $stubMethod = PhpStormStubsSingleton::getPhpStormStubs()->getInterface($className)->getMethod($method->name);
        }
        $filteredStubParameters = array_filter(
            $stubMethod->parameters,
            function ($parameter) {
                if (!empty($parameter->availableVersionsRangeFromAttribute)) {
                    return $parameter->availableVersionsRangeFromAttribute['from'] <= (doubleval(getenv('PHP_VERSION') ?? PhpVersions::getFirst()))
                        && $parameter->availableVersionsRangeFromAttribute['to'] >= (doubleval(getenv('PHP_VERSION')) ?? PhpVersions::getLatest());
                } else {
                    return true;
                }
            }
        );
        static::assertSameSize(
            $method->parameters,
            $filteredStubParameters,
            "Parameter number mismatch for method $className::$method->name.
                         Expected: " . self::getParameterRepresentation($method)
        );
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionClassesTestDataProviders::classesWithInterfacesProvider
     * @throws Exception|RuntimeException
     */
    public function testClassInterfaces(PHPClass $class)
    {
        $className = $class->name;
        $stubClass = PhpStormStubsSingleton::getPhpStormStubs()->getClass($class->name, shouldSuitCurrentPhpVersion: false);
        foreach ($class->interfaces as $interface) {
            static::assertContains(
                $interface,
                $stubClass->interfaces,
                "Class $className doesn't implement interface $interface"
            );
        }
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionPropertiesProvider::classPropertiesProvider
     * @throws Exception|RuntimeException
     */
    public function testClassProperties(PHPClass $class, PHPProperty $property)
    {
        $className = $class->name;
        $stubClass = PhpStormStubsSingleton::getPhpStormStubs()->getClass($class->name);
        static::assertNotEmpty($stubClass->getProperty($property->name), "Missing property $property->access "
            . implode('|', $property->typesFromSignature) .
            "$className::$$property->name");
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionPropertiesProvider::classStaticPropertiesProvider
     * @throws RuntimeException
     */
    public function testClassStaticProperties(PHPClass $class, PHPProperty $property)
    {
        $className = $class->name;
        $stubProperty = PhpStormStubsSingleton::getPhpStormStubs()->getClass($class->name)->getProperty($property->name);
        static::assertEquals(
            $property->is_static,
            $stubProperty->is_static,
            "Property $className::$property->name static modifier is incorrect"
        );
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionPropertiesProvider::classPropertiesWithAccessProvider
     * @throws RuntimeException
     */
    public function testClassPropertiesVisibility(PHPClass $class, PHPProperty $property)
    {
        $className = $class->name;
        $stubProperty = PhpStormStubsSingleton::getPhpStormStubs()->getClass($class->name)->getProperty($property->name);
        static::assertEquals(
            $property->access,
            $stubProperty->access,
            "Property $className::$property->name access modifier is incorrect"
        );
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionPropertiesProvider::classPropertiesWithTypeProvider
     * @throws RuntimeException
     */
    public function testClassPropertiesType(PHPClass $class, PHPProperty $property)
    {
        $className = $class->name;
        $stubProperty = PhpStormStubsSingleton::getPhpStormStubs()->getClass($class->name)->getProperty($property->name);
        $propertyName = $stubProperty->name;
        $unifiedStubsPropertyTypes = [];
        $unifiedStubsAttributesPropertyTypes = [];
        $unifiedReflectionPropertyTypes = [];
        self::convertNullableTypesToUnion($property->typesFromSignature, $unifiedReflectionPropertyTypes);
        if (!empty($stubProperty->typesFromSignature)) {
            self::convertNullableTypesToUnion($stubProperty->typesFromSignature, $unifiedStubsPropertyTypes);
        }
        foreach ($stubProperty->typesFromAttribute as $languageVersion => $listOfTypes) {
            $unifiedStubsAttributesPropertyTypes[$languageVersion] = [];
            self::convertNullableTypesToUnion($listOfTypes, $unifiedStubsAttributesPropertyTypes[$languageVersion]);
        }
        $typesFromAttribute = [];
        $testCondition = self::isReflectionTypesMatchSignature($unifiedReflectionPropertyTypes, $unifiedStubsPropertyTypes);
        if (!$testCondition) {
            if (!empty($unifiedStubsAttributesPropertyTypes)) {
                $typesFromAttribute = !empty($unifiedStubsAttributesPropertyTypes[getenv('PHP_VERSION')]) ?
                    $unifiedStubsAttributesPropertyTypes[getenv('PHP_VERSION')] :
                    $unifiedStubsAttributesPropertyTypes['default'];
                $testCondition = self::isReflectionTypesExistInAttributes($unifiedReflectionPropertyTypes, $typesFromAttribute);
            }
        }
        self::assertTrue($testCondition, "Property $className::$propertyName has invalid typehint.
        Reflection property has type " . implode('|', $unifiedReflectionPropertyTypes) . ' but stubs has type ' .
            implode('|', $unifiedStubsPropertyTypes) . ' in signature and attribute has types ' .
            implode('|', $typesFromAttribute));
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionClassesTestDataProviders::allClassesProvider
     * @throws Exception
     */
    public function testClassesExist(PHPClass|PHPInterface $class): void
    {
        $className = $class->name;
        if ($class instanceof PHPClass) {
            $stubClass = PhpStormStubsSingleton::getPhpStormStubs()->getClass($className);
        } else {
            $stubClass = PhpStormStubsSingleton::getPhpStormStubs()->getInterface($className);
        }
        static::assertNotEmpty($stubClass, "Missing class $className: class $className {}");
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionClassesTestDataProviders::finalClassesProvider
     * @throws Exception|RuntimeException
     */
    public function testClassesFinal(PHPClass|PHPInterface $class): void
    {
        $className = $class->name;
        if ($class instanceof PHPClass) {
            $stubClass = PhpStormStubsSingleton::getPhpStormStubs()->getClass($className);
        } else {
            $stubClass = PhpStormStubsSingleton::getPhpStormStubs()->getInterface($className);
        }
        static::assertEquals($class->isFinal, $stubClass->isFinal, "Final modifier of class $className is incorrect");
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionClassesTestDataProviders::readonlyClassesProvider
     * @throws RuntimeException
     */
    public function testClassesReadonly(PHPClass $class): void
    {
        $className = $class->name;
        $stubClass = PhpStormStubsSingleton::getPhpStormStubs()->getClass($className);
        static::assertEquals(
            $class->isReadonly,
            $stubClass->isReadonly,
            "Readonly modifier for class $className is incorrect"
        );
    }
}
