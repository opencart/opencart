<?php
declare(strict_types=1);

namespace StubTests;

use PHPUnit\Framework\Exception;
use RuntimeException;
use StubTests\Model\PHPClass;
use StubTests\Model\PHPConst;
use StubTests\Model\PHPFunction;
use StubTests\Model\PHPInterface;
use StubTests\Model\PHPMethod;
use StubTests\Model\PHPParameter;
use StubTests\TestData\Providers\PhpStormStubsSingleton;

class StubsConstantsAndParametersValuesTest extends AbstractBaseStubsTestCase
{
    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionConstantsProvider::constantValuesProvider
     */
    public function testConstantsValues(PHPConst $constant): void
    {
        $constantName = $constant->name;
        $constantValue = $constant->value;
        $stubConstant = PhpStormStubsSingleton::getPhpStormStubs()->getConstant($constantName);
        self::assertEquals(
            $constantValue,
            $stubConstant->value,
            "Constant value mismatch: const $constantName \n
            Expected value: $constantValue but was $stubConstant->value"
        );
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionParametersProvider::functionOptionalParametersWithDefaultValueProvider
     * @throws Exception|RuntimeException
     */
    public function testFunctionsDefaultParametersValue(PHPFunction $function, PHPParameter $parameter)
    {
        $phpstormFunction = PhpStormStubsSingleton::getPhpStormStubs()->getFunction($function->name);
        $stubParameters = array_filter($phpstormFunction->parameters, fn (PHPParameter $stubParameter) => $stubParameter->indexInSignature === $parameter->indexInSignature);
        /** @var PHPParameter $stubOptionalParameter */
        $stubOptionalParameter = array_pop($stubParameters);
        $reflectionValue = AbstractBaseStubsTestCase::getStringRepresentationOfDefaultParameterValue($parameter->defaultValue);
        $stubValue = AbstractBaseStubsTestCase::getStringRepresentationOfDefaultParameterValue($stubOptionalParameter->defaultValue);
        self::assertEquals(
            $reflectionValue,
            $stubValue,
            sprintf(
                'Reflection function %s has optional parameter %s with default value "%s" but stub parameter has value "%s"',
                $function->name,
                $parameter->name,
                $reflectionValue,
                $stubValue
            )
        );
    }

    /**
     * @dataProvider \StubTests\TestData\Providers\Reflection\ReflectionParametersProvider::methodOptionalParametersWithDefaultValueProvider
     * @throws Exception|RuntimeException
     */
    public function testMethodsDefaultParametersValue(PHPClass|PHPInterface $class, PHPMethod $method, PHPParameter $parameter)
    {
        if ($class instanceof PHPClass) {
            $phpstormFunction = PhpStormStubsSingleton::getPhpStormStubs()->getClass($class->name)->getMethod($method->name);
        } else {
            $phpstormFunction = PhpStormStubsSingleton::getPhpStormStubs()->getInterface($class->name)->getMethod($method->name);
        }
        $stubParameters = array_filter($phpstormFunction->parameters, fn (PHPParameter $stubParameter) => $stubParameter->indexInSignature === $parameter->indexInSignature);
        /** @var PHPParameter $stubOptionalParameter */
        $stubOptionalParameter = array_pop($stubParameters);
        $reflectionValue = AbstractBaseStubsTestCase::getStringRepresentationOfDefaultParameterValue($parameter->defaultValue);
        $stubValue = AbstractBaseStubsTestCase::getStringRepresentationOfDefaultParameterValue($stubOptionalParameter->defaultValue, $class);
        self::assertEquals(
            $reflectionValue,
            $stubValue,
            sprintf(
                'Reflection method %s::%s has optional parameter %s with default value %s but stub parameter has value %s',
                $class->name,
                $method->name,
                $parameter->name,
                $reflectionValue,
                $stubValue
            )
        );
    }
}
