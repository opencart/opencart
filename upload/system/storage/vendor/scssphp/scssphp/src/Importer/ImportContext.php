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

/**
 * @internal
 */
final class ImportContext
{
    private static ?CanonicalizeContext $context = null;

    /**
     * Whether the Sass compiler is currently evaluating an `@import` rule.
     *
     * When evaluating `@import` rules, URLs should canonicalize to an import-only
     * file if one exists for the URL being canonicalized. Otherwise,
     * canonicalization should be identical for `@import` and `@use` rules. It's
     * admittedly hacky to set this globally, but `@import` will eventually be
     * removed, at which point we can delete this and have one consistent behavior.
     */
    public static function isFromImport(): bool
    {
        return self::$context?->isFromImport() ?? false;
    }

    /**
     * @template T
     *
     * @param callable(): T $callback
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     */
    public static function inImportRule(callable $callback)
    {
        if (self::$context !== null) {
            return self::$context->withFromImport(true, $callback);
        }

        return self::withCanonicalizeContext(new CanonicalizeContext(null, true), $callback);
    }

    public static function getCanonicalizeContext(): CanonicalizeContext
    {
        if (self::$context === null) {
            throw new \LogicException('canonicalizeContext may only be accessed within a call to canonicalize().');
        }

        return self::$context;
    }

    /**
     * Runs $callback in the given context.
     *
     * @template T
     *
     * @param callable(): T $callback
     * @return T
     *
     * @param-immediately-invoked-callable $callback
     */
    public static function withCanonicalizeContext(?CanonicalizeContext $canonicalizeContext, callable $callback)
    {
        $oldCanonicalizeContext = self::$context;

        self::$context = $canonicalizeContext;

        try {
            return $callback();
        } finally {
            self::$context = $oldCanonicalizeContext;
        }
    }
}
