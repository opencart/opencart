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

namespace ScssPhp\ScssPhp\Ast\Sass\Statement;

use ScssPhp\ScssPhp\Ast\Sass\Expression;
use ScssPhp\ScssPhp\Ast\Sass\Statement;
use ScssPhp\ScssPhp\Visitor\StatementVisitor;
use SourceSpan\FileSpan;

/**
 * A `@while` rule.
 *
 * This repeatedly executes a block of code as long as a statement evaluates to
 * `true`.
 *
 * @extends ParentStatement<Statement[]>
 *
 * @internal
 */
final class WhileRule extends ParentStatement
{
    private readonly Expression $condition;

    private readonly FileSpan $span;

    /**
     * @param Statement[] $children
     */
    public function __construct(Expression $condition, array $children, FileSpan $span)
    {
        $this->condition = $condition;
        $this->span = $span;
        parent::__construct($children);
    }

    public function getCondition(): Expression
    {
        return $this->condition;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitWhileRule($this);
    }

    public function __toString(): string
    {
        return '@while ' . $this->condition . ' {' . implode(' ', $this->getChildren()) . '}';
    }
}
