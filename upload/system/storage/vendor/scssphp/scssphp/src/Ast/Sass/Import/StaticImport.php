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

namespace ScssPhp\ScssPhp\Ast\Sass\Import;

use ScssPhp\ScssPhp\Ast\Sass\Import;
use ScssPhp\ScssPhp\Ast\Sass\Interpolation;
use SourceSpan\FileSpan;

/**
 * An import that produces a plain CSS `@import` rule.
 *
 * @internal
 */
final class StaticImport implements Import
{
    /**
     * The URL for this import.
     *
     * This already contains quotes.
     */
    private readonly Interpolation $url;

    /**
     * The modifiers (such as media or supports queries) attached to this import,
     * or `null` if none are attached.
     */
    private readonly ?Interpolation $modifiers;

    private readonly FileSpan $span;

    public function __construct(Interpolation $url, FileSpan $span, ?Interpolation $modifiers = null)
    {
        $this->url = $url;
        $this->span = $span;
        $this->modifiers = $modifiers;
    }

    public function getUrl(): Interpolation
    {
        return $this->url;
    }

    public function getModifiers(): ?Interpolation
    {
        return $this->modifiers;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function __toString(): string
    {
        $buffer = (string) $this->url;

        if ($this->modifiers !== null) {
            $buffer .= ' ' . $this->modifiers;
        }

        return $buffer;
    }
}
