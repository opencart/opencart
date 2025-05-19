<?php namespace Todaymade\Daux;

use Symfony\Component\Console\Output\OutputInterface;

class Cache
{
    public static $printed = false;

    public static function getDirectory(): string
    {
        $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'dauxio' . DIRECTORY_SEPARATOR;

        if (!Cache::$printed) {
            Cache::$printed = true;
            Daux::writeln("Using cache dir '$dir'", OutputInterface::VERBOSITY_VERBOSE);
        }

        return $dir;
    }

    /**
     * Store an item in the cache for a given number of minutes.
     */
    public static function put(string $key, string $value): void
    {
        Cache::ensureCacheDirectoryExists($path = Cache::path($key));
        file_put_contents($path, $value);
    }

    /**
     * Create the file cache directory if necessary.
     */
    protected static function ensureCacheDirectoryExists(string $path): void
    {
        $parent = dirname($path);

        if (!file_exists($parent)) {
            mkdir($parent, 0777, true);
        }
    }

    /**
     * Remove an item from the cache.
     */
    public static function forget(string $key): bool
    {
        $path = Cache::path($key);

        if (file_exists($path)) {
            return unlink($path);
        }

        return false;
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @return mixed
     */
    public static function get(string $key): ?string
    {
        $path = Cache::path($key);

        if (file_exists($path)) {
            return file_get_contents($path);
        }

        return null;
    }

    /**
     * Get the full path for the given cache key.
     */
    protected static function path(string $key): string
    {
        $parts = array_slice(str_split($hash = sha1($key), 2), 0, 2);

        return Cache::getDirectory() . '/' . implode('/', $parts) . '/' . $hash;
    }

    public static function clear(): void
    {
        Cache::rrmdir(Cache::getDirectory());
    }

    protected static function rrmdir(string $dir): void
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (is_dir($dir . '/' . $object)) {
                        Cache::rrmdir($dir . '/' . $object);
                    } else {
                        unlink($dir . '/' . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }
}
