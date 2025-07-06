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

namespace ScssPhp\ScssPhp\Importer;

use League\Uri\Contracts\UriInterface;

/**
 * @internal
 */
final class CanonicalizeContext
{
    private readonly ?UriInterface $containingUrl;
    private bool $fromImport;
    private bool $containingUrlAccessed = false;

    public function __construct(?UriInterface $containingUrl, bool $fromImport)
    {
        $this->containingUrl = $containingUrl;
        $this->fromImport = $fromImport;
    }

    /**
     * Whether the Sass compiler is currently evaluating an `@import` rule.
     */
    public function isFromImport(): bool
    {
        return $this->fromImport;
    }

    /**
     * @template T
     *
     * @param callable(): T $callback
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     */
    public function withFromImport(bool $fromImport, callable $callback)
    {
        $oldFromImport = $this->fromImport;
        $this->fromImport = $fromImport;

        try {
            return $callback();
        } finally {
            $this->fromImport = $oldFromImport;
        }
    }

    public function getContainingUrl(): ?UriInterface
    {
        $this->containingUrlAccessed = true;

        return $this->containingUrl;
    }

    /**
     * Whether {@see getContainingUrl} has been accessed.
     *
     * This is used to determine whether canonicalize result is cacheable.
     */
    public function wasContainingUrlAccessed(): bool
    {
        return $this->containingUrlAccessed;
    }
}
