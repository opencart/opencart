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

use ScssPhp\ScssPhp\Util\EquatableUtil;
use ScssPhp\ScssPhp\Visitor\ModifiableCssVisitor;
use SourceSpan\FileSpan;

/**
 * A modifiable version of {@see CssKeyframeBlock} for use in the evaluation step.
 *
 * @internal
 */
final class ModifiableCssKeyframeBlock extends ModifiableCssParentNode implements CssKeyframeBlock
{
    /**
     * @var CssValue<list<string>>
     */
    private readonly CssValue $selector;

    private readonly FileSpan $span;

    /**
     * @param CssValue<list<string>> $selector
     */
    public function __construct(CssValue $selector, FileSpan $span)
    {
        parent::__construct();
        $this->selector = $selector;
        $this->span = $span;
    }

    public function getSelector(): CssValue
    {
        return $this->selector;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(ModifiableCssVisitor $visitor)
    {
        return $visitor->visitCssKeyframeBlock($this);
    }

    public function equalsIgnoringChildren(ModifiableCssNode $other): bool
    {
        return $other instanceof ModifiableCssKeyframeBlock && EquatableUtil::listEquals($this->selector->getValue(), $other->selector->getValue());
    }

    public function copyWithoutChildren(): ModifiableCssKeyframeBlock
    {
        return new ModifiableCssKeyframeBlock($this->selector, $this->span);
    }
}
