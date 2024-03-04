<?php
declare(strict_types=1);

namespace StubTests;

use PHPUnit\Framework\Exception;
use RuntimeException;
use StubTests\Model\PHPClass;
use StubTests\Model\PHPFunction;
use StubTests\Model\PHPInterface;
use StubTests\Model\PHPMethod;
use StubTests\Model\PHPParameter;
use StubTests\Model\StubProblemType;
use StubTests\TestData\Providers\PhpStormStubsSingleton;
use StubTests\TestData\Providers\ReflectionStubsSingleton;

class StubsTypeHintsTest extends AbstractBaseStubsTestCase
{
    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionFunctionsProvider::functionsForReturnTypeHintsTestProvider
     * @throws RuntimeException
     */
    public function testFunctionsReturnTypeHints(PHPFunction $function)
    {
        $functionName = $function->name;
        $stubFunction = PhpStormStubsSingleton::getPhpStormStubs()->getFunction($functionName);
        $unifiedStubsReturnTypes = [];
        $unifiedStubsAttributesReturnTypes = [];
        $unifiedReflectionReturnTypes = [];
        self::convertNullableTypesToUnion($function->returnTypesFromSignature, $unifiedReflectionReturnTypes);
        if (!empty($stubFunction->returnTypesFromSignature)) {
            self::convertNullableTypesToUnion($stubFunction->returnTypesFromSignature, $unifiedStubsReturnTypes);
        }
        foreach ($stubFunction->returnTypesFromAttribute as $languageVersion => $listOfTypes) {
            $unifiedStubsAttributesReturnTypes[$languageVersion] = [];
            self::convertNullableTypesToUnion($listOfTypes, $unifiedStubsAttributesReturnTypes[$languageVersion]);
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
        self::assertTrue($testCondition, "Function $functionName has invalid return type.
        Reflection function has return type " . implode('|', $function->returnTypesFromSignature) . ' but stubs has return type ' .
            implode('|', $stubFunction->returnTypesFromSignature) . ' in signature and attribute has types ' .
            implode('|', $typesFromAttribute));
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionParametersProvider::functionParametersWithTypeProvider
     * @throws RuntimeException
     */
    public function testFunctionsParametersTypeHints(PHPFunction $function, PHPParameter $parameter)
    {
        $functionName = $function->name;
        $phpstormFunction = PhpStormStubsSingleton::getPhpStormStubs()->getFunction($functionName);
        /** @var PHPParameter $stubParameter */
        $stubParameter = current(array_filter($phpstormFunction->parameters, fn (PHPParameter $stubParameter) => $stubParameter->indexInSignature === $parameter->indexInSignature));
        self::compareTypeHintsWithReflection($parameter, $stubParameter, $functionName);
        if (!$parameter->hasMutedProblem(StubProblemType::PARAMETER_REFERENCE)) {
            self::assertEquals(
                $parameter->is_passed_by_ref,
                $stubParameter->is_passed_by_ref,
                "Invalid pass by ref $functionName: \$$parameter->name "
            );
        }
        self::assertEquals(
            $parameter->is_vararg,
            $stubParameter->is_vararg,
            "Invalid vararg $functionName: \$$parameter->name "
        );
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionMethodsProvider::classMethodsWithoutTentitiveReturnTypeProvider
     * @throws RuntimeException
     */
    public function testMethodsReturnTypeHints(PHPClass|PHPInterface $class, PHPMethod $method)
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
        self::assertTrue($testCondition, "Method $class->name::$functionName has invalid return type.
        Reflection method has return type " . implode('|', $method->returnTypesFromSignature) . ' but stubs has return type ' .
            implode('|', $stubMethod->returnTypesFromSignature) . ' in signature and attribute has types ' .
            implode('|', $typesFromAttribute));
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionParametersProvider::methodParametersProvider
     * @throws RuntimeException
     */
    public function testMethodsParametersTypeHints(PHPClass|PHPInterface $reflectionClass, PHPMethod $reflectionMethod, PHPParameter $reflectionParameter)
    {
        $className = $reflectionClass->name;
        $methodName = $reflectionMethod->name;
        if ($reflectionClass instanceof PHPClass) {
            $stubMethod = PhpStormStubsSingleton::getPhpStormStubs()->getClass($className)->getMethod($methodName);
        } else {
            $stubMethod = PhpStormStubsSingleton::getPhpStormStubs()->getInterface($className)->getMethod($methodName);
        }
        /** @var PHPParameter $stubParameter */
        $stubParameter = current(array_filter(
            $stubMethod->parameters,
            fn (PHPParameter $stubParameter) => $stubParameter->name === $reflectionParameter->name
        ));
        self::assertNotFalse($stubParameter, "Parameter $$reflectionParameter->name not found at 
        $reflectionClass->name::$stubMethod->name(" .
            StubsParameterNamesTest::printParameters($stubMethod->parameters) . ')');
        self::compareTypeHintsWithReflection($reflectionParameter, $stubParameter, $methodName);
        if (!$reflectionParameter->hasMutedProblem(StubProblemType::PARAMETER_REFERENCE)) {
            self::assertEquals(
                $reflectionParameter->is_passed_by_ref,
                $stubParameter->is_passed_by_ref,
                "Invalid pass by ref $className::$methodName: \$$reflectionParameter->name "
            );
        }
        self::assertEquals(
            $reflectionParameter->is_vararg,
            $stubParameter->is_vararg,
            "Invalid pass by ref $className::$methodName: \$$reflectionParameter->name "
        );
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Stubs\StubsParametersProvider::parametersForAllowedScalarTypeHintTestsProvider
     * @throws RuntimeException
     */
    public function testMethodScalarTypeHintsInParametersMatchReflection(PHPClass|PHPInterface $class, PHPMethod $stubMethod, PHPParameter $stubParameter)
    {
        $reflectionMethod = ReflectionStubsSingleton::getReflectionStubs()->getClass($class->name)->getMethod($stubMethod->name);
        $reflectionParameters = array_filter($reflectionMethod->parameters, fn (PHPParameter $parameter) => $parameter->name === $stubParameter->name);
        $reflectionParameter = array_pop($reflectionParameters);
        self::compareTypeHintsWithReflection($reflectionParameter, $stubParameter, $stubMethod->name);
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Stubs\StubsParametersProvider::parametersForAllowedNullableTypeHintTestsProvider
     * @throws RuntimeException
     */
    public function testMethodNullableTypeHintsInParametersMatchReflection(PHPClass|PHPInterface $class, PHPMethod $stubMethod, PHPParameter $stubParameter)
    {
        $reflectionMethod = ReflectionStubsSingleton::getReflectionStubs()->getClass($class->name)->getMethod($stubMethod->name);
        $reflectionParameters = array_filter($reflectionMethod->parameters, fn (PHPParameter $parameter) => $parameter->name === $stubParameter->name);
        $reflectionParameter = array_pop($reflectionParameters);
        self::compareTypeHintsWithReflection($reflectionParameter, $stubParameter, $stubMethod->name);
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Stubs\StubsParametersProvider::parametersForAllowedUnionTypeHintTestsProvider
     * @throws RuntimeException
     */
    public function testMethodUnionTypeHintsInParametersMatchReflection(PHPClass|PHPInterface $class, PHPMethod $stubMethod, PHPParameter $stubParameter)
    {
        $reflectionMethod = ReflectionStubsSingleton::getReflectionStubs()->getClass($class->name)->getMethod($stubMethod->name);
        $reflectionParameters = array_filter($reflectionMethod->parameters, fn (PHPParameter $parameter) => $parameter->name === $stubParameter->name);
        $reflectionParameter = array_pop($reflectionParameters);
        self::compareTypeHintsWithReflection($reflectionParameter, $stubParameter, $stubMethod->name);
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Stubs\StubMethodsProvider::allFunctionAndMethodsWithReturnTypeHintsProvider
     * @throws Exception
     */
    public static function testSignatureTypeHintsConformPhpDocInMethods(PHPFunction|PHPMethod $method)
    {
        $functionName = $method->name;
        $unifiedPhpDocTypes = array_map(function (string $type) use ($method) {
            $typeParts = explode('\\', $type);
            $typeName = end($typeParts);
            foreach ($method->templateTypes as $templateType) {
                if ($typeName === $templateType) {
                    $typeName = 'object';
                }
            }

            // replace array notations like int[] or array<string,mixed> to match the array type
            return preg_replace(['/\w+\[]/', '/array<[a-z,\s]+>/'], 'array', $typeName);
        }, $method->returnTypesFromPhpDoc);
        $unifiedSignatureTypes = $method->returnTypesFromSignature;
        if (count($unifiedSignatureTypes) === 1) {
            $type = array_pop($unifiedSignatureTypes);
            if (str_contains($type, '?')) {
                $unifiedSignatureTypes[] = 'null';
            }
            $typeParts = explode('\\', ltrim($type, '?'));
            $typeName = end($typeParts);
            $unifiedSignatureTypes[] = $typeName;
        }
        $typesIntersection = array_intersect($unifiedSignatureTypes, $unifiedPhpDocTypes);
        self::assertSameSize(
            $unifiedSignatureTypes,
            $typesIntersection,
            $method instanceof PHPMethod ? "Method $method->parentName::" : 'Function ' .
                "$functionName has mismatch in phpdoc return type and signature return type\n
                signature has " . implode('|', $unifiedSignatureTypes) . "\n
                but phpdoc has " . implode('|', $unifiedPhpDocTypes)
        );
    }

    private static function compareTypeHintsWithReflection(PHPParameter $parameter, PHPParameter $stubParameter, ?string $functionName): void
    {
        $unifiedStubsParameterTypes = [];
        $unifiedStubsAttributesParameterTypes = [];
        $unifiedReflectionParameterTypes = [];
        self::convertNullableTypesToUnion($parameter->typesFromSignature, $unifiedReflectionParameterTypes);
        if (!empty($stubParameter->typesFromSignature)) {
            self::convertNullableTypesToUnion($stubParameter->typesFromSignature, $unifiedStubsParameterTypes);
        }
        foreach ($stubParameter->typesFromAttribute as $languageVersion => $listOfTypes) {
            $unifiedStubsAttributesParameterTypes[$languageVersion] = [];
            self::convertNullableTypesToUnion($listOfTypes, $unifiedStubsAttributesParameterTypes[$languageVersion]);
        }
        $typesFromAttribute = [];
        $testCondition = AbstractBaseStubsTestCase::isReflectionTypesMatchSignature($unifiedReflectionParameterTypes, $unifiedStubsParameterTypes);
        if (!$testCondition) {
            if (!empty($unifiedStubsAttributesParameterTypes)) {
                $typesFromAttribute = !empty($unifiedStubsAttributesParameterTypes[getenv('PHP_VERSION')]) ?
                    $unifiedStubsAttributesParameterTypes[getenv('PHP_VERSION')] :
                    $unifiedStubsAttributesParameterTypes['default'];
                $testCondition = AbstractBaseStubsTestCase::isReflectionTypesExistInAttributes($unifiedReflectionParameterTypes, $typesFromAttribute);
            }
        }
        self::assertTrue($testCondition, "Type mismatch $functionName: \$$parameter->name \n
        Reflection parameter $parameter->name with index $parameter->indexInSignature has type '" . implode('|', $unifiedReflectionParameterTypes) .
            "' but stub parameter $stubParameter->name with index $stubParameter->indexInSignature has type '" . implode('|', $unifiedStubsParameterTypes) . "' in signature and " .
            implode('|', $typesFromAttribute) . ' in attribute');
    }
}
