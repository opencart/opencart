<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester\Runner;

use Tester;
use Tester\Dumper;
use Tester\Helpers;
use Tester\TestCase;


/**
 * Default test behavior.
 */
class TestHandler
{
	private const HttpOk = 200;
	private Runner $runner;
	private ?string $tempDir = null;


	public function __construct(Runner $runner)
	{
		$this->runner = $runner;
	}


	public function setTempDirectory(?string $path): void
	{
		$this->tempDir = $path;
	}


	public function initiate(string $file): void
	{
		[$annotations, $title] = $this->getAnnotations($file);
		$php = $this->runner->getInterpreter();

		$tests = [new Test($file, $title)];
		foreach (get_class_methods($this) as $method) {
			if (!preg_match('#^initiate(.+)#', strtolower($method), $m) || !isset($annotations[$m[1]])) {
				continue;
			}

			foreach ((array) $annotations[$m[1]] as $value) {
				/** @var Test[] $prepared */
				$prepared = [];
				foreach ($tests as $test) {
					$res = $this->$method($test, $value, $php);
					if ($res === null) {
						$prepared[] = $test;
					} else {
						foreach (is_array($res) ? $res : [$res] as $testVariety) {
							\assert($testVariety instanceof Test);
							if ($testVariety->hasResult()) {
								$this->runner->prepareTest($testVariety);
								$this->runner->finishTest($testVariety);
							} else {
								$prepared[] = $testVariety;
							}
						}
					}
				}

				$tests = $prepared;
			}
		}

		foreach ($tests as $test) {
			$this->runner->prepareTest($test);
			$job = new Job($test, $php, $this->runner->getEnvironmentVariables());
			$job->setTempDirectory($this->tempDir);
			$this->runner->addJob($job);
		}
	}


	public function assess(Job $job): void
	{
		$test = $job->getTest();
		$annotations = $this->getAnnotations($test->getFile())[0] += [
			'exitcode' => Job::CodeOk,
			'httpcode' => self::HttpOk,
		];

		foreach (get_class_methods($this) as $method) {
			if (!preg_match('#^assess(.+)#', strtolower($method), $m) || !isset($annotations[$m[1]])) {
				continue;
			}

			foreach ((array) $annotations[$m[1]] as $arg) {
				/** @var Test|null $res */
				if ($res = $this->$method($job, $arg)) {
					$this->runner->finishTest($res);
					return;
				}
			}
		}

		$this->runner->finishTest($test->withResult(Test::Passed, $test->message, $job->getDuration()));
	}


	private function initiateSkip(Test $test, string $message): Test
	{
		return $test->withResult(Test::Skipped, $message);
	}


	private function initiatePhpVersion(Test $test, string $version, PhpInterpreter $interpreter): ?Test
	{
		if (preg_match('#^(<=|<|==|=|!=|<>|>=|>)?\s*(.+)#', $version, $matches)
			&& version_compare($matches[2], $interpreter->getVersion(), $matches[1] ?: '>=')) {
			return $test->withResult(Test::Skipped, "Requires PHP $version.");
		}

		return null;
	}


	private function initiatePhpExtension(Test $test, string $value, PhpInterpreter $interpreter): ?Test
	{
		foreach (preg_split('#[\s,]+#', $value) as $extension) {
			if (!$interpreter->hasExtension($extension)) {
				return $test->withResult(Test::Skipped, "Requires PHP extension $extension.");
			}
		}

		return null;
	}


	private function initiatePhpIni(Test $test, string $pair, PhpInterpreter &$interpreter): void
	{
		[$name, $value] = explode('=', $pair, 2) + [1 => null];
		$interpreter = $interpreter->withPhpIniOption($name, $value);
	}


	private function initiateDataProvider(Test $test, string $provider): array|Test
	{
		try {
			[$dataFile, $query, $optional] = Tester\DataProvider::parseAnnotation($provider, $test->getFile());
			$data = Tester\DataProvider::load($dataFile, $query);
			if (count($data) < 1) {
				throw new \Exception("No records in data provider file '{$test->getFile()}'" . ($query ? " for query '$query'" : '') . '.');
			}
		} catch (\Throwable $e) {
			return $test->withResult(empty($optional) ? Test::Failed : Test::Skipped, $e->getMessage());
		}

		return array_map(
			fn(string $item): Test => $test->withArguments(['dataprovider' => "$item|$dataFile"]),
			array_keys($data)
		);
	}


	private function initiateMultiple(Test $test, string $count): array
	{
		return array_map(
			fn(int $i): Test => $test->withArguments(['multiple' => $i]),
			range(0, (int) $count - 1)
		);
	}


	private function initiateTestCase(Test $test, $foo, PhpInterpreter $interpreter)
	{
		$methods = null;

		if ($this->tempDir) {
			$cacheFile = $this->tempDir . DIRECTORY_SEPARATOR . 'TestHandler.testCase.' . substr(md5($test->getSignature()), 0, 5) . '.list';
			if (is_file($cacheFile)) {
				$cache = unserialize(file_get_contents($cacheFile));

				$valid = true;
				foreach ($cache['files'] as $path => $mTime) {
					if (!is_file($path) || filemtime($path) !== $mTime) {
						$valid = false;
						break;
					}
				}

				if ($valid) {
					$methods = $cache['methods'];
				}
			}
		}

		if ($methods === null) {
			$job = new Job($test->withArguments(['method' => TestCase::ListMethods]), $interpreter, $this->runner->getEnvironmentVariables());
			$job->setTempDirectory($this->tempDir);
			$job->run();

			if (in_array($job->getExitCode(), [Job::CodeError, Job::CodeFail, Job::CodeSkip], true)) {
				return $test->withResult($job->getExitCode() === Job::CodeSkip ? Test::Skipped : Test::Failed, $job->getTest()->getOutput());
			}

			$stdout = $job->getTest()->stdout;

			if (!preg_match('#^TestCase:([^\n]+)$#m', $stdout, $m)) {
				return $test->withResult(Test::Failed, "Cannot list TestCase methods in file '{$test->getFile()}'. Do you call TestCase::run() in it?");
			}

			$testCaseClass = $m[1];

			preg_match_all('#^Method:([^\n]+)$#m', $stdout, $m);
			if (count($m[1]) < 1) {
				return $test->withResult(Test::Skipped, "Class $testCaseClass in file '{$test->getFile()}' does not contain test methods.");
			}

			$methods = $m[1];

			if ($this->tempDir) {
				preg_match_all('#^Dependency:([^\n]+)$#m', $stdout, $m);
				file_put_contents($cacheFile, serialize([
					'methods' => $methods,
					'files' => array_combine($m[1], array_map('filemtime', $m[1])),
				]));
			}
		}

		return array_map(
			fn(string $method): Test => $test->withArguments(['method' => $method]),
			$methods
		);
	}


	private function assessExitCode(Job $job, string|int $code): ?Test
	{
		$code = (int) $code;
		if ($job->getExitCode() === Job::CodeSkip) {
			$message = preg_match('#.*Skipped:\n(.*?)$#Ds', $output = $job->getTest()->stdout, $m)
				? $m[1]
				: $output;
			return $job->getTest()->withResult(Test::Skipped, trim($message));

		} elseif ($job->getExitCode() !== $code) {
			$message = $job->getExitCode() !== Job::CodeFail
				? "Exited with error code {$job->getExitCode()} (expected $code)"
				: '';
			return $job->getTest()->withResult(Test::Failed, trim($message . "\n" . $job->getTest()->getOutput()));
		}

		return null;
	}


	private function assessHttpCode(Job $job, string|int $code): ?Test
	{
		if (!$this->runner->getInterpreter()->isCgi()) {
			return null;
		}

		$headers = $job->getHeaders();
		$actual = (int) ($headers['Status'] ?? self::HttpOk);
		$code = (int) $code;
		return $code && $code !== $actual
			? $job->getTest()->withResult(Test::Failed, "Exited with HTTP code $actual (expected $code)")
			: null;
	}


	private function assessOutputMatchFile(Job $job, string $file): ?Test
	{
		$file = dirname($job->getTest()->getFile()) . DIRECTORY_SEPARATOR . $file;
		if (!is_file($file)) {
			return $job->getTest()->withResult(Test::Failed, "Missing matching file '$file'.");
		}

		return $this->assessOutputMatch($job, file_get_contents($file));
	}


	private function assessOutputMatch(Job $job, string $content): ?Test
	{
		$actual = $job->getTest()->stdout;
		if (!Tester\Assert::isMatching($content, $actual)) {
			[$content, $actual] = Tester\Assert::expandMatchingPatterns($content, $actual);
			Dumper::saveOutput($job->getTest()->getFile(), $actual, '.actual');
			Dumper::saveOutput($job->getTest()->getFile(), $content, '.expected');
			return $job->getTest()->withResult(Test::Failed, 'Failed: output should match ' . Dumper::toLine($content));
		}

		return null;
	}


	private function getAnnotations(string $file): array
	{
		$annotations = Helpers::parseDocComment(file_get_contents($file));
		$testTitle = isset($annotations[0])
			? preg_replace('#^TEST:\s*#i', '', $annotations[0])
			: null;
		return [$annotations, $testTitle];
	}
}
