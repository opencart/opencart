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
 * A modifiable version of {@see CssSupportsRule} for use in the evaluation step.
 *
 * @internal
 */
final class ModifiableCssSupportsRule extends ModifiableCssParentNode implements CssSupportsRule
{
    /**
     * @var CssValue<string>
     */
    private readonly CssValue $condition;

    private readonly FileSpan $span;

    /**
     * @param CssValue<string> $condition
     */
    public function __construct(CssValue $condition, FileSpan $span)
    {
        parent::__construct();
        $this->condition = $condition;
        $this->span = $span;
    }

    public function getCondition(): CssValue
    {
        return $this->condition;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(ModifiableCssVisitor $visitor)
    {
        return $visitor->visitCssSupportsRule($this);
    }

    public function equalsIgnoringChildren(ModifiableCssNode $other): bool
    {
        return $other instanceof ModifiableCssSupportsRule && EquatableUtil::equals($this->condition, $other->condition);
    }

    public function copyWithoutChildren(): ModifiableCssSupportsRule
    {
        return new ModifiableCssSupportsRule($this->condition, $this->span);
    }
}
