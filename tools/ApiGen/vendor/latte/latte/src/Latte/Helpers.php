<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;


/**
 * Latte helpers.
 * @internal
 */
class Helpers
{
	/** @var array<string, int>  empty (void) HTML elements */
	public static array $emptyElements = [
		'img' => 1, 'hr' => 1, 'br' => 1, 'input' => 1, 'meta' => 1, 'area' => 1, 'embed' => 1, 'keygen' => 1, 'source' => 1, 'base' => 1,
		'col' => 1, 'link' => 1, 'param' => 1, 'basefont' => 1, 'frame' => 1, 'isindex' => 1, 'wbr' => 1, 'command' => 1, 'track' => 1,
	];


	/**
	 * Finds the best suggestion.
	 * @param  string[]  $items
	 */
	public static function getSuggestion(array $items, string $value): ?string
	{
		$best = null;
		$min = (strlen($value) / 4 + 1) * 10 + .1;
		foreach (array_unique($items) as $item) {
			if (($len = levenshtein($item, $value, 10, 11, 10)) > 0 && $len < $min) {
				$min = $len;
				$best = $item;
			}
		}

		return $best;
	}


	/** intentionally without callable typehint, because it generates bad error messages */
	public static function toReflection($callable): \ReflectionFunctionAbstract
	{
		if (is_string($callable) && strpos($callable, '::')) {
			return new \ReflectionMethod($callable);
		} elseif (is_array($callable)) {
			return new \ReflectionMethod($callable[0], $callable[1]);
		} elseif (is_object($callable) && !$callable instanceof \Closure) {
			return new \ReflectionMethod($callable, '__invoke');
		} else {
			return new \ReflectionFunction($callable);
		}
	}


	public static function sortBeforeAfter(array $list): array
	{
		foreach ($list as $name => $info) {
			if (!$info instanceof \stdClass || !($info->before ?? $info->after ?? null)) {
				continue;
			}

			unset($list[$name]);
			$names = array_keys($list);
			$best = null;

			foreach ((array) $info->before as $target) {
				if ($target === '*') {
					$best = 0;
				} elseif (isset($list[$target])) {
					$pos = array_search($target, $names, true);
					$best = min($pos, $best ?? $pos);
				}
			}

			foreach ((array) ($info->after ?? null) as $target) {
				if ($target === '*') {
					$best = count($names);
				} elseif (isset($list[$target])) {
					$pos = array_search($target, $names, true);
					$best = max($pos + 1, $best);
				}
			}

			$list = array_slice($list, 0, $best, true)
				+ [$name => $info]
				+ array_slice($list, $best, null, true);
		}

		return $list;
	}
}
