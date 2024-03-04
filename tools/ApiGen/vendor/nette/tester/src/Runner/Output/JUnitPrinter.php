<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester\Runner\Output;

use Tester;
use Tester\Runner\Test;


/**
 * JUnit xml format printer.
 */
class JUnitPrinter implements Tester\Runner\OutputHandler
{
	/** @var resource */
	private $file;
	private string $buffer;
	private float $startTime;
	private array $results;


	public function __construct(?string $file = null)
	{
		$this->file = fopen($file ?: 'php://output', 'w');
	}


	public function begin(): void
	{
		$this->buffer = '';
		$this->results = [
			Test::Passed => 0,
			Test::Skipped => 0,
			Test::Failed => 0,
		];
		$this->startTime = microtime(true);
		fwrite($this->file, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<testsuites>\n");
	}


	public function prepare(Test $test): void
	{
	}


	public function finish(Test $test): void
	{
		$this->results[$test->getResult()]++;
		$this->buffer .= "\t\t<testcase classname=\"" . htmlspecialchars($test->getSignature()) . '" name="' . htmlspecialchars($test->getSignature()) . '"';
		$this->buffer .= match ($test->getResult()) {
			Test::Failed => ">\n\t\t\t<failure message=\"" . htmlspecialchars($test->message, ENT_COMPAT | ENT_HTML5) . "\"/>\n\t\t</testcase>\n",
			Test::Skipped => ">\n\t\t\t<skipped/>\n\t\t</testcase>\n",
			Test::Passed => "/>\n",
		};
	}


	public function end(): void
	{
		$time = sprintf('%0.1f', microtime(true) - $this->startTime);
		$output = $this->buffer;
		$this->buffer = "\t<testsuite errors=\"{$this->results[Test::Failed]}\" skipped=\"{$this->results[Test::Skipped]}\" tests=\"" . array_sum($this->results) . "\" time=\"$time\" timestamp=\"" . @date('Y-m-d\TH:i:s') . "\">\n";
		$this->buffer .= $output;
		$this->buffer .= "\t</testsuite>";

		fwrite($this->file, $this->buffer . "\n</testsuites>\n");
	}
}
