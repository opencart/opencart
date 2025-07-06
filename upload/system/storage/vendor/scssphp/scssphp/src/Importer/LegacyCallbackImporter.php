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
use ScssPhp\ScssPhp\Util\Path;

/**
 * @internal
 */
final class LegacyCallbackImporter extends Importer
{
    private readonly \Closure $callback;
    private readonly Importer $filesystemImporter;

    /**
     * @param \Closure(string): (string|null) $callback
     */
    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
        $this->filesystemImporter = new FilesystemImporter(null);
    }

    public function canonicalize(UriInterface $url): ?UriInterface
    {
        if ($url->getScheme() === 'file') {
            return $this->filesystemImporter->canonicalize($url);
        }

        $result = ($this->callback)((string) $url);

        if ($result === null) {
            return null;
        }

        $resultUrl = Path::toUri($result);

        return $this->filesystemImporter->canonicalize($resultUrl);
    }

    public function load(UriInterface $url): ?ImporterResult
    {
        return $this->filesystemImporter->load($url);
    }

    public function couldCanonicalize(UriInterface $url, UriInterface $canonicalUrl): bool
    {
        return $this->filesystemImporter->couldCanonicalize($url, $canonicalUrl);
    }

    public function __toString(): string
    {
        return 'LegacyCallbackImporter';
    }
}
