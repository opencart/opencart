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

namespace ScssPhp\ScssPhp\Ast\Sass;

use ScssPhp\ScssPhp\Util;
use ScssPhp\ScssPhp\Util\SpanUtil;
use SourceSpan\FileSpan;

/**
 * An argument declared as part of an {@see ArgumentDeclaration}.
 *
 * @internal
 */
final class Argument implements SassNode, SassDeclaration
{
    private readonly string $name;

    private readonly ?Expression $defaultValue;

    private readonly FileSpan $span;

    public function __construct(string $name, FileSpan $span, ?Expression $defaultValue = null)
    {
        $this->name = $name;
        $this->defaultValue = $defaultValue;
        $this->span = $span;
    }

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
        if ($this->defaultValue === null) {
            return $this->span->getText();
        }

        return Util::declarationName($this->span);
    }

    public function getNameSpan(): FileSpan
    {
        if ($this->defaultValue === null) {
            return $this->span;
        }

        return SpanUtil::initialIdentifier($this->span, 1);
    }

    public function getDefaultValue(): ?Expression
    {
        return $this->defaultValue;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function __toString(): string
    {
        if ($this->defaultValue === null) {
            return $this->name;
        }

        return $this->name . ': ' . $this->defaultValue;
    }
}
