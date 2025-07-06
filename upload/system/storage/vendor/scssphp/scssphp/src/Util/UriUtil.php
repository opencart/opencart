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

namespace ScssPhp\ScssPhp\Util;

use League\Uri\BaseUri;
use League\Uri\Contracts\UriInterface;

/**
 * @internal
 */
final class UriUtil
{
    public static function resolve(UriInterface $baseUrl, string $reference): UriInterface
    {
        $resolvedUri = BaseUri::from($baseUrl)->resolve($reference)->getUri();

        \assert($resolvedUri instanceof UriInterface);

        return $resolvedUri;
    }

    public static function resolveUri(UriInterface $baseUrl, UriInterface $url): UriInterface
    {
        $resolvedUri = BaseUri::from($baseUrl)->resolve($url)->getUri();

        \assert($resolvedUri instanceof UriInterface);

        return $resolvedUri;
    }
}
