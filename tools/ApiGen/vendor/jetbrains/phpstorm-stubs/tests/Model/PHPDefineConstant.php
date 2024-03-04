<?php

namespace StubTests\Model;

use PhpParser\Node\Expr\FuncCall;
use function in_array;
use function is_float;
use function is_resource;
use function is_string;

class PHPDefineConstant extends PHPConst
{
    /**
     * @param array $reflectionObject
     * @return static
     */
    public function readObjectFromReflection($reflectionObject)
    {
        if (is_string($reflectionObject[0])) {
            $this->name = mb_convert_encoding($reflectionObject[0], 'UTF-8');
        } else {
            $this->name = $reflectionObject[0];
        }
        $constantValue = $reflectionObject[1];
        if ($constantValue !== null) {
            if (is_resource($constantValue)) {
                $this->value = 'PHPSTORM_RESOURCE';
            } elseif (is_string($constantValue) || is_float($constantValue)) {
                $this->value = mb_convert_encoding((string)$constantValue, 'UTF-8');
            } else {
                $this->value = $constantValue;
            }
        } else {
            $this->value = null;
        }
        $this->visibility = 'public';
        return $this;
    }

    /**
     * @param FuncCall $node
     * @return static
     */
    public function readObjectFromStubNode($node)
    {
        $constName = $this->getConstantFQN($node, $node->args[0]->value->value);
        if (in_array($constName, ['null', 'true', 'false'])) {
            $constName = strtoupper($constName);
        }
        $this->name = $constName;
        $this->value = $this->getConstValue($node->args[1]);
        $this->visibility = 'public';
        $this->collectTags($node);
        return $this;
    }
}
