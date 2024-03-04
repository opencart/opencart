<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester\CodeCoverage\Generators;

use Tester\Helpers;


/**
 * Code coverage report generator.
 */
abstract class AbstractGenerator
{
	protected const
		LineDead = -2,
		LineTested = 1,
		LineUntested = -1;

	public array $acceptFiles = ['php', 'phpt', 'phtml'];
	protected array $data;
	protected array $sources;
	protected int $totalSum = 0;
	protected int $coveredSum = 0;


	/**
	 * @param  string  $file  path to coverage.dat file
	 * @param  array   $sources  paths to covered source files or directories
	 */
	public function __construct(string $file, array $sources = [])
	{
		if (!is_file($file)) {
			throw new \Exception("File '$file' is missing.");
		}

		$this->data = @unserialize(file_get_contents($file)); // @ is escalated to exception
		if (!is_array($this->data)) {
			throw new \Exception("Content of file '$file' is invalid.");
		}

		$this->data = array_filter($this->data, function (string $path): bool {
			return @is_file($path); // @ some files or wrappers may not exist, i.e. mock://
		}, ARRAY_FILTER_USE_KEY);

		if (!$sources) {
			$sources = [Helpers::findCommonDirectory(array_keys($this->data))];

		} else {
			foreach ($sources as $source) {
				if (!file_exists($source)) {
					throw new \Exception("File or directory '$source' is missing.");
				}
			}
		}

		$this->sources = array_map('realpath', $sources);
	}


	public function render(?string $file = null): void
	{
		$handle = $file ? @fopen($file, 'w') : STDOUT; // @ is escalated to exception
		if (!$handle) {
			throw new \Exception("Unable to write to file '$file'.");
		}

		ob_start(function (string $buffer) use ($handle) { fwrite($handle, $buffer); }, 4096);
		try {
			$this->renderSelf();
		} catch (\Throwable $e) {
		}

		ob_end_flush();
		fclose($handle);

		if (isset($e)) {
			if ($file) {
				unlink($file);
			}

			throw $e;
		}
	}


	public function getCoveredPercent(): float
	{
		return $this->totalSum ? $this->coveredSum * 100 / $this->totalSum : 0;
	}


	protected function getSourceIterator(): \Iterator
	{
		$iterator = new \AppendIterator;
		foreach ($this->sources as $source) {
			$iterator->append(
				is_dir($source)
					? new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source))
					: new \ArrayIterator([new \SplFileInfo($source)])
			);
		}

		return new \CallbackFilterIterator($iterator, function (\SplFileInfo $file): bool {
			return $file->getBasename()[0] !== '.'  // . or .. or .gitignore
				&& in_array($file->getExtension(), $this->acceptFiles, true);
		});
	}


	/** @deprecated  */
	protected static function getCommonFilesPath(array $files): string
	{
		return Helpers::findCommonDirectory($files);
	}


	abstract protected function renderSelf(): void;
}
