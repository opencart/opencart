<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester\Runner;

use Tester\Environment;


/**
 * Test runner.
 */
class Runner
{
	/** @var string[]  paths to test files/directories */
	public array $paths = [];

	/** @var string[] */
	public array $ignoreDirs = ['vendor'];
	public int $threadCount = 1;
	public TestHandler $testHandler;

	/** @var OutputHandler[] */
	public array $outputHandlers = [];
	public bool $stopOnFail = false;
	private PhpInterpreter $interpreter;
	private array $envVars = [];

	/** @var Job[] */
	private array $jobs;
	private bool $interrupted = false;
	private ?string $tempDir = null;
	private bool $result;
	private array $lastResults = [];


	public function __construct(PhpInterpreter $interpreter)
	{
		$this->interpreter = $interpreter;
		$this->testHandler = new TestHandler($this);
	}


	public function setEnvironmentVariable(string $name, string $value): void
	{
		$this->envVars[$name] = $value;
	}


	public function getEnvironmentVariables(): array
	{
		return $this->envVars;
	}


	public function addPhpIniOption(string $name, ?string $value = null): void
	{
		$this->interpreter = $this->interpreter->withPhpIniOption($name, $value);
	}


	public function setTempDirectory(?string $path): void
	{
		$this->tempDir = $path;
		$this->testHandler->setTempDirectory($path);
	}


	/**
	 * Runs all tests.
	 */
	public function run(): bool
	{
		$this->result = true;
		$this->interrupted = false;

		foreach ($this->outputHandlers as $handler) {
			$handler->begin();
		}

		$this->jobs = $running = [];
		foreach ($this->paths as $path) {
			$this->findTests($path);
		}

		if ($this->tempDir) {
			usort(
				$this->jobs,
				fn(Job $a, Job $b): int => $this->getLastResult($a->getTest()) - $this->getLastResult($b->getTest())
			);
		}

		$threads = range(1, $this->threadCount);

		$async = $this->threadCount > 1 && count($this->jobs) > 1;

		try {
			while (($this->jobs || $running) && !$this->interrupted) {
				while ($threads && $this->jobs) {
					$running[] = $job = array_shift($this->jobs);
					$job->setEnvironmentVariable(Environment::VariableThread, (string) array_shift($threads));
					$job->run(async: $async);
				}

				if ($async) {
					usleep(Job::RunSleep); // stream_select() doesn't work with proc_open()
				}

				foreach ($running as $key => $job) {
					if ($this->interrupted) {
						break 2;
					}

					if (!$job->isRunning()) {
						$threads[] = $job->getEnvironmentVariable(Environment::VariableThread);
						$this->testHandler->assess($job);
						unset($running[$key]);
					}
				}
			}
		} finally {
			foreach ($this->outputHandlers as $handler) {
				$handler->end();
			}
		}

		return $this->result;
	}


	private function findTests(string $path): void
	{
		if (strpbrk($path, '*?') === false && !file_exists($path)) {
			throw new \InvalidArgumentException("File or directory '$path' not found.");
		}

		if (is_dir($path)) {
			foreach (glob(str_replace('[', '[[]', $path) . '/*', GLOB_ONLYDIR) ?: [] as $dir) {
				if (in_array(basename($dir), $this->ignoreDirs, true)) {
					continue;
				}

				$this->findTests($dir);
			}

			$this->findTests($path . '/*.phpt');
			$this->findTests($path . '/*Test.php');

		} else {
			foreach (glob(str_replace('[', '[[]', $path)) ?: [] as $file) {
				if (is_file($file)) {
					$this->testHandler->initiate(realpath($file));
				}
			}
		}
	}


	/**
	 * Appends new job to queue.
	 */
	public function addJob(Job $job): void
	{
		$this->jobs[] = $job;
	}


	public function prepareTest(Test $test): void
	{
		foreach ($this->outputHandlers as $handler) {
			$handler->prepare($test);
		}
	}


	/**
	 * Writes to output handlers.
	 */
	public function finishTest(Test $test): void
	{
		$this->result = $this->result && ($test->getResult() !== Test::Failed);

		foreach ($this->outputHandlers as $handler) {
			$handler->finish($test);
		}

		if ($this->tempDir) {
			$lastResult = &$this->lastResults[$test->getSignature()];
			if ($lastResult !== $test->getResult()) {
				file_put_contents($this->getLastResultFilename($test), $lastResult = $test->getResult());
			}
		}

		if ($this->stopOnFail && $test->getResult() === Test::Failed) {
			$this->interrupted = true;
		}
	}


	public function getInterpreter(): PhpInterpreter
	{
		return $this->interpreter;
	}


	private function getLastResult(Test $test): int
	{
		$signature = $test->getSignature();
		if (isset($this->lastResults[$signature])) {
			return $this->lastResults[$signature];
		}

		$file = $this->getLastResultFilename($test);
		if (is_file($file)) {
			return $this->lastResults[$signature] = (int) file_get_contents($file);
		}

		return $this->lastResults[$signature] = Test::Prepared;
	}


	private function getLastResultFilename(Test $test): string
	{
		return $this->tempDir
			. DIRECTORY_SEPARATOR
			. pathinfo($test->getFile(), PATHINFO_FILENAME)
			. '.'
			. substr(md5($test->getSignature()), 0, 5)
			. '.result';
	}
}
