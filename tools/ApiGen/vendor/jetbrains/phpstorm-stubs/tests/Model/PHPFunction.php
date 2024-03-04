<?php

namespace StubTests\Model;

use Exception;
use JetBrains\PhpStorm\Deprecated;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use phpDocumentor\Reflection\Types\Compound;
use PhpParser\Comment\Doc;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\Function_;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use RuntimeException;
use stdClass;
use StubTests\Parsers\DocFactoryProvider;

class PHPFunction extends BasePHPElement
{
    /**
     * @var bool
     */
    public $isDeprecated;

    /**
     * @var PHPParameter[]
     */
    public $parameters = [];

    /** @var string[] */
    public $returnTypesFromPhpDoc = [];

    /** @var string[][] */
    public $returnTypesFromAttribute = [];

    /** @var string[] */
    public $returnTypesFromSignature = [];

    /**
     * @param ReflectionFunction|ReflectionFunctionAbstract $reflectionObject
     * @return static
     */
    public function readObjectFromReflection($reflectionObject)
    {
        $this->name = $reflectionObject->name;
        $this->isDeprecated = $reflectionObject->isDeprecated();
        foreach ($reflectionObject->getParameters() as $parameter) {
            $this->parameters[] = (new PHPParameter())->readObjectFromReflection($parameter);
        }
        if (method_exists($reflectionObject, 'getReturnType')) {
            $returnTypes = self::getReflectionTypeAsArray($reflectionObject->getReturnType());
        }
        if (!empty($returnTypes)) {
            array_push($this->returnTypesFromSignature, ...$returnTypes);
        }
        return $this;
    }

    /**
     * @param Function_ $node
     * @return static
     * @throws RuntimeException
     */
    public function readObjectFromStubNode($node)
    {
        $functionName = self::getFQN($node);
        $this->name = $functionName;
        $typesFromAttribute = self::findTypesFromAttribute($node->attrGroups);
        $this->availableVersionsRangeFromAttribute = self::findAvailableVersionsRangeFromAttribute($node->attrGroups);
        $this->returnTypesFromAttribute = $typesFromAttribute;
        array_push($this->returnTypesFromSignature, ...self::convertParsedTypeToArray($node->getReturnType()));
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

        $this->collectTags($node);
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

        $this->checkDeprecationTag($node);
        $this->checkReturnTag();
        return $this;
    }

    protected function checkDeprecationTag(FunctionLike $node)
    {
        $this->isDeprecated = self::hasDeprecatedAttribute($node) || !empty($this->deprecatedTags);
    }

    protected function checkReturnTag()
    {
        if (!empty($this->returnTags) && $this->returnTags[0] instanceof Return_) {
            $returnType = $this->returnTags[0]->getType();
            if ($returnType instanceof Compound) {
                foreach ($returnType as $nextType) {
                    $this->returnTypesFromPhpDoc[] = (string)$nextType;
                }
            } else {
                $this->returnTypesFromPhpDoc[] = (string)$returnType;
            }
        }
    }

    /**
     * @param stdClass|array $jsonData
     * @throws Exception
     */
    public function readMutedProblems($jsonData)
    {
        foreach ($jsonData as $function) {
            if ($function->name === $this->name) {
                if (!empty($function->problems)) {
                    foreach ($function->problems as $problem) {
                        switch ($problem->description) {
                            case 'parameter mismatch':
                                $this->mutedProblems[StubProblemType::FUNCTION_PARAMETER_MISMATCH] = $problem->versions;
                                break;
                            case 'missing function':
                                $this->mutedProblems[StubProblemType::STUB_IS_MISSED] = $problem->versions;
                                break;
                            case 'deprecated function':
                                $this->mutedProblems[StubProblemType::FUNCTION_IS_DEPRECATED] = $problem->versions;
                                break;
                            case 'absent in meta':
                                $this->mutedProblems[StubProblemType::ABSENT_IN_META] = $problem->versions;
                                break;
                            case 'has return typehint':
                                $this->mutedProblems[StubProblemType::FUNCTION_HAS_RETURN_TYPEHINT] = $problem->versions;
                                break;
                            case 'wrong return typehint':
                                $this->mutedProblems[StubProblemType::WRONG_RETURN_TYPEHINT] = $problem->versions;
                                break;
                            case 'has duplicate in stubs':
                                $this->mutedProblems[StubProblemType::HAS_DUPLICATION] = $problem->versions;
                                break;
                            case 'has type mismatch in signature and phpdoc':
                                $this->mutedProblems[StubProblemType::TYPE_IN_PHPDOC_DIFFERS_FROM_SIGNATURE] = $problem->versions;
                                break;
                            default:
                                throw new Exception("Unexpected value $problem->description");
                        }
                    }
                }
                if (!empty($function->parameters)) {
                    foreach ($this->parameters as $parameter) {
                        $parameter->readMutedProblems($function->parameters);
                    }
                }
            }
        }
    }

    /**
     * @param FunctionLike $node
     * @return bool
     */
    private static function hasDeprecatedAttribute(FunctionLike $node)
    {
        foreach ($node->getAttrGroups() as $group) {
            foreach ($group->attrs as $attr) {
                if ((string)$attr->name === Deprecated::class) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param Doc|null $docComment
     * @return bool
     */
    private static function hasDeprecatedDocTag($docComment)
    {
        $phpDoc = $docComment !== null ? DocFactoryProvider::getDocFactory()->create($docComment->getText()) : null;
        return $phpDoc !== null && !empty($phpDoc->getTagsByName('deprecated'));
    }
}
