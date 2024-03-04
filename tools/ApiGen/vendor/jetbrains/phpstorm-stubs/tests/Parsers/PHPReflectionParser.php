<?php

namespace StubTests\Parsers;

use ReflectionClass;
use ReflectionFunction;
use StubTests\Model\CommonUtils;
use StubTests\Model\PHPClass;
use StubTests\Model\PHPDefineConstant;
use StubTests\Model\PHPFunction;
use StubTests\Model\PHPInterface;
use StubTests\Model\StubsContainer;

class PHPReflectionParser
{
    /**
     * @return StubsContainer
     * @throws \ReflectionException
     */
    public static function getStubs()
    {
        if (file_exists(__DIR__ . '/../../ReflectionData.json')) {
            $stubs = unserialize(file_get_contents(__DIR__ . '/../../ReflectionData.json'));
        } else {
            $stubs = new StubsContainer();

            $jsonData = json_decode(file_get_contents(__DIR__ . '/../TestData/mutedProblems.json'));
            $const_groups = get_defined_constants(true);
            unset($const_groups['user']);
            $const_groups = CommonUtils::flattenArray($const_groups, true);
            foreach ($const_groups as $name => $value) {
                $constant = (new PHPDefineConstant())->readObjectFromReflection([$name, $value]);
                $constant->readMutedProblems($jsonData->constants);
                $stubs->addConstant($constant);
            }

            foreach (get_defined_functions()['internal'] as $function) {
                $reflectionFunction = new ReflectionFunction($function);
                $phpFunction = (new PHPFunction())->readObjectFromReflection($reflectionFunction);
                $phpFunction->readMutedProblems($jsonData->functions);
                $stubs->addFunction($phpFunction);
            }

            foreach (get_declared_classes() as $clazz) {
                $reflectionClass = new ReflectionClass($clazz);
                if ($reflectionClass->isInternal()) {
                    $class = (new PHPClass())->readObjectFromReflection($reflectionClass);
                    $class->readMutedProblems($jsonData->classes);
                    $stubs->addClass($class);
                }
            }

            foreach (get_declared_interfaces() as $interface) {
                $reflectionInterface = new ReflectionClass($interface);
                if ($reflectionInterface->isInternal()) {
                    $phpInterface = (new PHPInterface())->readObjectFromReflection($reflectionInterface);
                    $phpInterface->readMutedProblems($jsonData->interfaces);
                    $stubs->addInterface($phpInterface);
                }
            }
        }

        return $stubs;
    }
}
