<?php
declare(strict_types=1);

namespace StubTests;

use PHPUnit\Framework\Exception;
use RuntimeException;
use StubTests\Model\PHPMethod;
use StubTests\Model\StubProblemType;
use StubTests\Parsers\Visitors\MetaOverrideFunctionsParser;
use StubTests\TestData\Providers\PhpStormStubsSingleton;
use StubTests\TestData\Providers\ReflectionStubsSingleton;
use function array_filter;
use function array_pop;

class StubsMetaInternalTagTest extends AbstractBaseStubsTestCase
{
    /**
     * @var string[]
     */
    private static array $overriddenFunctionsInMeta;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$overriddenFunctionsInMeta = (new MetaOverrideFunctionsParser())->overridenFunctions;
    }

    /**
     * @throws Exception
     */
    public function testFunctionInternalMetaTag(): void
    {
        $functions = PhpStormStubsSingleton::getPhpStormStubs()->getFunctions();
        foreach ($functions as $function) {
            if ($function->hasInternalMetaTag) {
                $reflectionFunctions = array_filter(
                    ReflectionStubsSingleton::getReflectionStubs()->getFunctions(),
                    fn ($refFunction) => $refFunction->name === $function->name
                );
                $reflectionFunction = array_pop($reflectionFunctions);
                if ($reflectionFunction !== null && !$reflectionFunction->hasMutedProblem(StubProblemType::ABSENT_IN_META)) {
                    self::checkInternalMetaInOverride($function->name);
                }
            }
        }
    }

    /**
     * @throws RuntimeException
     */
    public function testMethodsInternalMetaTag(): void
    {
        foreach (PhpStormStubsSingleton::getPhpStormStubs()->getClasses() as $className => $class) {
            foreach ($class->methods as $methodName => $method) {
                if ($method->hasInternalMetaTag) {
                    $refClass = ReflectionStubsSingleton::getReflectionStubs()->getClass($className);
                    if ($refClass !== null) {
                        $reflectionMethods = array_filter(
                            $refClass->methods,
                            fn ($refMethod) => $refMethod->name === $methodName
                        );
                        /** @var PHPMethod $reflectionMethod */
                        $reflectionMethod = array_pop($reflectionMethods);
                        if ($reflectionMethod->hasMutedProblem(StubProblemType::ABSENT_IN_META)) {
                            static::markTestSkipped('method intentionally not added to meta');
                        } else {
                            self::checkInternalMetaInOverride($className . '::' . $methodName);
                        }
                    }
                } else {
                    $this->expectNotToPerformAssertions();
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    private static function checkInternalMetaInOverride(string $elementName): void
    {
        self::assertContains(
            $elementName,
            self::$overriddenFunctionsInMeta,
            "$elementName contains @meta in phpdoc but isn't added to 'override()' functions in meta file"
        );
    }
}
