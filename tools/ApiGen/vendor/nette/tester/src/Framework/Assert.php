<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester;


/**
 * Assertion test helpers.
 */
class Assert
{
	/** used by equal() for comparing floats */
	private const Epsilon = 1e-10;

	/** used by match(); in values, each $ followed by number is backreference */
	public static $patterns = [
		'%%' => '%',            // one % character
		'%a%' => '[^\r\n]+',    // one or more of anything except the end of line characters
		'%a\?%' => '[^\r\n]*',  // zero or more of anything except the end of line characters
		'%A%' => '.+',          // one or more of anything including the end of line characters
		'%A\?%' => '.*',        // zero or more of anything including the end of line characters
		'%s%' => '[\t ]+',      // one or more white space characters except the end of line characters
		'%s\?%' => '[\t ]*',    // zero or more white space characters except the end of line characters
		'%S%' => '\S+',         // one or more of characters except the white space
		'%S\?%' => '\S*',       // zero or more of characters except the white space
		'%c%' => '[^\r\n]',     // a single character of any sort (except the end of line)
		'%d%' => '[0-9]+',      // one or more digits
		'%d\?%' => '[0-9]*',    // zero or more digits
		'%i%' => '[+-]?[0-9]+', // signed integer value
		'%f%' => '[+-]?\.?\d+\.?\d*(?:[Ee][+-]?\d+)?', // floating point number
		'%h%' => '[0-9a-fA-F]+', // one or more HEX digits
		'%w%' => '[0-9a-zA-Z_]+', //one or more alphanumeric characters
		'%ds%' => '[\\\\/]',    // directory separator
		'%(\[.+\][+*?{},\d]*)%' => '$1', // range
	];

	/** expand patterns in match() and matchFile() */
	public static bool $expandPatterns = true;

	/** @var callable  function (AssertException $exception): void */
	public static $onFailure;
	public static int $counter = 0;


	/**
	 * Asserts that two values are equal and have the same type and identity of objects.
	 */
	public static function same(mixed $expected, mixed $actual, ?string $description = null): void
	{
		self::$counter++;
		if ($actual !== $expected) {
			self::fail(self::describe('%1 should be %2', $description), $actual, $expected);
		}
	}


	/**
	 * Asserts that two values are not equal or do not have the same type and identity of objects.
	 */
	public static function notSame(mixed $expected, mixed $actual, ?string $description = null): void
	{
		self::$counter++;
		if ($actual === $expected) {
			self::fail(self::describe('%1 should not be %2', $description), $actual, $expected);
		}
	}


	/**
	 * Asserts that two values are equal and checks expectations. The identity of objects,
	 * the order of keys in the arrays and marginally different floats are ignored by default.
	 */
	public static function equal(
		mixed $expected,
		mixed $actual,
		?string $description = null,
		bool $matchOrder = false,
		bool $matchIdentity = false,
	): void
	{
		self::$counter++;
		if (!self::isEqual($expected, $actual, $matchOrder, $matchIdentity)) {
			self::fail(self::describe('%1 should be equal to %2', $description), $actual, $expected);
		}
	}


	/**
	 * Asserts that two values are not equal and checks expectations. The identity of objects,
	 * the order of keys in the arrays and marginally different floats are ignored.
	 */
	public static function notEqual(mixed $expected, mixed $actual, ?string $description = null): void
	{
		self::$counter++;
		try {
			$res = self::isEqual($expected, $actual, matchOrder: false, matchIdentity: false);
		} catch (AssertException $e) {
		}

		if (empty($e) && $res) {
			self::fail(self::describe('%1 should not be equal to %2', $description), $actual, $expected);
		}
	}


	/**
	 * Asserts that a haystack (string or array) contains an expected needle.
	 */
	public static function contains(mixed $needle, array|string $actual, ?string $description = null): void
	{
		self::$counter++;
		if (is_array($actual)) {
			if (!in_array($needle, $actual, true)) {
				self::fail(self::describe('%1 should contain %2', $description), $actual, $needle);
			}
		} elseif (!is_string($needle)) {
			self::fail(self::describe('Needle %1 should be string'), $needle);

		} elseif ($needle !== '' && !str_contains($actual, $needle)) {
			self::fail(self::describe('%1 should contain %2', $description), $actual, $needle);
		}
	}


	/**
	 * Asserts that a haystack (string or array) does not contain an expected needle.
	 */
	public static function notContains(mixed $needle, array|string $actual, ?string $description = null): void
	{
		self::$counter++;
		if (is_array($actual)) {
			if (in_array($needle, $actual, true)) {
				self::fail(self::describe('%1 should not contain %2', $description), $actual, $needle);
			}
		} elseif (!is_string($needle)) {
			self::fail(self::describe('Needle %1 should be string'), $needle);

		} elseif ($needle === '' || str_contains($actual, $needle)) {
			self::fail(self::describe('%1 should not contain %2', $description), $actual, $needle);
		}
	}


	/**
	 * Asserts that a haystack has an expected key.
	 */
	public static function hasKey(string|int $key, array $actual, ?string $description = null): void
	{
		self::$counter++;
		if (!array_key_exists($key, $actual)) {
			self::fail(self::describe('%1 should contain key %2', $description), $actual, $key);
		}
	}


	/**
	 * Asserts that a haystack doesn't have an expected key.
	 */
	public static function hasNotKey(string|int $key, array $actual, ?string $description = null): void
	{
		self::$counter++;
		if (array_key_exists($key, $actual)) {
			self::fail(self::describe('%1 should not contain key %2', $description), $actual, $key);
		}
	}


	/**
	 * Asserts that a value is true.
	 */
	public static function true(mixed $actual, ?string $description = null): void
	{
		self::$counter++;
		if ($actual !== true) {
			self::fail(self::describe('%1 should be true', $description), $actual);
		}
	}


	/**
	 * Asserts that a value is false.
	 */
	public static function false(mixed $actual, ?string $description = null): void
	{
		self::$counter++;
		if ($actual !== false) {
			self::fail(self::describe('%1 should be false', $description), $actual);
		}
	}


	/**
	 * Asserts that a value is null.
	 */
	public static function null(mixed $actual, ?string $description = null): void
	{
		self::$counter++;
		if ($actual !== null) {
			self::fail(self::describe('%1 should be null', $description), $actual);
		}
	}


	/**
	 * Asserts that a value is not null.
	 */
	public static function notNull(mixed $actual, ?string $description = null): void
	{
		self::$counter++;
		if ($actual === null) {
			self::fail(self::describe('Value should not be null', $description));
		}
	}


	/**
	 * Asserts that a value is Not a Number.
	 */
	public static function nan(mixed $actual, ?string $description = null): void
	{
		self::$counter++;
		if (!is_float($actual) || !is_nan($actual)) {
			self::fail(self::describe('%1 should be NAN', $description), $actual);
		}
	}


	/**
	 * Asserts that a value is truthy.
	 */
	public static function truthy(mixed $actual, ?string $description = null): void
	{
		self::$counter++;
		if (!$actual) {
			self::fail(self::describe('%1 should be truthy', $description), $actual);
		}
	}


	/**
	 * Asserts that a value is falsey.
	 */
	public static function falsey(mixed $actual, ?string $description = null): void
	{
		self::$counter++;
		if ($actual) {
			self::fail(self::describe('%1 should be falsey', $description), $actual);
		}
	}


	/**
	 * Asserts the number of items in an array or Countable.
	 */
	public static function count(int $count, array|\Countable $value, ?string $description = null): void
	{
		self::$counter++;
		if (count($value) !== $count) {
			self::fail(self::describe('Count %1 should be %2', $description), count($value), $count);
		}
	}


	/**
	 * Asserts that a value is of given class, interface or built-in type.
	 */
	public static function type(string|object $type, mixed $value, ?string $description = null): void
	{
		self::$counter++;
		if ($type === 'list') {
			if (!is_array($value) || ($value && array_keys($value) !== range(0, count($value) - 1))) {
				self::fail(self::describe("%1 should be $type", $description), $value);
			}
		} elseif (in_array($type, ['array', 'bool', 'callable', 'float',
			'int', 'integer', 'null', 'object', 'resource', 'scalar', 'string', ], true)
		) {
			if (!("is_$type")($value)) {
				self::fail(self::describe(gettype($value) . " should be $type", $description));
			}
		} elseif (!$value instanceof $type) {
			$actual = is_object($value) ? $value::class : gettype($value);
			$type = is_object($type) ? $type::class : $type;
			self::fail(self::describe("$actual should be instance of $type", $description));
		}
	}


	/**
	 * Asserts that a function throws exception of given type and its message matches given pattern.
	 */
	public static function exception(
		callable $function,
		string $class,
		?string $message = null,
		$code = null
	): ?\Throwable
	{
		self::$counter++;
		$e = null;
		try {
			$function();
		} catch (\Throwable $e) {
		}

		if ($e === null) {
			self::fail("$class was expected, but none was thrown");

		} elseif (!$e instanceof $class) {
			self::fail("$class was expected but got " . $e::class . ($e->getMessage() ? " ({$e->getMessage()})" : ''), null, null, $e);

		} elseif ($message && !self::isMatching($message, $e->getMessage())) {
			self::fail("$class with a message matching %2 was expected but got %1", $e->getMessage(), $message, $e);

		} elseif ($code !== null && $e->getCode() !== $code) {
			self::fail("$class with a code %2 was expected but got %1", $e->getCode(), $code, $e);
		}

		return $e;
	}


	/**
	 * Asserts that a function throws exception of given type and its message matches given pattern. Alias for exception().
	 */
	public static function throws(
		callable $function,
		string $class,
		?string $message = null,
		mixed $code = null
	): ?\Throwable
	{
		return self::exception($function, $class, $message, $code);
	}


	/**
	 * Asserts that a function generates one or more PHP errors or throws exceptions.
	 * @throws \Exception
	 */
	public static function error(
		callable $function,
		int|string|array $expectedType,
		?string $expectedMessage = null
	): ?\Throwable
	{
		if (is_string($expectedType) && !preg_match('#^E_[A-Z_]+$#D', $expectedType)) {
			return static::exception($function, $expectedType, $expectedMessage);
		}

		self::$counter++;
		$expected = is_array($expectedType) ? $expectedType : [[$expectedType, $expectedMessage]];
		foreach ($expected as &$item) {
			$item = ((array) $item) + [null, null];
			$expectedType = $item[0];
			if (is_int($expectedType)) {
				$item[2] = Helpers::errorTypeToString($expectedType);
			} elseif (is_string($expectedType)) {
				$item[0] = constant($item[2] = $expectedType);
			} else {
				throw new \Exception('Error type must be E_* constant.');
			}
		}

		set_error_handler(function (int $severity, string $message, string $file, int $line) use (&$expected) {
			if (($severity & error_reporting()) !== $severity) {
				return;
			}

			$errorStr = Helpers::errorTypeToString($severity) . ($message ? " ($message)" : '');
			[$expectedType, $expectedMessage, $expectedTypeStr] = array_shift($expected);
			if ($expectedType === null) {
				self::fail("Generated more errors than expected: $errorStr was generated in file $file on line $line");

			} elseif ($severity !== $expectedType) {
				self::fail("$expectedTypeStr was expected, but $errorStr was generated in file $file on line $line");

			} elseif ($expectedMessage && !self::isMatching($expectedMessage, $message)) {
				self::fail("$expectedTypeStr with a message matching %2 was expected but got %1", $message, $expectedMessage);
			}
		});

		reset($expected);
		try {
			$function();
			restore_error_handler();
		} catch (\Throwable $e) {
			restore_error_handler();
			throw $e;
		}

		if ($expected) {
			self::fail('Error was expected, but was not generated');
		}

		return null;
	}


	/**
	 * Asserts that a function does not generate PHP errors and does not throw exceptions.
	 */
	public static function noError(callable $function): void
	{
		if (($count = func_num_args()) > 1) {
			throw new \Exception(__METHOD__ . "() expects 1 parameter, $count given.");
		}

		self::error($function, []);
	}


	/**
	 * Asserts that a string matches a given pattern.
	 *   %a%    one or more of anything except the end of line characters
	 *   %a?%   zero or more of anything except the end of line characters
	 *   %A%    one or more of anything including the end of line characters
	 *   %A?%   zero or more of anything including the end of line characters
	 *   %s%    one or more white space characters except the end of line characters
	 *   %s?%   zero or more white space characters except the end of line characters
	 *   %S%    one or more of characters except the white space
	 *   %S?%   zero or more of characters except the white space
	 *   %c%    a single character of any sort (except the end of line)
	 *   %d%    one or more digits
	 *   %d?%   zero or more digits
	 *   %i%    signed integer value
	 *   %f%    floating point number
	 *   %h%    one or more HEX digits
	 * @param  string  $pattern  mask|regexp; only delimiters ~ and # are supported for regexp
	 */
	public static function match(string $pattern, string $actual, ?string $description = null): void
	{
		self::$counter++;
		if (!self::isMatching($pattern, $actual)) {
			if (self::$expandPatterns) {
				[$pattern, $actual] = self::expandMatchingPatterns($pattern, $actual);
			}

			self::fail(self::describe('%1 should match %2', $description), $actual, $pattern);
		}
	}


	/**
	 * Asserts that a string matches a given pattern stored in file.
	 */
	public static function matchFile(string $file, string $actual, ?string $description = null): void
	{
		self::$counter++;
		$pattern = @file_get_contents($file); // @ is escalated to exception
		if ($pattern === false) {
			throw new \Exception("Unable to read file '$file'.");

		} elseif (!self::isMatching($pattern, $actual)) {
			if (self::$expandPatterns) {
				[$pattern, $actual] = self::expandMatchingPatterns($pattern, $actual);
			}

			self::fail(self::describe('%1 should match %2', $description), $actual, $pattern, null, basename($file));
		}
	}


	/**
	 * Assertion that fails.
	 */
	public static function fail(
		string $message,
		$actual = null,
		$expected = null,
		?\Throwable $previous = null,
		?string $outputName = null
	): void
	{
		$e = new AssertException($message, $expected, $actual, $previous);
		$e->outputName = $outputName;
		if (self::$onFailure) {
			(self::$onFailure)($e);
		} else {
			throw $e;
		}
	}


	private static function describe(string $reason, ?string $description = null): string
	{
		return ($description ? $description . ': ' : '') . $reason;
	}


	/**
	 * Executes function that can access private and protected members of given object via $this.
	 */
	public static function with(object|string $objectOrClass, \Closure $closure): mixed
	{
		return $closure->bindTo(is_object($objectOrClass) ? $objectOrClass : null, $objectOrClass)();
	}


	/********************* helpers ****************d*g**/


	/**
	 * Compares using mask.
	 * @internal
	 */
	public static function isMatching(string $pattern, string $actual, bool $strict = false): bool
	{
		$old = ini_set('pcre.backtrack_limit', '10000000');

		if (!self::isPcre($pattern)) {
			$utf8 = preg_match('#\x80-\x{10FFFF}]#u', $pattern) ? 'u' : '';
			$suffix = ($strict ? '$#DsU' : '\s*$#sU') . $utf8;
			$patterns = static::$patterns + [
				'[.\\\\+*?[^$(){|\#]' => '\$0', // preg quoting
				'\x00' => '\x00',
				'[\t ]*\r?\n' => '[\t ]*\r?\n', // right trim
			];
			$pattern = '#^' . preg_replace_callback('#' . implode('|', array_keys($patterns)) . '#U' . $utf8, function ($m) use ($patterns) {
				foreach ($patterns as $re => $replacement) {
					$s = preg_replace("#^$re$#D", str_replace('\\', '\\\\', $replacement), $m[0], 1, $count);
					if ($count) {
						return $s;
					}
				}
			}, rtrim($pattern, " \t\n\r")) . $suffix;
		}

		$res = preg_match($pattern, (string) $actual);
		ini_set('pcre.backtrack_limit', $old);
		if ($res === false || preg_last_error()) {
			throw new \Exception('Error while executing regular expression. (PREG Error Code ' . preg_last_error() . ')');
		}

		return (bool) $res;
	}


	/**
	 * @internal
	 */
	public static function expandMatchingPatterns(string $pattern, string $actual): array
	{
		if (self::isPcre($pattern)) {
			return [$pattern, $actual];
		}

		$parts = preg_split('#(%)#', $pattern, -1, PREG_SPLIT_DELIM_CAPTURE);
		for ($i = count($parts); $i >= 0; $i--) {
			$patternX = implode('', array_slice($parts, 0, $i));
			$patternY = "$patternX%A?%";
			if (self::isMatching($patternY, $actual)) {
				$patternZ = implode('', array_slice($parts, $i));
				break;
			}
		}

		foreach (['%A%', '%A?%'] as $greedyPattern) {
			if (substr($patternX, -strlen($greedyPattern)) === $greedyPattern) {
				$patternX = substr($patternX, 0, -strlen($greedyPattern));
				$patternY = "$patternX%A?%";
				$patternZ = $greedyPattern . $patternZ;
				break;
			}
		}

		$low = 0;
		$high = strlen($actual);
		while ($low <= $high) {
			$mid = ($low + $high) >> 1;
			if (self::isMatching($patternY, substr($actual, 0, $mid))) {
				$high = $mid - 1;
			} else {
				$low = $mid + 1;
			}
		}

		$low = $high + 2;
		$high = strlen($actual);
		while ($low <= $high) {
			$mid = ($low + $high) >> 1;
			if (!self::isMatching($patternX, substr($actual, 0, $mid), true)) {
				$high = $mid - 1;
			} else {
				$low = $mid + 1;
			}
		}

		$actualX = substr($actual, 0, $high);
		$actualZ = substr($actual, $high);

		return [
			$actualX . rtrim(preg_replace('#[\t ]*\r?\n#', "\n", $patternZ)),
			$actualX . rtrim(preg_replace('#[\t ]*\r?\n#', "\n", $actualZ)),
		];
	}


	/**
	 * Compares two structures and checks expectations. The identity of objects, the order of keys
	 * in the arrays and marginally different floats are ignored.
	 */
	private static function isEqual(
		mixed $expected,
		mixed $actual,
		bool $matchOrder,
		bool $matchIdentity,
		int $level = 0,
		?\SplObjectStorage $objects = null
	): bool
	{
		switch (true) {
			case $level > 10:
				throw new \Exception('Nesting level too deep or recursive dependency.');

			case $expected instanceof Expect:
				$expected($actual);
				return true;

			case is_float($expected) && is_float($actual) && is_finite($expected) && is_finite($actual):
				$diff = abs($expected - $actual);
				return ($diff < self::Epsilon) || ($diff / max(abs($expected), abs($actual)) < self::Epsilon);

			case !$matchIdentity && is_object($expected) && is_object($actual) && $expected::class === $actual::class:
				$objects = $objects ? clone $objects : new \SplObjectStorage;
				if (isset($objects[$expected])) {
					return $objects[$expected] === $actual;
				} elseif ($expected === $actual) {
					return true;
				}

				$objects[$expected] = $actual;
				$objects[$actual] = $expected;
				$expected = (array) $expected;
				$actual = (array) $actual;
				// break omitted

			case is_array($expected) && is_array($actual):
				if ($matchOrder) {
					reset($expected);
					reset($actual);
				} else {
					ksort($expected, SORT_STRING);
					ksort($actual, SORT_STRING);
				}

				if (array_keys($expected) !== array_keys($actual)) {
					return false;
				}

				foreach ($expected as $value) {
					if (!self::isEqual($value, current($actual), $matchOrder, $matchIdentity, $level + 1, $objects)) {
						return false;
					}

					next($actual);
				}

				return true;

			default:
				return $expected === $actual;
		}
	}


	private static function isPcre(string $pattern): bool
	{
		return (bool) preg_match('/^([~#]).+(\1)[imsxUu]*$/Ds', $pattern);
	}
}
