<?php

namespace StubTests\Model;

use Exception;
use PhpParser\Node\Stmt\Interface_;
use ReflectionClass;
use stdClass;

class PHPInterface extends BasePHPClass
{
    public $parentInterfaces = [];

    /**
     * @param ReflectionClass $reflectionObject
     * @return static
     */
    public function readObjectFromReflection($reflectionObject)
    {
        $this->name = $reflectionObject->getName();
        foreach ($reflectionObject->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() !== $this->name) {
                continue;
            }
            $this->methods[$method->name] = (new PHPMethod())->readObjectFromReflection($method);
        }
        $this->parentInterfaces = $reflectionObject->getInterfaceNames();
        if (method_exists($reflectionObject, 'getReflectionConstants')) {
            foreach ($reflectionObject->getReflectionConstants() as $constant) {
                if ($constant->getDeclaringClass()->getName() !== $this->name) {
                    continue;
                }
                $this->constants[$constant->name] = (new PHPConst())->readObjectFromReflection($constant);
            }
        }
        return $this;
    }

    /**
     * @param Interface_ $node
     * @return static
     */
    public function readObjectFromStubNode($node)
    {
        $this->name = self::getFQN($node);
        $this->collectTags($node);
        $this->availableVersionsRangeFromAttribute = self::findAvailableVersionsRangeFromAttribute($node->attrGroups);
        if (!empty($node->extends)) {
            foreach ($node->extends as $extend) {
                $this->parentInterfaces[] = implode('\\', $extend->parts);
            }
        }
        return $this;
    }

    /**
     * @param stdClass|array $jsonData
     * @throws Exception
     */
    public function readMutedProblems($jsonData)
    {
        foreach ($jsonData as $interface) {
            if ($interface->name === $this->name) {
                if (!empty($interface->problems)) {
                    foreach ($interface->problems as $problem) {
                        switch ($problem->description) {
                            case 'wrong parent':
                                $this->mutedProblems[StubProblemType::WRONG_PARENT] = $problem->versions;
                                break;
                            case 'missing interface':
                                $this->mutedProblems[StubProblemType::STUB_IS_MISSED] = $problem->versions;
                                break;
                            default:
                                throw new Exception("Unexpected value $problem->description");
                        }
                    }
                }
                if (!empty($interface->methods)) {
                    foreach ($this->methods as $method) {
                        $method->readMutedProblems($interface->methods);
                    }
                }
                if (!empty($interface->constants)) {
                    foreach ($this->constants as $constant) {
                        $constant->readMutedProblems($interface->constants);
                    }
                }
            }
        }
    }
}
