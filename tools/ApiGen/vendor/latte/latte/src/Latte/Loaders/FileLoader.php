<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Loaders;

use Latte;


/**
 * Template loader.
 */
class FileLoader implements Latte\Loader
{
	use Latte\Strict;

	protected ?string $baseDir = null;


	public function __construct(?string $baseDir = null)
	{
		$this->baseDir = $baseDir ? $this->normalizePath("$baseDir/") : null;
	}


	/**
	 * Returns template source code.
	 */
	public function getContent(string $fileName): string
	{
		$file = $this->baseDir . $fileName;
		if ($this->baseDir && !str_starts_with($this->normalizePath($file), $this->baseDir)) {
			throw new Latte\RuntimeException("Template '$file' is not within the allowed path '{$this->baseDir}'.");

		} elseif (!is_file($file)) {
			throw new Latte\RuntimeException("Missing template file '$file'.");

		} elseif ($this->isExpired($fileName, time())) {
			if (@touch($file) === false) {
				trigger_error("File's modification time is in the future. Cannot update it: " . error_get_last()['message'], E_USER_WARNING);
			}
		}

		return file_get_contents($file);
	}


	public function isExpired(string $file, int $time): bool
	{
		$mtime = @filemtime($this->baseDir . $file); // @ - stat may fail
		return !$mtime || $mtime > $time;
	}


	/**
	 * Returns referred template name.
	 */
	public function getReferredName(string $file, string $referringFile): string
	{
		if ($this->baseDir || !preg_match('#/|\\\\|[a-z][a-z0-9+.-]*:#iA', $file)) {
			$file = $this->normalizePath($referringFile . '/../' . $file);
		}

		return $file;
	}


	/**
	 * Returns unique identifier for caching.
	 */
	public function getUniqueId(string $file): string
	{
		return $this->baseDir . strtr($file, '/', DIRECTORY_SEPARATOR);
	}


	protected static function normalizePath(string $path): string
	{
		$res = [];
		foreach (explode('/', strtr($path, '\\', '/')) as $part) {
			if ($part === '..' && $res && end($res) !== '..') {
				array_pop($res);
			} elseif ($part !== '.') {
				$res[] = $part;
			}
		}

		return implode(DIRECTORY_SEPARATOR, $res);
	}
}
