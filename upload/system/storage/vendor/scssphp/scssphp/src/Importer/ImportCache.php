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
use ScssPhp\ScssPhp\Ast\Sass\Statement\Stylesheet;
use ScssPhp\ScssPhp\Logger\LoggerInterface;
use ScssPhp\ScssPhp\Logger\QuietLogger;
use ScssPhp\ScssPhp\Util\UriUtil;

/**
 * An in-memory cache of parsed stylesheets that have been imported by Sass.
 *
 * @internal
 */
final class ImportCache
{
    /**
     * @var list<Importer>
     */
    private readonly array $importers;

    private readonly LoggerInterface $logger;

    /**
     * The canonicalized URLs for each non-canonical URL.
     *
     * The `forImport` in each key is true when this canonicalization is for an
     * `@import` rule. Otherwise, it's for a `@use` or `@forward` rule.
     *
     * This cache covers loads that go through the entire chain of {@see $importers},
     * but it doesn't cover individual loads or loads in which any importer
     * accesses `containingUrl`. See also {@see $perImporterCanonicalizeCache}.
     *
     * @var array<string, array<0|1, CanonicalizeResult|SpecialCacheValue>>
     */
    private array $canonicalizeCache = [];

    /**
     * Like {@see $canonicalizeCache} but also includes the specific importer in the
     * key.
     *
     * This is used to cache both relative imports from the base importer and
     * individual importer results in the case where some other component of the
     * importer chain isn't cacheable.
     *
     * @var \SplObjectStorage<Importer, array<string, array<0|1, CanonicalizeResult|SpecialCacheValue>>>
     */
    private \SplObjectStorage $perImporterCanonicalizeCache;

    /**
     * The parsed stylesheets for each canonicalized import URL.
     *
     * @var array<string, Stylesheet|SpecialCacheValue>
     */
    private array $importCache = [];

    /**
     * The import results for each canonicalized import URL.
     *
     * @var array<string, ImporterResult>
     */
    private array $resultsCache = [];

    /**
     * @param list<Importer> $importers
     */
    public function __construct(array $importers, LoggerInterface $logger)
    {
        $this->importers = $importers;
        $this->logger = $logger;
        $this->perImporterCanonicalizeCache = new \SplObjectStorage();
    }

    public function canonicalize(UriInterface $url, ?Importer $baseImporter = null, ?UriInterface $baseUrl = null, bool $forImport = false): ?CanonicalizeResult
    {
        $urlCacheKey = (string) $url;
        $forImportCacheKey = (int) $forImport;

        if ($baseImporter !== null && $url->getScheme() === null) {
            $resolvedUrl = self::resolveUri($baseUrl, $url);
            $resolvedUrlCacheKey = (string) $resolvedUrl;

            if (!isset($this->perImporterCanonicalizeCache[$baseImporter][$resolvedUrlCacheKey][$forImportCacheKey])) {
                [$result, $cacheable] = $this->doCanonicalize($baseImporter, $resolvedUrl, $baseUrl, $forImport);
                \assert($cacheable, 'Relative loads should always be cacheable because they never provide access to the containing URL.');

                $importerCache = $this->perImporterCanonicalizeCache[$baseImporter] ?? [];
                $importerCache[$resolvedUrlCacheKey][$forImportCacheKey] = $result ?? SpecialCacheValue::null;
                $this->perImporterCanonicalizeCache[$baseImporter] = $importerCache;
            }

            $relativeResult = $this->perImporterCanonicalizeCache[$baseImporter][$resolvedUrlCacheKey][$forImportCacheKey];

            if ($relativeResult !== SpecialCacheValue::null) {
                return $relativeResult;
            }
        }

        if (isset($this->canonicalizeCache[$urlCacheKey][$forImportCacheKey])) {
            $cacheResult = $this->canonicalizeCache[$urlCacheKey][$forImportCacheKey];

            if ($cacheResult !== SpecialCacheValue::null) {
                return $cacheResult;
            }

            return null;
        }

        // Each individual call to a `canonicalize()` override may not be cacheable
        // (specifically, if it has access to `containingUrl` it's too
        // context-sensitive to usefully cache). We want to cache a given URL across
        // the _entire_ importer chain, so we use $cacheable to track whether _all_
        // `canonicalize()` calls we've attempted are cacheable. Only if they are, do
        // we store the result in the cache.
        $cacheable = true;
        foreach ($this->importers as $i => $importer) {
            if (isset($this->perImporterCanonicalizeCache[$importer][$urlCacheKey][$forImportCacheKey])) {
                $result = $this->perImporterCanonicalizeCache[$importer][$urlCacheKey][$forImportCacheKey];

                if ($result !== SpecialCacheValue::null) {
                    return $result;
                }

                continue;
            }

            [$result, $importerCacheable] = $this->doCanonicalize($importer, $url, $baseUrl, $forImport);

            if ($result !== null && $importerCacheable && $cacheable) {
                $this->canonicalizeCache[$urlCacheKey][$forImportCacheKey] = $result;

                return $result;
            }
            if ($importerCacheable && !$cacheable) {
                $importerCache = $this->perImporterCanonicalizeCache[$importer] ?? [];
                $importerCache[$urlCacheKey][$forImportCacheKey] = $result ?? SpecialCacheValue::null;
                $this->perImporterCanonicalizeCache[$importer] = $importerCache;

                if ($result !== null) {
                    return $result;
                }
            }
            if (!$importerCacheable) {
                if ($cacheable) {
                    // If this is the first uncacheable result, add all previous results
                    // to the per-importer cache so we don't have to re-run them for
                    // future uses of this importer.
                    for ($j = 0; $j < $i; ++$j) {
                        $importerCache = $this->perImporterCanonicalizeCache[$this->importers[$j]] ?? [];
                        $importerCache[$urlCacheKey][$forImportCacheKey] = SpecialCacheValue::null;
                        $this->perImporterCanonicalizeCache[$this->importers[$j]] = $importerCache;
                    }
                    $cacheable = false;
                }

                if ($result !== null) {
                    return $result;
                }
            }
        }

        if ($cacheable) {
            $this->canonicalizeCache[$urlCacheKey][$forImportCacheKey] = SpecialCacheValue::null;
        }

        return null;
    }

    private static function resolveUri(?UriInterface $baseUrl, UriInterface $url): UriInterface
    {
        if ($baseUrl === null) {
            return $url;
        }

        return UriUtil::resolveUri($baseUrl, $url);
    }

    /**
     * Calls {@see Importer::canonicalize} and prints a deprecation warning if it
     * returns a relative URL.
     *
     * This returns both the result of the call to `canonicalize()` and whether
     * that result is cacheable at all.
     *
     * @return array{CanonicalizeResult|null, bool}
     */
    private function doCanonicalize(Importer $importer, UriInterface $url, ?UriInterface $baseUrl, bool $forImport): array
    {
        $passContainingUrl = $baseUrl !== null && ($url->getScheme() === null || $importer->isNonCanonicalScheme($url->getScheme()));
        $canonicalizeContext = new CanonicalizeContext($passContainingUrl ? $baseUrl : null, $forImport);

        $result = ImportContext::withCanonicalizeContext($canonicalizeContext, fn () => $importer->canonicalize($url));

        $cacheable = !$passContainingUrl || !$canonicalizeContext->wasContainingUrlAccessed();

        if ($result === null) {
            return [null, $cacheable];
        }

        if ($result->getScheme() === null) {
            // dart-sass triggers a deprecation here. As we never supported the old behavior, we forbid it directly.
            throw new \UnexpectedValueException("Importer $importer canonicalized $url to $result but canonical URLs must be absolute.");
        }

        if ($importer->isNonCanonicalScheme($result->getScheme())) {
            throw new \UnexpectedValueException("Importer $importer canonicalized $url to $result, which uses a scheme declared as non-canonical.");
        }

        return [new CanonicalizeResult($importer, $result, $url), $cacheable];
    }

    /**
     * Tries to load the canonicalized $canonicalUrl using $importer.
     *
     * If $importer can import $canonicalUrl, returns the imported {@see Stylesheet}.
     * Otherwise returns `null`.
     *
     * If passed, the $originalUrl represents the URL that was canonicalized
     * into $canonicalUrl. It's used to resolve a relative canonical URL, which
     * importers may return for legacy reasons.
     *
     * If $quiet is `true`, this will disable logging warnings when parsing the
     * newly imported stylesheet.
     *
     * Caches the result of the import and uses cached results if possible.
     */
    public function importCanonical(Importer $importer, UriInterface $canonicalUrl, ?UriInterface $originalUrl = null, bool $quiet = false): ?Stylesheet
    {
        $result = $this->importCache[(string) $canonicalUrl] ??= $this->doImportCanonical($importer, $canonicalUrl, $originalUrl, $quiet) ?? SpecialCacheValue::null;

        if ($result !== SpecialCacheValue::null) {
            return $result;
        }

        return null;
    }

    private function doImportCanonical(Importer $importer, UriInterface $canonicalUrl, ?UriInterface $originalUrl = null, bool $quiet = false): ?Stylesheet
    {
        $result = $importer->load($canonicalUrl);

        if ($result === null) {
            return null;
        }

        $this->resultsCache[(string) $canonicalUrl] = $result;

        return Stylesheet::parse($result->getContents(), $result->getSyntax(), $quiet ? new QuietLogger() : $this->logger, self::resolveUri($originalUrl, $canonicalUrl));
    }

    public function humanize(UriInterface $canonicalUrl): UriInterface
    {
        $shortestUrl = null;
        $shortestLength = \PHP_INT_MAX;

        foreach ($this->canonicalizeCache as $cacheValues) {
            foreach ($cacheValues as $cacheValue) {
                if ($cacheValue === SpecialCacheValue::null) {
                    continue;
                }

                if ($cacheValue->canonicalUrl->toString() !== $canonicalUrl->toString()) {
                    continue;
                }

                $originalUrlLength = \strlen($cacheValue->originalUrl->getPath());

                if ($shortestUrl === null || $originalUrlLength < $shortestLength) {
                    $shortestUrl = $cacheValue->originalUrl;
                    $shortestLength = $originalUrlLength;
                }
            }
        }

        if ($shortestUrl !== null) {
            return UriUtil::resolve($shortestUrl, basename($canonicalUrl->getPath()));
        }

        return $canonicalUrl;
    }

    public function sourceMapUrl(UriInterface $canonicalUrl): UriInterface
    {
        return ($this->resultsCache[(string) $canonicalUrl] ?? null)?->getSourceMapUrl() ?? $canonicalUrl;
    }
}
