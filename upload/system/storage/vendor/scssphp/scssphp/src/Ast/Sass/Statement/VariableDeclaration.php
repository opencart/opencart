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

use ScssPhp\ScssPhp\Ast\Sass\Expression;
use ScssPhp\ScssPhp\Ast\Sass\SassDeclaration;
use ScssPhp\ScssPhp\Ast\Sass\Statement;
use ScssPhp\ScssPhp\Util;
use ScssPhp\ScssPhp\Util\SpanUtil;
use ScssPhp\ScssPhp\Visitor\StatementVisitor;
use SourceSpan\FileSpan;

/**
 * A variable declaration.
 *
 * This defines or sets a variable.
 *
 * @internal
 */
final class VariableDeclaration implements Statement, SassDeclaration
{
    private readonly ?string $namespace;

    private readonly string $name;

    private readonly ?SilentComment $comment;

    private readonly Expression $expression;

    private readonly bool $guarded;

    private readonly bool $global;

    private readonly FileSpan $span;

    public function __construct(string $name, Expression $expression, FileSpan $span, ?string $namespace = null, bool $guarded = false, bool $global = false, ?SilentComment $comment = null)
    {
        $this->name = $name;
        $this->expression = $expression;
        $this->span = $span;
        $this->namespace = $namespace;
        $this->guarded = $guarded;
        $this->global = $global;
        $this->comment = $comment;

        if ($namespace !== null && $global) {
            throw new \InvalidArgumentException("Other modules' members can't be defined with !global.");
        }
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * The name of the variable, with underscores converted to hyphens.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * The variable name as written in the document, without underscores
     * converted to hyphens and including the leading `$`.
     *
     * This isn't particularly efficient, and should only be used for error
     * messages.
     */
    public function getOriginalName(): string
    {
        return Util::declarationName($this->span);
    }

    public function getComment(): ?SilentComment
    {
        return $this->comment;
    }

    public function getExpression(): Expression
    {
        return $this->expression;
    }

    public function isGuarded(): bool
    {
        return $this->guarded;
    }

    public function isGlobal(): bool
    {
        return $this->global;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function getNameSpan(): FileSpan
    {
        $span = $this->span;

        if ($this->namespace !== null) {
            $span = SpanUtil::withoutNamespace($span);
        }

        return SpanUtil::initialIdentifier($span, 1);
    }

    public function getNamespaceSpan(): ?FileSpan
    {
        if ($this->namespace === null) {
            return null;
        }

        return SpanUtil::initialIdentifier($this->span);
    }

    public function accept(StatementVisitor $visitor)
    {
        return $visitor->visitVariableDeclaration($this);
    }

    public function __toString(): string
    {
        $buffer = '';
        if ($this->namespace !== null) {
            $buffer .= $this->namespace . '.';
        }
        $buffer .= "\$$this->name: $this->expression;";

        return $buffer;
    }
}
