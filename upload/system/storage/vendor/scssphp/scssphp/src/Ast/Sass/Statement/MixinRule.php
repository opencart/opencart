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

use ScssPhp\ScssPhp\Ast\Sass\ArgumentDeclaration;
use ScssPhp\ScssPhp\Ast\Sass\SassDeclaration;
use ScssPhp\ScssPhp\Ast\Sass\Statement;
use ScssPhp\ScssPhp\Util\SpanUtil;
use ScssPhp\ScssPhp\Visitor\StatementVisitor;
use SourceSpan\FileSpan;

/**
 * A mixin declaration.
 *
 * This declares a mixin that's invoked using `@include`.
 *
 * @internal
 */
final class MixinRule extends CallableDeclaration implements SassDeclaration
{
    /**
     * Whether the mixin contains a `@content` rule.
     */
    private ?bool $content = null;

    /**
     * @param Statement[] $children
     */
    public function __construct(string $name, ArgumentDeclaration $arguments, FileSpan $span, array $children, ?SilentComment $comment = null)
    {
        parent::__construct($name, $arguments, $span, $children, $comment);
    }

    public function hasContent(): bool
    {
        if (!isset($this->content)) {
            $this->content = (new HasContentVisitor())->visitMixinRule($this) === true;
        }

        return $this->content;
    }

    public function getNameSpan(): FileSpan
    {
        $startSpan = $this->getSpan()->getText()[0] === '='
            ? SpanUtil::trimLeft($this->getSpan()->subspan(1))
            : SpanUtil::withoutInitialAtRule($this->getSpan());

        return SpanUtil::initialIdentifier($startSpan);
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitMixinRule($this);
    }

    public function __toString(): string
    {
        $buffer = '@mixin ' . $this->getName();

        if (!$this->getArguments()->isEmpty()) {
            $buffer .= "({$this->getArguments()})";
        }

        $buffer .= ' {' . implode(' ', $this->getChildren()) . '}';

        return $buffer;
    }
}
