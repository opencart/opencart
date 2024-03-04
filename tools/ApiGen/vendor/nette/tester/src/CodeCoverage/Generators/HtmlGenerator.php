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
class HtmlGenerator extends AbstractGenerator
{
	private const Classes = [
		self::LineTested => 't', // tested
		self::LineUntested => 'u', // untested
		self::LineDead => 'dead', // dead code
	];
	private ?string $title;
	private array $files = [];


	/**
	 * @param  string  $file  path to coverage.dat file
	 * @param  array   $sources  files/directories
	 */
	public function __construct(string $file, array $sources = [], ?string $title = null)
	{
		parent::__construct($file, $sources);
		$this->title = $title;
	}


	protected function renderSelf(): void
	{
		$this->setupHighlight();
		$this->parse();

		$title = $this->title;
		$classes = self::Classes;
		$files = $this->files;
		$coveredPercent = $this->getCoveredPercent();

		include __DIR__ . '/template.phtml';
	}


	private function setupHighlight(): void
	{
		ini_set('highlight.comment', 'hc');
		ini_set('highlight.default', 'hd');
		ini_set('highlight.html', 'hh');
		ini_set('highlight.keyword', 'hk');
		ini_set('highlight.string', 'hs');
	}


	private function parse(): void
	{
		if (count($this->files) > 0) {
			return;
		}

		$this->files = [];
		$commonSourcesPath = Helpers::findCommonDirectory($this->sources) . DIRECTORY_SEPARATOR;
		foreach ($this->getSourceIterator() as $entry) {
			$entry = (string) $entry;

			$coverage = $covered = $total = 0;
			$loaded = !empty($this->data[$entry]);
			$lines = [];
			if ($loaded) {
				$lines = $this->data[$entry];
				foreach ($lines as $flag) {
					if ($flag >= self::LineUntested) {
						$total++;
					}

					if ($flag >= self::LineTested) {
						$covered++;
					}
				}

				$coverage = round($covered * 100 / $total);
				$this->totalSum += $total;
				$this->coveredSum += $covered;
			} else {
				$this->totalSum += count(file($entry, FILE_SKIP_EMPTY_LINES));
			}

			$light = $total ? $total < 5 : count(file($entry)) < 50;
			$this->files[] = (object) [
				'name' => str_replace($commonSourcesPath, '', $entry),
				'file' => $entry,
				'lines' => $lines,
				'coverage' => $coverage,
				'total' => $total,
				'class' => $light ? 'light' : ($loaded ? null : 'not-loaded'),
			];
		}
	}
}
