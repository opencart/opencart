<?php declare(strict_types = 1);

namespace ApiGen;

use ReflectionClass;

use function array_map;
use function array_shift;
use function assert;
use function count;
use function realpath;
use function str_starts_with;
use function strlen;
use function substr;

use const DIRECTORY_SEPARATOR;


class Helpers
{
	/**
	 * @param string[] $paths indexed by [], non-empty
	 */
	public static function baseDir(array $paths): string
	{
		assert(count($paths) > 0);

		$paths = array_map(self::realPath(...), $paths);
		$first = array_shift($paths);
		$j = 0;

		for ($i = 0; $i < strlen($first); $i++) {
			foreach ($paths as $path) {
				if (!isset($path[$i]) || $path[$i] !== $first[$i]) {
					return substr($first, 0, $j);

				} elseif ($first[$i] === DIRECTORY_SEPARATOR) {
					$j = $i;
				}
			}
		}

		return $first;
	}


	public static function realPath(string $path): string
	{
		if (str_starts_with($path, 'phar://')) {
			return $path;
		}

		$realPath = realpath($path);

		if ($realPath === false) {
			throw new \RuntimeException("File $path does not exist.");
		}

		return $realPath;
	}


	/**
	 * @param class-string $name
	 */
	public static function classLikePath(string $name): string
	{
		$reflection = new ReflectionClass($name);
		$path = $reflection->getFileName();

		if ($path === false) {
			throw new \RuntimeException("Class-like $name has no path.");
		}

		return $path;
	}
}
