<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester\CodeCoverage\Generators;

use DOMDocument;
use DOMElement;
use Tester\CodeCoverage\PhpParser;


class CloverXMLGenerator extends AbstractGenerator
{
	private static array $metricAttributesMap = [
		'packageCount' => 'packages',
		'fileCount' => 'files',
		'linesOfCode' => 'loc',
		'linesOfNonCommentedCode' => 'ncloc',
		'classCount' => 'classes',
		'methodCount' => 'methods',
		'coveredMethodCount' => 'coveredmethods',
		'statementCount' => 'statements',
		'coveredStatementCount' => 'coveredstatements',
		'elementCount' => 'elements',
		'coveredElementCount' => 'coveredelements',
		'conditionalCount' => 'conditionals',
		'coveredConditionalCount' => 'coveredconditionals',
	];


	public function __construct(string $file, array $sources = [])
	{
		if (!extension_loaded('dom') || !extension_loaded('tokenizer')) {
			throw new \LogicException('CloverXML generator requires DOM and Tokenizer extensions to be loaded.');
		}

		parent::__construct($file, $sources);
	}


	protected function renderSelf(): void
	{
		$time = (string) time();
		$parser = new PhpParser;

		$doc = new DOMDocument;
		$doc->formatOutput = true;

		$elCoverage = $doc->appendChild($doc->createElement('coverage'));
		$elCoverage->setAttribute('generated', $time);

		// TODO: @name
		$elProject = $elCoverage->appendChild($doc->createElement('project'));
		$elProject->setAttribute('timestamp', $time);
		$elProjectMetrics = $elProject->appendChild($doc->createElement('metrics'));

		$projectMetrics = (object) [
			'packageCount' => 0,
			'fileCount' => 0,
			'linesOfCode' => 0,
			'linesOfNonCommentedCode' => 0,
			'classCount' => 0,
			'methodCount' => 0,
			'coveredMethodCount' => 0,
			'statementCount' => 0,
			'coveredStatementCount' => 0,
			'elementCount' => 0,
			'coveredElementCount' => 0,
			'conditionalCount' => 0,
			'coveredConditionalCount' => 0,
		];

		foreach ($this->getSourceIterator() as $file) {
			$file = (string) $file;

			$projectMetrics->fileCount++;

			if (empty($this->data[$file])) {
				$coverageData = null;
				$this->totalSum += count(file($file, FILE_SKIP_EMPTY_LINES));
			} else {
				$coverageData = $this->data[$file];
			}

			// TODO: split to <package> by namespace?
			$elFile = $elProject->appendChild($doc->createElement('file'));
			$elFile->setAttribute('name', $file);
			$elFileMetrics = $elFile->appendChild($doc->createElement('metrics'));

			try {
				$code = $parser->parse(file_get_contents($file));
			} catch (\ParseError $e) {
				throw new \ParseError($e->getMessage() . ' in file ' . $file);
			}

			$fileMetrics = (object) [
				'linesOfCode' => $code->linesOfCode,
				'linesOfNonCommentedCode' => $code->linesOfCode - $code->linesOfComments,
				'classCount' => count($code->classes) + count($code->traits),
				'methodCount' => 0,
				'coveredMethodCount' => 0,
				'statementCount' => 0,
				'coveredStatementCount' => 0,
				'elementCount' => 0,
				'coveredElementCount' => 0,
				'conditionalCount' => 0,
				'coveredConditionalCount' => 0,
			];

			foreach (array_merge($code->classes, $code->traits) as $name => $info) { // TODO: interfaces?
				$elClass = $elFile->appendChild($doc->createElement('class'));
				if (($tmp = strrpos($name, '\\')) === false) {
					$elClass->setAttribute('name', $name);
				} else {
					$elClass->setAttribute('namespace', substr($name, 0, $tmp));
					$elClass->setAttribute('name', substr($name, $tmp + 1));
				}

				$elClassMetrics = $elClass->appendChild($doc->createElement('metrics'));
				$classMetrics = $this->calculateClassMetrics($info, $coverageData);
				self::setMetricAttributes($elClassMetrics, $classMetrics);
				self::appendMetrics($fileMetrics, $classMetrics);
			}

			self::setMetricAttributes($elFileMetrics, $fileMetrics);


			foreach ((array) $coverageData as $line => $count) {
				if ($count === self::LineDead) {
					continue;
				}

				// Line type can be 'method' but Xdebug does not report such lines as executed.
				$elLine = $elFile->appendChild($doc->createElement('line'));
				$elLine->setAttribute('num', (string) $line);
				$elLine->setAttribute('type', 'stmt');
				$elLine->setAttribute('count', (string) max(0, $count));

				$this->totalSum++;
				$this->coveredSum += $count > 0 ? 1 : 0;
			}

			self::appendMetrics($projectMetrics, $fileMetrics);
		}

		// TODO: What about reported (covered) lines outside of class/trait definition?
		self::setMetricAttributes($elProjectMetrics, $projectMetrics);

		echo $doc->saveXML();
	}


	private function calculateClassMetrics(\stdClass $info, ?array $coverageData = null): \stdClass
	{
		$stats = (object) [
			'methodCount' => count($info->methods),
			'coveredMethodCount' => 0,
			'statementCount' => 0,
			'coveredStatementCount' => 0,
			'conditionalCount' => 0,
			'coveredConditionalCount' => 0,
			'elementCount' => null,
			'coveredElementCount' => null,
		];

		foreach ($info->methods as $name => $methodInfo) {
			[$lineCount, $coveredLineCount] = $this->analyzeMethod($methodInfo, $coverageData);

			$stats->statementCount += $lineCount;

			if ($coverageData !== null) {
				$stats->coveredMethodCount += $lineCount === $coveredLineCount ? 1 : 0;
				$stats->coveredStatementCount += $coveredLineCount;
			}
		}

		$stats->elementCount = $stats->methodCount + $stats->statementCount;
		$stats->coveredElementCount = $stats->coveredMethodCount + $stats->coveredStatementCount;

		return $stats;
	}


	private static function analyzeMethod(\stdClass $info, ?array $coverageData = null): array
	{
		$count = 0;
		$coveredCount = 0;

		if ($coverageData === null) { // Never loaded file
			$count = max(1, $info->end - $info->start - 2);
		} else {
			for ($i = $info->start; $i <= $info->end; $i++) {
				if (isset($coverageData[$i]) && $coverageData[$i] !== self::LineDead) {
					$count++;
					if ($coverageData[$i] > 0) {
						$coveredCount++;
					}
				}
			}
		}

		return [$count, $coveredCount];
	}


	private static function appendMetrics(\stdClass $summary, \stdClass $add): void
	{
		foreach ($add as $name => $value) {
			$summary->{$name} += $value;
		}
	}


	private static function setMetricAttributes(DOMElement $element, \stdClass $metrics): void
	{
		foreach ($metrics as $name => $value) {
			$element->setAttribute(self::$metricAttributesMap[$name], (string) $value);
		}
	}
}
