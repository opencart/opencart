<?php

namespace ScssPhp\ScssPhp\Util;

use League\Uri\BaseUri;
use League\Uri\Contracts\UriInterface;
use League\Uri\Uri;
use Symfony\Component\Filesystem\Exception\InvalidArgumentException;
use Symfony\Component\Filesystem\Path as SymfonyPath;

/**
 * @internal
 */
final class Path
{
    /**
     * @var array<string, string>
     */
    private static array $realCaseCache = [];
    public static function toUri(string $path): UriInterface
    {
        if (\DIRECTORY_SEPARATOR === '\\') {
            return Uri::fromWindowsPath($path);
        }

        return Uri::fromUnixPath($path);
    }

    public static function fromUri(UriInterface $uri): string
    {
        if (\DIRECTORY_SEPARATOR === '\\') {
            return BaseUri::from($uri)->windowsPath() ?? throw new \InvalidArgumentException("Uri $uri must have scheme 'file:'.");
        }

        return BaseUri::from($uri)->unixPath() ?? throw new \InvalidArgumentException("Uri $uri must have scheme 'file:'.");
    }

    public static function isAbsolute(string $path): bool
    {
        if ($path === '') {
            return false;
        }

        if ($path[0] === '/') {
            return true;
        }

        if (\DIRECTORY_SEPARATOR === '\\') {
            return self::isWindowsAbsolute($path);
        }

        return false;
    }

    /**
     * Canonicalizes $path.
     *
     * This is guaranteed to return the same path for two different input paths
     * if and only if both input paths point to the same location. Unlike
     * {@see normalize}, it returns absolute paths when possible and canonicalizes
     * ASCII case on Windows.
     *
     * Note that this does not resolve symlinks.
     */
    public static function canonicalize(string $path): string
    {
        return self::realCasePath(self::normalize(self::absolute($path)));
    }

    /**
     * Normalizes $path, simplifying it by handling `..`, and `.`, and
     * removing redundant path separators whenever possible.
     *
     * Note that this is *not* guaranteed to return the same result for two
     * equivalent input paths.
     */
    public static function normalize(string $path): string
    {
        $normalized = SymfonyPath::canonicalize($path);

        // The Symfony Path class always uses / as separator, while we want to use the platform one to get a real path
        if (\DIRECTORY_SEPARATOR === '\\') {
            $normalized = str_replace('/', '\\', $normalized);
        }

        return $normalized;
    }

    /**
     * Attempts to convert $path to an equivalent relative path from $from.
     *
     * Since there is no relative path from one drive letter to another on Windows,
     * this will return an absolute path in those cases.
     */
    public static function relative(string $path, string $from): string
    {
        try {
            $relativePath = SymfonyPath::makeRelative($path, $from);
        } catch (InvalidArgumentException) {
            return $path;
        }

        // The Symfony Path class always uses / as separator, while we want to use the platform one to get a real path
        if (\DIRECTORY_SEPARATOR === '\\') {
            $relativePath = str_replace('/', '\\', $relativePath);
        }

        return $relativePath;
    }

    private static function realCasePath(string $path): string
    {
        if (!(\PHP_OS_FAMILY === 'Windows' || \PHP_OS_FAMILY === 'Darwin')) {
            return $path;
        }

        if (\PHP_OS_FAMILY === 'Windows') {
            // Drive names are *always* case-insensitive, so convert them to uppercase.
            if (self::isAbsolute($path) && Character::isAlphabetic($path[0])) {
                $path = strtoupper(substr($path, 0, 3)) . substr($path, 3);
            }
        }

        return self::realCasePathHelper($path);
    }

    private static function realCasePathHelper(string $path): string
    {
        $dirname = dirname($path);

        if ($dirname === $path || $dirname === '.') {
            return $path;
        }

        return self::$realCaseCache[$path] ??= self::computeRealCasePath($path);
    }

    private static function computeRealCasePath(string $path): string
    {
        $realDirname = self::realCasePathHelper(dirname($path));
        $basename = basename($path);

        $files = @scandir($realDirname);

        if ($files === false) {
            // If there's an error listing a directory, it's likely because we're
            // trying to reach too far out of the current directory into something
            // we don't have permissions for. In that case, just assume we have the
            // real path.
            return $path;
        }

        $matches = array_values(array_filter($files, fn ($realPath) => StringUtil::equalsIgnoreCase(basename($realPath), $basename)));

        if (\count($matches) === 1) {
            return self::join($realDirname, $matches[0]);
        }

        // If the file doesn't exist, or if there are multiple options
        // (meaning the filesystem isn't actually case-insensitive), use
        // `basename` as-is.
        return self::join($realDirname, $basename);
    }

    public static function isWindowsAbsolute(string $path): bool
    {
        if ($path === '') {
            return false;
        }

        if ($path[0] === '/') {
            return true;
        }

        if ($path[0] === '\\') {
            return true;
        }

        if (\strlen($path) < 3) {
            return false;
        }

        if ($path[1] !== ':') {
            return false;
        }

        if ($path[2] !== '/' && $path[2] !== '\\') {
            return false;
        }

        if (!preg_match('/^[A-Za-z]$/', $path[0])) {
            return false;
        }

        return true;
    }

    public static function join(string $part1, string $part2): string
    {
        if ($part1 === '' || self::isAbsolute($part2)) {
            return $part2;
        }

        if ($part2 === '') {
            return $part1;
        }

        $last = $part1[\strlen($part1) - 1];
        $separator = \DIRECTORY_SEPARATOR;

        if ($last === '/' || $last === \DIRECTORY_SEPARATOR) {
            $separator = '';
        }

        return $part1 . $separator . $part2;
    }

    public static function absolute(string $path): string
    {
        $cwd = getcwd();

        if ($cwd === false) {
            return $path;
        }

        return self::join($cwd, $path);
    }

    /**
     * Gets the file extension of $path: the portion of basename from the last
     * `.` to the end (including the `.` itself).
     *
     * If the file name starts with a `.`, then that is not considered the
     * extension
     */
    public static function extension(string $path): string
    {
        $basename = basename($path);

        $lastDot = strrpos($basename, '.');

        if ($lastDot === false || $lastDot === 0) {
            return '';
        }

        return substr($basename, $lastDot);
    }

    public static function withoutExtension(string $path): string
    {
        $extension = self::extension($path);

        if ($extension === '') {
            return $path;
        }

        return substr($path, 0, -\strlen($extension));
    }

    /**
     * Returns a pretty URI for a path
     */
    public static function prettyUri(string|UriInterface $path): string
    {
        if ($path instanceof UriInterface) {
            if ($path->getScheme() !== 'file') {
                return (string) $path;
            }

            $path = self::fromUri($path);
        }

        $normalizedPath = $path;
        $normalizedRootDirectory = getcwd() . '/';

        if (\DIRECTORY_SEPARATOR === '\\') {
            $normalizedRootDirectory = str_replace('\\', '/', $normalizedRootDirectory);
            $normalizedPath = str_replace('\\', '/', $path);
        }

        // TODO add support for returning a relative path using ../ in some cases, like Dart's path.prettyUri method

        if (str_starts_with($normalizedPath, $normalizedRootDirectory)) {
            return substr($path, \strlen($normalizedRootDirectory));
        }

        return $path;
    }
}
