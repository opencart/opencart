<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Sandbox;

use Latte;


/** @internal */
final class RuntimeChecker
{
	use Latte\Strict;

	public function __construct(
		public Latte\Policy $policy,
	) {
	}


	public function call(mixed $callable, array $args): mixed
	{
		self::checkCallable($callable);
		self::args(...$args);
		return $callable(...$args);
	}


	public function callMethod(mixed $object, mixed $method, array $args, bool $nullsafe = false): mixed
	{
		if ($object === null) {
			if ($nullsafe) {
				throw new \Error("Call to a member function $method() on null");
			}
			return null;

		} elseif (!is_object($object) || !is_string($method)) {
			throw new Latte\SecurityViolationException('Invalid callable.');

		} elseif (!$this->policy->isMethodAllowed($class = $object::class, $method)) {
			throw new Latte\SecurityViolationException("Calling $class::$method() is not allowed.");
		}

		self::args(...$args);
		return [$object, $method](...$args);
	}


	public function closure(mixed $callable): \Closure
	{
		self::checkCallable($callable);
		return \Closure::fromCallable($callable);
	}


	public function args(...$args): array
	{
		foreach ($args as $arg) {
			if (
				is_array($arg)
				&& is_callable($arg, true, $text)
				&& !$this->policy->isMethodAllowed(is_object($arg[0]) ? $arg[0]::class : $arg[0], $arg[1])
			) {
				throw new Latte\SecurityViolationException("Calling $text() is not allowed.");
			}
		}

		return $args;
	}


	public function prop(mixed $object, mixed $property): mixed
	{
		$class = is_object($object) ? $object::class : $object;
		if (is_string($class) && !$this->policy->isPropertyAllowed($class, (string) $property)) {
			throw new Latte\SecurityViolationException("Access to '$property' property on a $class object is not allowed.");
		}

		return $object;
	}


	private function checkCallable(mixed $callable): void
	{
		if (!is_callable($callable)) {
			throw new Latte\SecurityViolationException('Invalid callable.');

		} elseif (is_string($callable)) {
			$parts = explode('::', $callable);
			$allowed = count($parts) === 1
				? $this->policy->isFunctionAllowed($parts[0])
				: $this->policy->isMethodAllowed(...$parts);

		} elseif (is_array($callable)) {
			$allowed = $this->policy->isMethodAllowed(is_object($callable[0]) ? $callable[0]::class : $callable[0], $callable[1]);

		} elseif (is_object($callable)) {
			$allowed = $callable instanceof \Closure
				? true
				: $this->policy->isMethodAllowed($callable::class, '__invoke');

		} else {
			$allowed = false;
		}

		if (!$allowed) {
			is_callable($callable, false, $text);
			throw new Latte\SecurityViolationException("Calling $text() is not allowed.");
		}
	}
}
