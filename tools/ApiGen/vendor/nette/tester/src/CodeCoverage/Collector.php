<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester\CodeCoverage;
use pcov;


/**
 * Code coverage collector.
 */
class Collector
{
	public const
		EnginePcov = 'PCOV',
		EnginePhpdbg = 'PHPDBG',
		EngineXdebug = 'Xdebug';

	/** @var resource */
	private static $file;
	private static string $engine;


	public static function detectEngines(): array
	{
		return array_filter([
			extension_loaded('pcov') ? [self::EnginePcov, phpversion('pcov')] : null,
			defined('PHPDBG_VERSION') ? [self::EnginePhpdbg, PHPDBG_VERSION] : null,
			extension_loaded('xdebug') ? [self::EngineXdebug, phpversion('xdebug')] : null,
		]);
	}


	public static function isStarted(): bool
	{
		return self::$file !== null;
	}


	/**
	 * Starts gathering the information for code coverage.
	 * @throws \LogicException
	 */
	public static function start(string $file, string $engine): void
	{
		if (self::isStarted()) {
			throw new \LogicException('Code coverage collector has been already started.');

		} elseif (!in_array(
			$engine,
			array_map(fn(array $engineInfo) => $engineInfo[0], self::detectEngines()),
			true
		)) {
			throw new \LogicException("Code coverage engine '$engine' is not supported.");
		}

		self::$file = fopen($file, 'c+');
		self::$engine = $engine;
		self::{'start' . $engine}();

		register_shutdown_function(function (): void {
			register_shutdown_function([self::class, 'save']);
		});
	}


	/**
	 * Flushes all gathered information. Effective only with PHPDBG collector.
	 */
	public static function flush(): void
	{
		if (self::isStarted() && self::$engine === self::EnginePhpdbg) {
			self::save();
		}
	}


	/**
	 * Saves information about code coverage. Can be called repeatedly to free memory.
	 * @throws \LogicException
	 */
	public static function save(): void
	{
		if (!self::isStarted()) {
			throw new \LogicException('Code coverage collector has not been started.');
		}

		[$positive, $negative] = self::{'collect' . self::$engine}();

		flock(self::$file, LOCK_EX);
		fseek(self::$file, 0);
		$rawContent = stream_get_contents(self::$file);
		$original = $rawContent ? unserialize($rawContent) : [];
		$coverage = array_replace_recursive($negative, $original, $positive);

		fseek(self::$file, 0);
		ftruncate(self::$file, 0);
		fwrite(self::$file, serialize($coverage));
		flock(self::$file, LOCK_UN);
	}


	private static function startPCOV(): void
	{
		pcov\start();
	}


	/**
	 * Collects information about code coverage.
	 */
	private static function collectPCOV(): array
	{
		$positive = $negative = [];

		pcov\stop();

		foreach (pcov\collect() as $file => $lines) {
			if (!file_exists($file)) {
				continue;
			}

			foreach ($lines as $num => $val) {
				if ($val > 0) {
					$positive[$file][$num] = $val;
				} else {
					$negative[$file][$num] = $val;
				}
			}
		}

		return [$positive, $negative];
	}


	private static function startXdebug(): void
	{
		xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
	}


	/**
	 * Collects information about code coverage.
	 */
	private static function collectXdebug(): array
	{
		$positive = $negative = [];

		foreach (xdebug_get_code_coverage() as $file => $lines) {
			if (!file_exists($file)) {
				continue;
			}

			foreach ($lines as $num => $val) {
				if ($val > 0) {
					$positive[$file][$num] = $val;
				} else {
					$negative[$file][$num] = $val;
				}
			}
		}

		return [$positive, $negative];
	}


	private static function startPhpDbg(): void
	{
		phpdbg_start_oplog();
	}


	/**
	 * Collects information about code coverage.
	 */
	private static function collectPhpDbg(): array
	{
		$positive = phpdbg_end_oplog();
		$negative = phpdbg_get_executable();

		foreach ($positive as $file => &$lines) {
			$lines = array_fill_keys(array_keys($lines), 1);
		}

		foreach ($negative as $file => &$lines) {
			$lines = array_fill_keys(array_keys($lines), -1);
		}

		phpdbg_start_oplog();
		return [$positive, $negative];
	}
}
