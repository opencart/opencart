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

use ScssPhp\ScssPhp\Util\Path;

/**
 * @internal
 */
final class ImportUtil
{
    /**
     * Resolves an imported path using the same logic as the filesystem importer.
     *
     * This tries to fill in extensions and partial prefixes and check for a
     * directory default. If no file can be found, it returns `null`.
     */
    public static function resolveImportPath(string $path): ?string
    {
        $extension = Path::extension($path);

        if ($extension === '.sass' || $extension === '.scss' || $extension === '.css') {
            return self::ifInImport(fn () => self::exactlyOne(self::tryPath(Path::withoutExtension($path) . '.import' . $extension)))
                ?? self::exactlyOne(self::tryPath($path));
        }

        return self::ifInImport(fn () => self::exactlyOne(self::tryPathWithExtensions($path . '.import')))
            ?? self::exactlyOne(self::tryPathWithExtensions($path))
            ?? self::tryPathAsDirectory($path);
    }

    /**
     * Like {@see tryPath}, but checks `.sass`, `.scss`, and `.css` extensions.
     *
     * @return list<string>
     */
    private static function tryPathWithExtensions(string $path): array
    {
        $result = array_merge(
            self::tryPath($path . '.sass'),
            self::tryPath($path . '.scss'),
        );

        if ($result !== []) {
            return $result;
        }

        return self::tryPath($path . '.css');
    }

    /**
     * Returns the $path and/or the partial with the same name, if either or both
     * exists.
     *
     * If neither exists, returns an empty list.
     *
     * @return list<string>
     */
    private static function tryPath(string $path): array
    {
        $partial = Path::join(dirname($path), '_' . basename($path));
        $candidates = [];

        if (is_file($partial)) {
            $candidates[] = $partial;
        }

        if (is_file($path)) {
            $candidates[] = $path;
        }

        return $candidates;
    }

    /**
     * Returns the resolved index file for $path if $path is a directory and the
     * index file exists.
     *
     * Otherwise, returns `null`.
     */
    private static function tryPathAsDirectory(string $path): ?string
    {
        if (!is_dir($path)) {
            return null;
        }

        return self::ifInImport(fn () => self::exactlyOne(self::tryPathWithExtensions(Path::join($path, 'index.import'))))
            ?? self::exactlyOne(self::tryPathWithExtensions(Path::join($path, 'index')));
    }

    /**
     * @param list<string> $paths
     */
    private static function exactlyOne(array $paths): ?string
    {
        if (\count($paths) === 0) {
            return null;
        }

        if (\count($paths) === 1) {
            return $paths[0];
        }

        $formattedPrettyPaths = [];

        foreach ($paths as $path) {
            $formattedPrettyPaths[] = '  ' . Path::prettyUri($path);
        }

        throw new \Exception("It's not clear which file to import. Found:\n" . implode("\n", $formattedPrettyPaths));
    }

    /**
     * If {@see ImportContext::isFromImport} is `true`, invokes callback and returns the result.
     *
     * Otherwise, returns `null`.
     *
     * @template T
     *
     * @param callable(): T $callback
     * @return T|null
     */
    private static function ifInImport(callable $callback)
    {
        if (ImportContext::isFromImport()) {
            return $callback();
        }

        return null;
    }
}
