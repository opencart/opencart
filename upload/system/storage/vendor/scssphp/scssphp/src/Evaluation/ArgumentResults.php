<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp\Evaluation;

use ScssPhp\ScssPhp\Ast\AstNode;
use ScssPhp\ScssPhp\Value\ListSeparator;
use ScssPhp\ScssPhp\Value\Value;

/**
 * The result of evaluating arguments to a function or mixin.
 *
 * @internal
 */
final class ArgumentResults
{
    /**
     * Arguments passed by position.
     *
     * @var list<Value>
     */
    private readonly array $positional;

    /**
     * The {@see AstNode}s that hold the spans for each {@see positional} argument.
     *
     * @var list<AstNode>
     */
    private readonly array $positionalNodes;

    /**
     * @var array<string, Value>
     */
    private readonly array $named;

    /**
     * The {@see AstNode}s that hold the spans for each {@see named} argument.
     *
     * @var array<string, AstNode>
     */
    private readonly array $namedNodes;

    private readonly ListSeparator $separator;

    /**
     * @param list<Value>            $positional
     * @param list<AstNode>          $positionalNodes
     * @param array<string, Value>   $named
     * @param array<string, AstNode> $namedNodes
     */
    public function __construct(array $positional, array $positionalNodes, array $named, array $namedNodes, ListSeparator $separator)
    {
        $this->positional = $positional;
        $this->positionalNodes = $positionalNodes;
        $this->named = $named;
        $this->namedNodes = $namedNodes;
        $this->separator = $separator;
    }

    /**
     * @return list<Value>
     */
    public function getPositional(): array
    {
        return $this->positional;
    }

    /**
     * @return list<AstNode>
     */
    public function getPositionalNodes(): array
    {
        return $this->positionalNodes;
    }

    /**
     * @return array<string, Value>
     */
    public function getNamed(): array
    {
        return $this->named;
    }

    /**
     * @return array<string, AstNode>
     */
    public function getNamedNodes(): array
    {
        return $this->namedNodes;
    }

    public function getSeparator(): ListSeparator
    {
        return $this->separator;
    }
}
