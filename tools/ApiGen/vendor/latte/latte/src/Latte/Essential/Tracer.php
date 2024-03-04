<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte;
use Latte\Engine;
use Latte\Runtime\Template;


/**
 * @internal
 */
final class Tracer
{
	public static function throw(): void
	{
		$e = new Latte\RuntimeException('Your location in Latte templates');
		$trace = debug_backtrace();
		$props = [
			'file' => $trace[1]['object']->getName(),
			'line' => self::getSourceLine($trace[0]['file'], $trace[0]['line']),
			'trace' => self::generateTrace($trace),
		];
		foreach ($props as $name => $value) {
			$ref = new \ReflectionProperty('Exception', $name);
			$ref->setAccessible(true);
			$ref->setValue($e, $value);
		}

		throw $e;
	}


	private static function generateTrace(array $trace): array
	{
		$res = [];
		foreach ($trace as $i => $item) {
			$object = $item['object'] ?? null;
			if ($object instanceof Template) {
				$method = $item['function'] ?? '';

				if (str_starts_with($method, 'block')) {
					// begin of block
					$comment = (new \ReflectionMethod($object, $method))->getDocComment();
					$res[] = [
						'function' => preg_match('~(\{.+\})~', $comment, $m) ? $m[1] : '?',
						'file' => $object->getName(),
						'line' => preg_match('~ on line (\d+)~', $comment, $m) ? (int) $m[1] : 0,
						'args' => [], // $L_args is not true, will be added in next step
					];

				} elseif ($method === 'render' && $object->getReferenceType()) {
					// begin of included/extended/... file
					$res[] = [
						'function' => '{' . $object->getReferenceType() . ' ' . basename($object->getName()) . '}',
						'file' => $object->getReferringTemplate()->getName(),
						'line' => 0, // will be added in next step
						'args' => self::filterParams($object->getParameters()),
					];

				} elseif ($method === 'renderToContentType') {
					// {include file}, extends, embed, sandbox, ...
					$res[count($res) - 1]['line'] = self::getSourceLine($item['file'], $item['line']);

				} elseif ($method === 'renderBlock' || $method === 'renderBlockParent') {
					// {include block}
					$res[count($res) - 1]['args'] = self::filterParams($item['args'][1] + $object->getParameters());

					if ($method !== 'renderBlock' || isset($item['args'][2])) { // is not {block}
						$res[] = [
							'function' => '{include ' . ($method === 'renderBlockParent' ? 'parent' : $item['args'][0]) . '}',
							'file' => $object->getName(),
							'line' => self::getSourceLine($item['file'], $item['line']),
							'args' => self::filterParams($item['args'][1]),
						];
					}
				}
			} elseif ($object instanceof Engine) {
				break;
			}
		}

		return $res;
	}


	private static function getSourceLine(string $compiledFile, int $line): int
	{
		if (!is_file($compiledFile)) {
			return 0;
		}

		$line = file($compiledFile)[$line - 1];
		return preg_match('~/\* line (\d+) \*/~', $line, $m)
			? (int) $m[1]
			: 0;
	}


	private static function filterParams(array $params): array
	{
		foreach ($params as $key => $foo) {
			if (is_string($key) && str_starts_with($key, 'ʟ_')) {
				unset($params[$key]);
			}
		}

		return $params;
	}
}
