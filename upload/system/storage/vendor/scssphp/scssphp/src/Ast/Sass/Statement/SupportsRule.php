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

use ScssPhp\ScssPhp\Ast\Sass\Statement;
use ScssPhp\ScssPhp\Ast\Sass\SupportsCondition;
use ScssPhp\ScssPhp\Visitor\StatementVisitor;
use SourceSpan\FileSpan;

/**
 * A `@supports` rule.
 *
 * @extends ParentStatement<Statement[]>
 *
 * @internal
 */
final class SupportsRule extends ParentStatement
{
    private readonly SupportsCondition $condition;

    private readonly FileSpan $span;

    /**
     * @param Statement[] $children
     */
    public function __construct(SupportsCondition $condition, array $children, FileSpan $span)
    {
        $this->condition = $condition;
        $this->span = $span;
        parent::__construct($children);
    }

    public function getCondition(): SupportsCondition
    {
        return $this->condition;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitSupportsRule($this);
    }

    public function __toString(): string
    {
        return '@supports ' . $this->condition . ' {' . implode(' ', $this->getChildren()) . '}';
    }
}
