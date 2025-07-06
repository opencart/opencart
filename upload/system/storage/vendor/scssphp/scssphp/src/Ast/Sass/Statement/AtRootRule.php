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

use ScssPhp\ScssPhp\Ast\Sass\Interpolation;
use ScssPhp\ScssPhp\Ast\Sass\Statement;
use ScssPhp\ScssPhp\Visitor\StatementVisitor;
use SourceSpan\FileSpan;

/**
 * A `@at-root` rule.
 *
 * This moves it contents "up" the tree through parent nodes.
 *
 * @extends ParentStatement<Statement[]>
 *
 * @internal
 */
final class AtRootRule extends ParentStatement
{
    private readonly ?Interpolation $query;

    private readonly FileSpan $span;

    /**
     * @param Statement[] $children
     */
    public function __construct(array $children, FileSpan $span, ?Interpolation $query = null)
    {
        $this->query = $query;
        $this->span = $span;
        parent::__construct($children);
    }

    /**
     * The query specifying which statements this should move its contents through.
     */
    public function getQuery(): ?Interpolation
    {
        return $this->query;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitAtRootRule($this);
    }

    public function __toString(): string
    {
        $buffer = '@at-root ';
        if ($this->query !== null) {
            $buffer .= $this->query . ' ';
        }

        return $buffer . '{' . implode(' ', $this->getChildren()) . '}';
    }
}
