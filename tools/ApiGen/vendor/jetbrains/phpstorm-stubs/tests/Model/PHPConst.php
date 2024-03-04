<?php

namespace StubTests\Model;

use Exception;
use PhpParser\Node\Const_;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeAbstract;
use ReflectionClassConstant;
use stdClass;
use function in_array;

class PHPConst extends BasePHPElement
{
    /**
     * @var string|null
     */
    public $parentName;

    /**
     * @var bool|int|string|float|null
     */
    public $value;

    /**
     * @var string|null
     */
    public $visibility;

    /**
     * @param ReflectionClassConstant $reflectionObject
     * @return static
     */
    public function readObjectFromReflection($reflectionObject)
    {
        $this->name = $reflectionObject->name;
        $this->value = $reflectionObject->getValue();
        if ($reflectionObject->isPrivate()) {
            $this->visibility = 'private';
        } elseif ($reflectionObject->isProtected()) {
            $this->visibility = 'protected';
        } else {
            $this->visibility = 'public';
        }
        return $this;
    }

    /**
     * @param Const_ $node
     * @return static
     */
    public function readObjectFromStubNode($node)
    {
        $this->name = $this->getConstantFQN($node, $node->name->name);
        $this->value = $this->getConstValue($node);
        $this->collectTags($node);
        $parentNode = $node->getAttribute('parent');
        if (property_exists($parentNode, 'attrGroups')) {
            $this->availableVersionsRangeFromAttribute = self::findAvailableVersionsRangeFromAttribute($parentNode->attrGroups);
        }
        if ($parentNode instanceof ClassConst) {
            if ($parentNode->isPrivate()) {
                $this->visibility = 'private';
            } elseif ($parentNode->isProtected()) {
                $this->visibility = 'protected';
            } else {
                $this->visibility = 'public';
            }
            $this->parentName = self::getFQN($parentNode->getAttribute('parent'));
        }
        return $this;
    }

    /**
     * @param $node
     * @return int|string|bool|float|null
     */
    protected function getConstValue($node)
    {
        if (in_array('value', $node->value->getSubNodeNames(), true)) {
            return $node->value->value;
        }
        if (in_array('expr', $node->value->getSubNodeNames(), true)) {
            if ($node->value instanceof UnaryMinus) {
                return -$node->value->expr->value;
            } elseif ($node->value instanceof Cast && $node->value->expr instanceof ConstFetch) {
                return $node->value->expr->name->parts[0];
            }
            return $node->value->expr->value;
        }
        if (in_array('name', $node->value->getSubNodeNames(), true)) {
            $value = isset($node->value->name->parts[0]) ? $node->value->name->parts[0] : $node->value->name->name;
            return $value === 'null' ? null : $value;
        }
        return null;
    }

    /**
     * @param NodeAbstract $node
     * @param string $nodeName
     * @return string
     */
    protected function getConstantFQN(NodeAbstract $node, $nodeName)
    {
        $namespace = '';
        $parentParentNode = $node->getAttribute('parent')->getAttribute('parent');
        if ($parentParentNode instanceof Namespace_ && !empty($parentParentNode->name)) {
            $namespace = '\\' . implode('\\', $parentParentNode->name->parts) . '\\';
        }

        return $namespace . $nodeName;
    }

    /**
     * @param stdClass|array $jsonData
     * @throws Exception
     */
    public function readMutedProblems($jsonData)
    {
        foreach ($jsonData as $constant) {
            if ($constant->name === $this->name && !empty($constant->problems)) {
                foreach ($constant->problems as $problem) {
                    switch ($problem->description) {
                        case 'wrong value':
                            $this->mutedProblems[StubProblemType::WRONG_CONSTANT_VALUE] = $problem->versions;
                            break;
                        case 'missing constant':
                            $this->mutedProblems[StubProblemType::STUB_IS_MISSED] = $problem->versions;
                            break;
                        default:
                            throw new Exception("Unexpected value $problem->description");
                    }
                }
            }
        }
    }
}
