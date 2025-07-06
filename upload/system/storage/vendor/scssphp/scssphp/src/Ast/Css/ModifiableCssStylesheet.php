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

namespace ScssPhp\ScssPhp\Ast\Css;

use ScssPhp\ScssPhp\Visitor\ModifiableCssVisitor;
use SourceSpan\FileSpan;

/**
 * A modifiable version of {@see CssStylesheet} for use in the evaluation step.
 *
 * @internal
 */
final class ModifiableCssStylesheet extends ModifiableCssParentNode implements CssStylesheet
{
    private readonly FileSpan $span;

    /**
     * @param list<ModifiableCssNode> $children
     */
    public function __construct(FileSpan $span, array $children = [])
    {
        parent::__construct($children);
        $this->span = $span;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(ModifiableCssVisitor $visitor)
    {
        return $visitor->visitCssStylesheet($this);
    }

    public function equalsIgnoringChildren(ModifiableCssNode $other): bool
    {
        return $other instanceof ModifiableCssStylesheet;
    }

    public function copyWithoutChildren(): ModifiableCssStylesheet
    {
        return new ModifiableCssStylesheet($this->span);
    }
}
