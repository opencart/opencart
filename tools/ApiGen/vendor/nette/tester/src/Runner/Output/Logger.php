<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester\Runner\Output;

use Tester;
use Tester\Runner\Runner;
use Tester\Runner\Test;


/**
 * Verbose logger.
 */
class Logger implements Tester\Runner\OutputHandler
{
	/** @var resource */
	private $file;
	private Runner $runner;
	private int $count;
	private array $results;


	public function __construct(Runner $runner, ?string $file = null)
	{
		$this->runner = $runner;
		$this->file = fopen($file ?: 'php://output', 'w');
	}


	public function begin(): void
	{
		$this->count = 0;
		$this->results = [
			Test::Passed => 0,
			Test::Skipped => 0,
			Test::Failed => 0,
		];
		fwrite($this->file, 'PHP ' . $this->runner->getInterpreter()->getVersion()
			. ' | ' . $this->runner->getInterpreter()->getCommandLine()
			. " | {$this->runner->threadCount} threads\n\n");
	}


	public function prepare(Test $test): void
	{
		$this->count++;
	}


	public function finish(Test $test): void
	{
		$this->results[$test->getResult()]++;
		$message = '   ' . str_replace("\n", "\n   ", Tester\Dumper::removeColors(trim((string) $test->message)));
		$outputs = [
			Test::Passed => "-- OK: {$test->getSignature()}",
			Test::Skipped => "-- Skipped: {$test->getSignature()}\n$message",
			Test::Failed => "-- FAILED: {$test->getSignature()}\n$message",
		];
		fwrite($this->file, $outputs[$test->getResult()] . "\n\n");
	}


	public function end(): void
	{
		$run = array_sum($this->results);
		fwrite(
			$this->file,
			($this->results[Test::Failed] ? 'FAILURES!' : 'OK')
			. " ($this->count tests"
			. ($this->results[Test::Failed] ? ", {$this->results[Test::Failed]} failures" : '')
			. ($this->results[Test::Skipped] ? ", {$this->results[Test::Skipped]} skipped" : '')
			. ($this->count !== $run ? ', ' . ($this->count - $run) . ' not run' : '')
			. ')'
		);
	}
}
