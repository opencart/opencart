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
 * An importer that never imports any stylesheets.
 *
 * This is used for stylesheets which don't support relative imports, such as
 * those created from PHP code with plain strings.
 */
final class NoOpImporter extends Importer
{
    public function canonicalize(UriInterface $url): ?UriInterface
    {
        return null;
    }

    public function load(UriInterface $url): ?ImporterResult
    {
        return null;
    }

    public function couldCanonicalize(UriInterface $url, UriInterface $canonicalUrl): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return '(unknown)';
    }
}
