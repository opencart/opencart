<?php
declare(strict_types=1);

namespace StubTests\Parsers\Visitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use function count;

/**
 * The visitor is required to provide "parent" attribute to nodes
 */
class ParentConnector extends NodeVisitorAbstract
{
    /**
     * @var Node[]
     */
    private array $stack;

    public function beforeTraverse(array $nodes): void
    {
        $this->stack = [];
    }

    public function enterNode(Node $node): void
    {
        if (!empty($this->stack)) {
            $node->setAttribute('parent', $this->stack[count($this->stack) - 1]);
        }
        $this->stack[] = $node;
    }

    public function leaveNode(Node $node): void
    {
        array_pop($this->stack);
    }
}
