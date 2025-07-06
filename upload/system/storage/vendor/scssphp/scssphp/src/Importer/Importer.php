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
 * A base class for importers that resolves URLs in `@import`s to the contents
 * of Sass files.
 *
 * Importers should implement {@see __toString} to provide a human-readable description
 * of the importer. For example, the default filesystem importer returns its
 * load path.
 */
abstract class Importer implements \Stringable
{
    /**
     * If $url is recognized by this importer, returns its canonical format.
     *
     * Note that canonical URLs *must* be absolute, including a scheme. Returning
     * `file:` URLs is encouraged if the imported stylesheet comes from a file on
     * disk.
     *
     * If Sass has already loaded a stylesheet with the returned canonical URL,
     * it re-uses the existing parse tree. This means that importers **must
     * ensure** that the same canonical URL always refers to the same stylesheet,
     * *even across different importers*.
     *
     * This may return `null` if $url isn't recognized by this importer.
     *
     * If this importer's URL format supports file extensions, it should
     * canonicalize them the same way as the default filesystem importer:
     *
     * * The importer should look for stylesheets by adding the prefix `_` to the
     *   URL's basename, and by adding the extensions `.sass` and `.scss` if the
     *   URL doesn't already have one of those extensions. For example, if the
     *   URL was `foo/bar/baz`, the importer would look for:
     *   * `foo/bar/baz.sass`
     *   * `foo/bar/baz.scss`
     *   * `foo/bar/_baz.sass`
     *   * `foo/bar/_baz.scss`
     *
     *   If the URL was `foo/bar/baz.scss`, the importer would just look for:
     *   * `foo/bar/baz.scss`
     *   * `foo/bar/_baz.scss`
     *
     *   If the importer finds a stylesheet at more than one of these URLs, it
     *   should throw an exception indicating that the import is ambiguous. Note
     *   that if the extension is explicitly specified, a stylesheet with the
     *   opposite extension may exist.
     *
     * * If none of the possible paths is valid, the importer should perform the
     *   same resolution on the URL followed by `/index`. In the example above,
     *   it would look for:
     *   * `foo/bar/baz/_index.sass`
     *   * `foo/bar/baz/index.sass`
     *   * `foo/bar/baz/_index.scss`
     *   * `foo/bar/baz/index.scss`
     *
     *   As above, if the importer finds a stylesheet at more than one of these
     *   URLs, it should throw an exception indicating that the import is
     *   ambiguous.
     *
     * If no stylesheets are found, the importer should return `null`.
     *
     * Calling {@see canonicalize} multiple times with the same URL must return the
     * same result. Calling {@see canonicalize} with a URL returned by {@see canonicalize}
     * must return that URL. Calling {@see canonicalize} with a URL relative to one
     * returned by {@see canonicalize} must return a meaningful result.
     */
    abstract public function canonicalize(UriInterface $url): ?UriInterface;

    /**
     * Loads the Sass text for the given $url, or returns `null` if
     * this importer can't find the stylesheet it refers to.
     *
     * The $url comes from a call to {@see canonicalize} for this importer.
     *
     * When Sass encounters an `@import` rule in a stylesheet, it first calls
     * {@see canonicalize} and {@see load} on the importer that first loaded that
     * stylesheet with the imported URL resolved relative to the stylesheet's
     * original URL. If either of those returns `null`, it then calls
     * {@see canonicalize} and {@see load} on each importer in order with the URL as it
     * appears in the `@import` rule.
     *
     * If the importer finds a stylesheet at $url but it fails to load for some
     * reason, or if $url is uniquely associated with this importer but doesn't
     * refer to a real stylesheet, the importer may throw an exception that will
     * be wrapped by Sass.
     */
    abstract public function load(UriInterface $url): ?ImporterResult;

    /**
     * Without accessing the filesystem, returns whether passing $url to
     * {@see canonicalize} could possibly return $canonicalUrl.
     *
     * This is expected to be very efficient, and subclasses are allowed to
     * return false positives if it would be inefficient to determine whether
     * $url would actually resolve to $canonicalUrl. Subclasses are not allowed
     * to return false negatives.
     */
    public function couldCanonicalize(UriInterface $url, UriInterface $canonicalUrl): bool
    {
        return true;
    }

    /**
     * Returns whether the given URL scheme (without `:`) should be considered
     * "non-canonical" for this importer.
     *
     * An importer may not return a URL with a non-canonical scheme from
     * {@see canonicalize}. In exchange, {@see getContainingUrl} is available within
     * {@see canonicalize} for absolute URLs with non-canonical schemes so that the
     * importer can resolve those URLs differently based on where they're loaded.
     *
     * This must always return the same value for the same $scheme. It is
     * expected to be very efficient.
     */
    public function isNonCanonicalScheme(string $scheme): bool
    {
        return false;
    }

    /**
     * Whether the current {@see canonicalize} invocation comes from an `@import`
     * rule.
     *
     * When evaluating `@import` rules, URLs should canonicalize to an
     * [import-only file] if one exists for the URL being canonicalized.
     * Otherwise, canonicalization should be identical for `@import` and `@use`
     * rules.
     *
     * [import-only file]: https://sass-lang.com/documentation/at-rules/import#import-only-files
     *
     * Subclasses should only access this from within calls to {@see canonicalize}.
     * Outside of that context, its value is undefined and subject to change.
     */
    final protected function isFromImport(): bool
    {
        return ImportContext::isFromImport();
    }

    /**
     * The canonical URL of the stylesheet that caused the current {@see canonicalize}
     * invocation.
     *
     * This is only set when the containing stylesheet has a canonical URL, and
     * when the URL being canonicalized is either relative or has a scheme for
     * which {@see isNonCanonicalScheme} returns `true`. This restriction ensures that
     * canonical URLs are always interpreted the same way regardless of their
     * context.
     *
     * Subclasses should only access this from within calls to {@see canonicalize}.
     * Outside of that context, its value is undefined and subject to change.
     */
    final protected function getContainingUrl(): ?UriInterface
    {
        return ImportContext::getCanonicalizeContext()->getContainingUrl();
    }
}
