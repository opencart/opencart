<?php

namespace StubTests\Model;

use Exception;
use phpDocumentor\Reflection\DocBlock\Tags\PropertyRead;
use phpDocumentor\Reflection\DocBlockFactory;
use PhpParser\Node\Stmt\Class_;
use ReflectionClass;
use RuntimeException;
use stdClass;
use function array_key_exists;
use function assert;
use function count;

class PHPClass extends BasePHPClass
{
    /**
     * @var false|string|null
     */
    public $parentClass;
    public $interfaces = [];

    /** @var PHPProperty[] */
    public $properties = [];
    public $isReadonly = false;

    /**
     * @param ReflectionClass $reflectionObject
     * @return static
     */
    public function readObjectFromReflection($reflectionObject)
    {
        $this->name = $reflectionObject->getName();
        $parent = $reflectionObject->getParentClass();
        if ($parent !== false) {
            $this->parentClass = $parent->getName();
        }
        $this->interfaces = $reflectionObject->getInterfaceNames();
        $this->isFinal = $reflectionObject->isFinal();
        if (method_exists($reflectionObject, 'isReadOnly')) {
            $this->isReadonly = $reflectionObject->isReadOnly();
        }
        foreach ($reflectionObject->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() !== $this->name) {
                continue;
            }
            $parsedMethod = (new PHPMethod())->readObjectFromReflection($method);
            $this->addMethod($parsedMethod);
        }

        if (method_exists($reflectionObject, 'getReflectionConstants')) {
            foreach ($reflectionObject->getReflectionConstants() as $constant) {
                if ($constant->getDeclaringClass()->getName() !== $this->name) {
                    continue;
                }
                $parsedConstant = (new PHPConst())->readObjectFromReflection($constant);
                $this->addConstant($parsedConstant);
            }
        }

        foreach ($reflectionObject->getProperties() as $property) {
            if ($property->getDeclaringClass()->getName() !== $this->name) {
                continue;
            }
            $parsedProperty = (new PHPProperty())->readObjectFromReflection($property);
            $this->addProperty($parsedProperty);
        }
        return $this;
    }

    /**
     * @param Class_ $node
     * @return static
     */
    public function readObjectFromStubNode($node)
    {
        $this->name = self::getFQN($node);
        $this->isFinal = $node->isFinal();
        $this->availableVersionsRangeFromAttribute = self::findAvailableVersionsRangeFromAttribute($node->attrGroups);
        $this->collectTags($node);
        if (!empty($node->extends)) {
            $this->parentClass = '';
            foreach ($node->extends->parts as $part) {
                $this->parentClass .= "\\$part";
            }
            $this->parentClass = ltrim($this->parentClass, "\\");
        }
        if (!empty($node->implements)) {
            foreach ($node->implements as $interfaceObject) {
                $interfaceFQN = '';
                foreach ($interfaceObject->parts as $interface) {
                    $interfaceFQN .= "\\$interface";
                }
                $this->interfaces[] = ltrim($interfaceFQN, "\\");
            }
        }
        foreach ($node->getProperties() as $property) {
            $parsedProperty = (new PHPProperty($this->name))->readObjectFromStubNode($property);
            $this->addProperty($parsedProperty);
        }
        if ($node->getDocComment() !== null) {
            $docBlock = DocBlockFactory::createInstance()->create($node->getDocComment()->getText());
            /** @var PropertyRead[] $properties */
            $properties = array_merge(
                $docBlock->getTagsByName('property-read'),
                $docBlock->getTagsByName('property')
            );
            foreach ($properties as $property) {
                $propertyName = $property->getVariableName();
                assert($propertyName !== '', "@property name is empty in class $this->name");
                $newProperty = new PHPProperty($this->name);
                $newProperty->is_static = false;
                $newProperty->access = 'public';
                $newProperty->name = $propertyName;
                $newProperty->parentName = $this->name;
                $newProperty->typesFromSignature = self::convertParsedTypeToArray($property->getType());
                assert(
                    !array_key_exists($propertyName, $this->properties),
                    "Property '$propertyName' is already declared in class '$this->name'"
                );
                $this->properties[$propertyName] = $newProperty;
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
        foreach ($jsonData as $class) {
            if ($class->name === $this->name) {
                if (!empty($class->problems)) {
                    foreach ($class->problems as $problem) {
                        switch ($problem->description) {
                            case 'wrong parent':
                                $this->mutedProblems[StubProblemType::WRONG_PARENT] = $problem->versions;
                                break;
                            case 'wrong interface':
                                $this->mutedProblems[StubProblemType::WRONG_INTERFACE] = $problem->versions;
                                break;
                            case 'missing class':
                                $this->mutedProblems[StubProblemType::STUB_IS_MISSED] = $problem->versions;
                                break;
                            case 'has wrong final modifier':
                                $this->mutedProblems[StubProblemType::WRONG_FINAL_MODIFIER] = $problem->versions;
                                break;
                            default:
                                throw new Exception("Unexpected value $problem->description");
                        }
                    }
                }
                if (!empty($class->methods)) {
                    foreach ($this->methods as $method) {
                        $method->readMutedProblems($class->methods);
                    }
                }
                if (!empty($class->constants)) {
                    foreach ($this->constants as $constant) {
                        $constant->readMutedProblems($class->constants);
                    }
                }
                if (!empty($class->properties)) {
                    foreach ($this->properties as $property) {
                        $property->readMutedProblems($class->properties);
                    }
                }
                return;
            }
        }
    }

    public function addProperty(PHPProperty $parsedProperty)
    {
        if (isset($parsedProperty->name)) {
            if (array_key_exists($parsedProperty->name, $this->properties)) {
                $amount = count(array_filter(
                    $this->properties,
                    function (PHPProperty $nextProperty) use ($parsedProperty) {
                        return $nextProperty->name === $parsedProperty->name;
                    }
                ));
                $this->properties[$parsedProperty->name . '_duplicated_' . $amount] = $parsedProperty;
            } else {
                $this->properties[$parsedProperty->name] = $parsedProperty;
            }
        }
    }

    /**
     * @return PHPProperty|null
     * @throws RuntimeException
     */
    public function getProperty($propertyName)
    {
        $properties = array_filter($this->properties, function (PHPProperty $property) use ($propertyName) {
            return $property->name === $propertyName && $property->duplicateOtherElement === false
                && BasePHPElement::entitySuitsCurrentPhpVersion($property);
        });
        if (empty($properties)) {
            throw new RuntimeException("Property $propertyName not found in stubs for set language version");
        }
        return array_pop($properties);
    }
}
