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
use ScssPhp\ScssPhp\Ast\Sass\Statement;
use SourceSpan\FileSpan;

/**
 * An abstract class for callables (functions or mixins) that are declared in
 * user code.
 *
 * @extends ParentStatement<Statement[]>
 *
 * @internal
 */
abstract class CallableDeclaration extends ParentStatement
{
    private readonly string $name;

    private readonly string $originalName;

    private readonly ArgumentDeclaration $arguments;

    private readonly ?SilentComment $comment;

    private readonly FileSpan $span;

    /**
     * @param Statement[] $children
     */
    public function __construct(string $originalName, ArgumentDeclaration $arguments, FileSpan $span, array $children, ?SilentComment $comment = null)
    {
        $this->originalName = $originalName;
        $this->name = str_replace('_', '-', $originalName);
        $this->arguments = $arguments;
        $this->comment = $comment;
        $this->span = $span;
        parent::__construct($children);
    }

    /**
     * The name of this callable, with underscores converted to hyphens.
     */
    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * The callable's original name, without underscores converted to hyphens.
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    final public function getArguments(): ArgumentDeclaration
    {
        return $this->arguments;
    }

    final public function getComment(): ?SilentComment
    {
        return $this->comment;
    }

    final public function getSpan(): FileSpan
    {
        return $this->span;
    }
}
