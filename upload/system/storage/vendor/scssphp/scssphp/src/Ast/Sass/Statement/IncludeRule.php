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

use ScssPhp\ScssPhp\Ast\Sass\ArgumentInvocation;
use ScssPhp\ScssPhp\Ast\Sass\CallableInvocation;
use ScssPhp\ScssPhp\Ast\Sass\SassReference;
use ScssPhp\ScssPhp\Ast\Sass\Statement;
use ScssPhp\ScssPhp\Util\SpanUtil;
use ScssPhp\ScssPhp\Visitor\StatementVisitor;
use SourceSpan\FileSpan;

/**
 * A mixin invocation.
 *
 * @internal
 */
final class IncludeRule implements Statement, CallableInvocation, SassReference
{
    private readonly ?string $namespace;

    private readonly string $name;

    private readonly string $originalName;

    private readonly ArgumentInvocation $arguments;

    private readonly ?ContentBlock $content;

    private readonly FileSpan $span;

    public function __construct(string $originalName, ArgumentInvocation $arguments, FileSpan $span, ?string $namespace = null, ?ContentBlock $content = null)
    {
        $this->originalName = $originalName;
        $this->name = str_replace('_', '-', $originalName);
        $this->arguments = $arguments;
        $this->span = $span;
        $this->namespace = $namespace;
        $this->content = $content;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * The original name of the mixin being invoked, without underscores
     * converted to hyphens.
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getArguments(): ArgumentInvocation
    {
        return $this->arguments;
    }

    public function getContent(): ?ContentBlock
    {
        return $this->content;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function getSpanWithoutContent(): FileSpan
    {
        if ($this->content === null) {
            return $this->span;
        }

        return SpanUtil::trim($this->span->getFile()->span($this->span->getStart()->getOffset(), $this->arguments->getSpan()->getEnd()->getOffset()));
    }

    public function getNameSpan(): FileSpan
    {
        $startSpan = $this->span->getText()[0] === '+' ? SpanUtil::trimLeft($this->span->subspan(1)) : SpanUtil::withoutInitialAtRule($this->span);

        if ($this->namespace !== null) {
            $startSpan = SpanUtil::withoutNamespace($startSpan);
        }

        return SpanUtil::initialIdentifier($startSpan);
    }

    public function getNamespaceSpan(): ?FileSpan
    {
        if ($this->namespace === null) {
            return null;
        }

        $startSpan = $this->span->getText()[0] === '+'
            ? SpanUtil::trimLeft($this->span->subspan(1))
            : SpanUtil::withoutInitialAtRule($this->span);

        return SpanUtil::initialIdentifier($startSpan);
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitIncludeRule($this);
    }

    public function __toString(): string
    {
        $buffer = '@include ';

        if ($this->namespace !== null) {
            $buffer .= $this->namespace . '.';
        }
        $buffer .= $this->name;

        if (!$this->arguments->isEmpty()) {
            $buffer .= "($this->arguments)";
        }

        $buffer .= $this->content === null ? ';' : ' ' . $this->content;

        return $buffer;
    }
}
