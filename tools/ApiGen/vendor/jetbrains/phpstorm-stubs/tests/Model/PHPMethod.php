<?php

namespace StubTests\Model;

use Exception;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use PhpParser\Node\Stmt\ClassMethod;
use ReflectionMethod;
use RuntimeException;
use stdClass;
use function strlen;

class PHPMethod extends PHPFunction
{
    /**
     * @var string
     */
    public $access;

    /**
     * @var bool
     */
    public $isStatic;

    /**
     * @var bool
     */
    public $isFinal;

    /**
     * @var string
     */
    public $parentName;

    /**
     * @var bool
     */
    public $isReturnTypeTentative;

    /**
     * @param ReflectionMethod $reflectionObject
     * @return static
     */
    public function readObjectFromReflection($reflectionObject)
    {
        parent::readObjectFromReflection($reflectionObject);
        $this->isStatic = $reflectionObject->isStatic();
        $this->isFinal = $reflectionObject->isFinal();
        $this->parentName = $reflectionObject->class;
        if ($reflectionObject->isProtected()) {
            $access = 'protected';
        } elseif ($reflectionObject->isPrivate()) {
            $access = 'private';
        } else {
            $access = 'public';
        }
        $this->access = $access;
        if (method_exists($reflectionObject, 'hasTentativeReturnType')) {
            $this->isReturnTypeTentative = $reflectionObject->hasTentativeReturnType();
            if ($this->isReturnTypeTentative) {
                $returnTypes = self::getReflectionTypeAsArray($reflectionObject->getTentativeReturnType());
                if (!empty($returnTypes)) {
                    array_push($this->returnTypesFromSignature, ...$returnTypes);
                }
            }
        } else {
            $this->isReturnTypeTentative = false;
        }
        return $this;
    }

    /**
     * @param ClassMethod $node
     * @return static
     * @throws RuntimeException
     */
    public function readObjectFromStubNode($node)
    {
        $this->parentName = self::getFQN($node->getAttribute('parent'));
        $this->name = $node->name->name;
        $typesFromAttribute = self::findTypesFromAttribute($node->attrGroups);
        $this->isReturnTypeTentative = self::hasTentativeTypeAttribute($node->attrGroups);
        $this->availableVersionsRangeFromAttribute = self::findAvailableVersionsRangeFromAttribute($node->attrGroups);
        $this->returnTypesFromAttribute = $typesFromAttribute;
        array_push($this->returnTypesFromSignature, ...self::convertParsedTypeToArray($node->getReturnType()));
        $this->collectTags($node);
        $this->checkDeprecationTag($node);
        $this->checkReturnTag();

        if (strncmp($this->name, 'PS_UNRESERVE_PREFIX_', 20) === 0) {
            $this->name = substr($this->name, strlen('PS_UNRESERVE_PREFIX_'));
        }
        $index = 0;
        foreach ($node->getParams() as $parameter) {
            $parsedParameter = (new PHPParameter())->readObjectFromStubNode($parameter);
            if (self::entitySuitsCurrentPhpVersion($parsedParameter)) {
                $parsedParameter->indexInSignature = $index;
                $addedParameters = array_filter($this->parameters, function (PHPParameter $addedParameter) use ($parsedParameter) {
                    return $addedParameter->name === $parsedParameter->name;
                });
                if (!empty($addedParameters)) {
                    if ($parsedParameter->is_vararg) {
                        $parsedParameter->isOptional = false;
                        $index--;
                        $parsedParameter->indexInSignature = $index;
                    }
                }
                $this->parameters[$parsedParameter->name] = $parsedParameter;
                $index++;
            }
        }

        foreach ($this->parameters as $parameter) {
            $relatedParamTags = array_filter($this->paramTags, function (Param $tag) use ($parameter) {
                return $tag->getVariableName() === $parameter->name;
            });
            /** @var Param $relatedParamTag */
            $relatedParamTag = array_pop($relatedParamTags);
            if ($relatedParamTag !== null) {
                $parameter->isOptional = $parameter->isOptional || str_contains((string)$relatedParamTag->getDescription(), '[optional]');
            }
        }

        $this->isFinal = $node->isFinal();
        $this->isStatic = $node->isStatic();
        if ($node->isPrivate()) {
            $this->access = 'private';
        } elseif ($node->isProtected()) {
            $this->access = 'protected';
        } else {
            $this->access = 'public';
        }
        return $this;
    }

    /**
     * @param stdClass|array $jsonData
     * @throws Exception
     */
    public function readMutedProblems($jsonData)
    {
        foreach ($jsonData as $method) {
            if ($method->name === $this->name) {
                if (!empty($method->problems)) {
                    foreach ($method->problems as $problem) {
                        switch ($problem->description) {
                            case 'parameter mismatch':
                                $this->mutedProblems[StubProblemType::FUNCTION_PARAMETER_MISMATCH] = $problem->versions;
                                break;
                            case 'missing method':
                                $this->mutedProblems[StubProblemType::STUB_IS_MISSED] = $problem->versions;
                                break;
                            case 'deprecated method':
                                $this->mutedProblems[StubProblemType::FUNCTION_IS_DEPRECATED] = $problem->versions;
                                break;
                            case 'absent in meta':
                                $this->mutedProblems[StubProblemType::ABSENT_IN_META] = $problem->versions;
                                break;
                            case 'wrong access':
                                $this->mutedProblems[StubProblemType::FUNCTION_ACCESS] = $problem->versions;
                                break;
                            case 'has duplicate in stubs':
                                $this->mutedProblems[StubProblemType::HAS_DUPLICATION] = $problem->versions;
                                break;
                            case 'has nullable typehint':
                                $this->mutedProblems[StubProblemType::HAS_NULLABLE_TYPEHINT] = $problem->versions;
                                break;
                            case 'has union typehint':
                                $this->mutedProblems[StubProblemType::HAS_UNION_TYPEHINT] = $problem->versions;
                                break;
                            case 'wrong return typehint':
                                $this->mutedProblems[StubProblemType::WRONG_RETURN_TYPEHINT] = $problem->versions;
                                break;
                            case 'has type mismatch in signature and phpdoc':
                                $this->mutedProblems[StubProblemType::TYPE_IN_PHPDOC_DIFFERS_FROM_SIGNATURE] = $problem->versions;
                                break;
                            case 'has wrong final modifier':
                                $this->mutedProblems[StubProblemType::WRONG_FINAL_MODIFIER] = $problem->versions;
                                break;
                            case 'has wrong static modifier':
                                $this->mutedProblems[StubProblemType::WRONG_STATIC_MODIFIER] = $problem->versions;
                                break;
                            default:
                                throw new Exception("Unexpected value $problem->description");
                        }
                    }
                }
                if (!empty($method->parameters)) {
                    foreach ($this->parameters as $parameter) {
                        $parameter->readMutedProblems($method->parameters);
                    }
                }
            }
        }
    }
}
