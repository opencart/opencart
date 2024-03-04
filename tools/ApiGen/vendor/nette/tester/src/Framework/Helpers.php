<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester;


/**
 * Test helpers.
 */
class Helpers
{
	/**
	 * Purges directory.
	 */
	public static function purge(string $dir): void
	{
		if (preg_match('#^(\w:)?[/\\\\]?$#', $dir)) {
			throw new \InvalidArgumentException('Directory must not be an empty string or root path.');
		}

		if (!is_dir($dir)) {
			mkdir($dir);
		}

		foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $entry) {
			if ($entry->isDir()) {
				rmdir((string) $entry);
			} else {
				unlink((string) $entry);
			}
		}
	}


	/**
	 * Find common directory for given paths. All files or directories must exist.
	 * @return string  Empty when not found. Slash and back slash chars normalized to DIRECTORY_SEPARATOR.
	 * @internal
	 */
	public static function findCommonDirectory(array $paths): string
	{
		$splitPaths = array_map(function ($s) {
			$real = realpath($s);
			if ($s === '') {
				throw new \RuntimeException('Path must not be empty.');
			} elseif ($real === false) {
				throw new \RuntimeException("File or directory '$s' does not exist.");
			}

			return explode(DIRECTORY_SEPARATOR, $real);
		}, $paths);

		$first = (array) array_shift($splitPaths);
		for ($i = 0; $i < count($first); $i++) {
			foreach ($splitPaths as $s) {
				if ($first[$i] !== ($s[$i] ?? null)) {
					break 2;
				}
			}
		}

		$common = implode(DIRECTORY_SEPARATOR, array_slice($first, 0, $i));
		return is_dir($common) ? $common : dirname($common);
	}


	/**
	 * Parse phpDoc comment.
	 * @internal
	 */
	public static function parseDocComment(string $s): array
	{
		$options = [];
		if (!preg_match('#^/\*\*(.*?)\*/#ms', $s, $content)) {
			return [];
		}

		if (preg_match('#^[ \t\*]*+([^\s@].*)#mi', $content[1], $matches)) {
			$options[0] = trim($matches[1]);
		}

		preg_match_all('#^[ \t\*]*@(\w+)([^\w\r\n].*)?#mi', $content[1], $matches, PREG_SET_ORDER);
		foreach ($matches as $match) {
			$ref = &$options[strtolower($match[1])];
			if (isset($ref)) {
				$ref = (array) $ref;
				$ref = &$ref[];
			}

			$ref = isset($match[2]) ? trim($match[2]) : '';
		}

		return $options;
	}


	/**
	 * @internal
	 */
	public static function errorTypeToString(int $type): string
	{
		$consts = get_defined_constants(true);
		foreach ($consts['Core'] as $name => $val) {
			if ($type === $val && substr($name, 0, 2) === 'E_') {
				return $name;
			}
		}

		return 'Unknown error';
	}


	/**
	 * Escape a string to be used as a shell argument.
	 * @internal
	 */
	public static function escapeArg(string $s): string
	{
		if (preg_match('#^[a-z0-9._=/:-]+$#Di', $s)) {
			return $s;
		}

		return defined('PHP_WINDOWS_VERSION_BUILD')
			? '"' . str_replace('"', '""', $s) . '"'
			: escapeshellarg($s);
	}


	/**
	 * @internal
	 */
	public static function prepareTempDir(string $path): string
	{
		$real = realpath($path);
		if ($real === false || !is_dir($real) || !is_writable($real)) {
			throw new \RuntimeException("Path '$real' is not a writable directory.");
		}

		$path = $real . DIRECTORY_SEPARATOR . 'Tester';
		if (!is_dir($path) && @mkdir($path) === false && !is_dir($path)) {  // @ - directory may exist
			throw new \RuntimeException("Cannot create '$path' directory.");
		}

		return $path;
	}
}
