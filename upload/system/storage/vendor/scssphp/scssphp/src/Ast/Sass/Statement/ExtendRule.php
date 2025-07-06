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
 * An `@extend` rule.
 *
 * This gives one selector all the styling of another.
 *
 * @internal
 */
final class ExtendRule implements Statement
{
    private readonly Interpolation $selector;

    private readonly FileSpan $span;

    private readonly bool $optional;

    public function __construct(Interpolation $selector, FileSpan $span, bool $optional = false)
    {
        $this->selector = $selector;
        $this->span = $span;
        $this->optional = $optional;
    }

    public function getSelector(): Interpolation
    {
        return $this->selector;
    }

    /**
     * Whether this is an optional extension.
     *
     * If an extension isn't optional, it will emit an error if it doesn't match
     * any selectors.
     */
    public function isOptional(): bool
    {
        return $this->optional;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitExtendRule($this);
    }

    public function __toString(): string
    {
        return '@extend ' . $this->selector . ($this->optional ? ' !optional' : '') . ';';
    }
}
