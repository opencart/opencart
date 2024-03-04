<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester\Runner;


/**
 * Stupid command line arguments parser.
 */
class CommandLine
{
	public const
		Argument = 'argument',
		Optional = 'optional',
		Repeatable = 'repeatable',
		Enum = 'enum',
		RealPath = 'realpath',
		Normalizer = 'normalizer',
		Value = 'default';

	/** @var array[] */
	private array $options = [];

	/** @var string[] */
	private array $aliases = [];

	/** @var string[] */
	private array $positional = [];
	private string $help;


	public function __construct(string $help, array $defaults = [])
	{
		$this->help = $help;
		$this->options = $defaults;

		preg_match_all('#^[ \t]+(--?\w.*?)(?:  .*\(default: (.*)\)|  |\r|$)#m', $help, $lines, PREG_SET_ORDER);
		foreach ($lines as $line) {
			preg_match_all('#(--?\w[\w-]*)(?:[= ](<.*?>|\[.*?]|\w+)(\.{0,3}))?[ ,|]*#A', $line[1], $m);
			if (!count($m[0]) || count($m[0]) > 2 || implode('', $m[0]) !== $line[1]) {
				throw new \InvalidArgumentException("Unable to parse '$line[1]'.");
			}

			$name = end($m[1]);
			$opts = $this->options[$name] ?? [];
			$this->options[$name] = $opts + [
				self::Argument => (bool) end($m[2]),
				self::Optional => isset($line[2]) || (substr(end($m[2]), 0, 1) === '[') || isset($opts[self::Value]),
				self::Repeatable => (bool) end($m[3]),
				self::Enum => count($enums = explode('|', trim(end($m[2]), '<[]>'))) > 1 ? $enums : null,
				self::Value => $line[2] ?? null,
			];
			if ($name !== $m[1][0]) {
				$this->aliases[$m[1][0]] = $name;
			}
		}

		foreach ($this->options as $name => $foo) {
			if ($name[0] !== '-') {
				$this->positional[] = $name;
			}
		}
	}


	public function parse(?array $args = null): array
	{
		if ($args === null) {
			$args = isset($_SERVER['argv']) ? array_slice($_SERVER['argv'], 1) : [];
		}

		$params = [];
		reset($this->positional);
		$i = 0;
		while ($i < count($args)) {
			$arg = $args[$i++];
			if ($arg[0] !== '-') {
				if (!current($this->positional)) {
					throw new \Exception("Unexpected parameter $arg.");
				}

				$name = current($this->positional);
				$this->checkArg($this->options[$name], $arg);
				if (empty($this->options[$name][self::Repeatable])) {
					$params[$name] = $arg;
					next($this->positional);
				} else {
					$params[$name][] = $arg;
				}

				continue;
			}

			[$name, $arg] = strpos($arg, '=') ? explode('=', $arg, 2) : [$arg, true];

			if (isset($this->aliases[$name])) {
				$name = $this->aliases[$name];

			} elseif (!isset($this->options[$name])) {
				throw new \Exception("Unknown option $name.");
			}

			$opt = $this->options[$name];

			if ($arg !== true && empty($opt[self::Argument])) {
				throw new \Exception("Option $name has not argument.");

			} elseif ($arg === true && !empty($opt[self::Argument])) {
				if (isset($args[$i]) && $args[$i][0] !== '-') {
					$arg = $args[$i++];
				} elseif (empty($opt[self::Optional])) {
					throw new \Exception("Option $name requires argument.");
				}
			}

			$this->checkArg($opt, $arg);

			if (
				!empty($opt[self::Enum])
				&& !in_array(is_array($arg) ? reset($arg) : $arg, $opt[self::Enum], true)
				&& !(
					$opt[self::Optional]
					&& $arg === true
				)
			) {
				throw new \Exception("Value of option $name must be " . implode(', or ', $opt[self::Enum]) . '.');
			}

			if (empty($opt[self::Repeatable])) {
				$params[$name] = $arg;
			} else {
				$params[$name][] = $arg;
			}
		}

		foreach ($this->options as $name => $opt) {
			if (isset($params[$name])) {
				continue;
			} elseif (isset($opt[self::Value])) {
				$params[$name] = $opt[self::Value];
			} elseif ($name[0] !== '-' && empty($opt[self::Optional])) {
				throw new \Exception("Missing required argument <$name>.");
			} else {
				$params[$name] = null;
			}

			if (!empty($opt[self::Repeatable])) {
				$params[$name] = (array) $params[$name];
			}
		}

		return $params;
	}


	public function help(): void
	{
		echo $this->help;
	}


	public function checkArg(array $opt, mixed &$arg): void
	{
		if (!empty($opt[self::Normalizer])) {
			$arg = call_user_func($opt[self::Normalizer], $arg);
		}

		if (!empty($opt[self::RealPath])) {
			$path = realpath($arg);
			if ($path === false) {
				throw new \Exception("File path '$arg' not found.");
			}

			$arg = $path;
		}
	}


	public function isEmpty(): bool
	{
		return !isset($_SERVER['argv']) || count($_SERVER['argv']) < 2;
	}
}
