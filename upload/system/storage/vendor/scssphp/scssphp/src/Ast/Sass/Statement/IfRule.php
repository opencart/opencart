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
use ScssPhp\ScssPhp\Visitor\StatementVisitor;
use SourceSpan\FileSpan;

/**
 * An `@if` rule.
 *
 * This conditionally executes a block of code.
 *
 * @internal
 */
final class IfRule implements Statement
{
    /**
     * @var list<IfClause>
     */
    private readonly array $clauses;

    private readonly ?ElseClause $lastClause;

    private readonly FileSpan $span;

    /**
     * @param list<IfClause> $clauses
     */
    public function __construct(array $clauses, FileSpan $span, ?ElseClause $lastClause = null)
    {
        $this->clauses = $clauses;
        $this->span = $span;
        $this->lastClause = $lastClause;
    }

    /**
     * The `@if` and `@else if` clauses.
     *
     * The first clause whose expression evaluates to `true` will have its
     * statements executed. If no expression evaluates to `true`, `lastClause`
     * will be executed if it's not `null`.
     *
     * @return list<IfClause>
     */
    public function getClauses(): array
    {
        return $this->clauses;
    }

    /**
     * The final, unconditional `@else` clause.
     *
     * This is `null` if there is no unconditional `@else`.
     */
    public function getLastClause(): ?ElseClause
    {
        return $this->lastClause;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitIfRule($this);
    }

    public function __toString(): string
    {
        $parts = [];

        foreach ($this->clauses as $index => $clause) {
            $parts[] = ($index === 0 ? '@if ' : '@else if ') . $clause->getExpression() . '{' . implode(' ', $clause->getChildren()) . '}';
        }

        if ($this->lastClause !== null) {
            $parts[] = $this->lastClause;
        }

        return implode(' ', $parts);
    }
}
