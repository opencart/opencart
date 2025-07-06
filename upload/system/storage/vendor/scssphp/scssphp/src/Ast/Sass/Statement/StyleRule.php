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
 * A style rule.
 *
 * This applies style declarations to elements that match a given selector.
 *
 * @extends ParentStatement<Statement[]>
 *
 * @internal
 */
final class StyleRule extends ParentStatement
{
    private readonly Interpolation $selector;

    private readonly FileSpan $span;

    /**
     * @param Statement[] $children
     */
    public function __construct(Interpolation $selector, array $children, FileSpan $span)
    {
        $this->selector = $selector;
        $this->span = $span;
        parent::__construct($children);
    }

    /**
     * The selector to which the declaration will be applied.
     *
     * This is only parsed after the interpolation has been resolved.
     */
    public function getSelector(): Interpolation
    {
        return $this->selector;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitStyleRule($this);
    }

    public function __toString(): string
    {
        return $this->selector . ' {' . implode(' ', $this->getChildren()) . '}';
    }
}
