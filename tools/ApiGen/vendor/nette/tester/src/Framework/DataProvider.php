<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester;


/**
 * Data provider helpers.
 * @internal
 */
class DataProvider
{
	/**
	 * @throws \Exception
	 */
	public static function load(string $file, string $query = ''): array
	{
		if (!is_file($file)) {
			throw new \Exception("Missing data provider file '$file'.");
		}

		if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
			$data = (fn() => require func_get_arg(0))(realpath($file));

			if ($data instanceof \Traversable) {
				$data = iterator_to_array($data);
			} elseif (!is_array($data)) {
				throw new \Exception("Data provider '$file' did not return array or Traversable.");
			}
		} else {
			$data = @parse_ini_file($file, true); // @ is escalated to exception
			if ($data === false) {
				throw new \Exception("Cannot parse data provider file '$file'.");
			}
		}

		foreach ($data as $key => $value) {
			if (!self::testQuery((string) $key, $query)) {
				unset($data[$key]);
			}
		}

		return $data;
	}


	public static function testQuery(string $input, string $query): bool
	{
		$replaces = ['' => '=', '=>' => '>=', '=<' => '<='];
		$tokens = preg_split('#\s+#', $input);
		preg_match_all('#\s*,?\s*(<=|=<|<|==|=|!=|<>|>=|=>|>)?\s*([^\s,]+)#A', $query, $queryParts, PREG_SET_ORDER);
		foreach ($queryParts as [, $operator, $operand]) {
			$operator = $replaces[$operator] ?? $operator;
			$token = (string) array_shift($tokens);
			$res = preg_match('#^[0-9.]+$#D', $token)
				? version_compare($token, $operand, $operator)
				: self::compare($token, $operator, $operand);
			if (!$res) {
				return false;
			}
		}

		return true;
	}


	private static function compare(mixed $l, string $operator, mixed $r): bool
	{
		return match ($operator) {
			'>' => $l > $r,
			'>=', '=>' => $l >= $r,
			'<' => $l < $r,
			'=<', '<=' => $l <= $r,
			'=', '==' => $l == $r,
			'!', '!=', '<>' => $l != $r,
			default => throw new \InvalidArgumentException("Unknown operator '$operator'"),
		};
	}


	/**
	 * @throws \Exception
	 */
	public static function parseAnnotation(string $annotation, string $file): array
	{
		if (!preg_match('#^(\??)\s*([^,\s]+)\s*,?\s*(\S.*)?()#', $annotation, $m)) {
			throw new \Exception("Invalid @dataProvider value '$annotation'.");
		}

		return [dirname($file) . DIRECTORY_SEPARATOR . $m[2], $m[3], (bool) $m[1]];
	}
}
