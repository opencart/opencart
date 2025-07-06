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

use League\Uri\Contracts\UriInterface;
use League\Uri\Uri;
use ScssPhp\ScssPhp\Ast\Sass\Expression\StringExpression;
use ScssPhp\ScssPhp\Ast\Sass\Import;
use SourceSpan\FileSpan;

/**
 * An import that will load a Sass file at runtime.
 *
 * @internal
 */
final class DynamicImport implements Import
{
    /**
     * The URI of the file to import.
     *
     * If this is relative, it's relative to the containing file.
     */
    private readonly string $urlString;

    private readonly FileSpan $span;

    public function __construct(string $urlString, FileSpan $span)
    {
        $this->urlString = $urlString;
        $this->span = $span;
    }

    public function getUrl(): UriInterface
    {
        return Uri::new($this->urlString);
    }

    public function getUrlString(): string
    {
        return $this->urlString;
    }

    public function getSpan(): FileSpan
    {
        return $this->span;
    }

    public function __toString(): string
    {
        return StringExpression::quoteText($this->urlString);
    }
}
