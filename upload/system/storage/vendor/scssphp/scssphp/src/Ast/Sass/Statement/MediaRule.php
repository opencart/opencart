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
 * A `@media` rule.
 *
 * @extends ParentStatement<Statement[]>
 *
 * @internal
 */
final class MediaRule extends ParentStatement
{
    private readonly Interpolation $query;

    private readonly FileSpan $span;

    /**
     * @param Statement[] $children
     */
    public function __construct(Interpolation $query, array $children, FileSpan $span)
    {
        $this->query = $query;
        $this->span = $span;
        parent::__construct($children);
    }

    /**
     * The query that determines on which platforms the styles will be in effect.
     *
     * This is only parsed after the interpolation has been resolved.
     */
    public function getQuery(): Interpolation
    {
        return $this->query;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitMediaRule($this);
    }

    public function __toString(): string
    {
        return '@media ' . $this->query . ' {' . implode(' ', $this->getChildren()) . '}';
    }
}
