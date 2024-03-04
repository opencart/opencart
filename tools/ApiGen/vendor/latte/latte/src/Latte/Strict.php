<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte;

use LogicException;


/**
 * Better OOP experience.
 */
trait Strict
{
	/**
	 * Call to undefined method.
	 * @param  mixed[]  $args
	 * @throws LogicException
	 */
	public function __call(string $name, array $args): mixed
	{
		$class = method_exists($this, $name) ? 'parent' : static::class;
		$items = (new \ReflectionClass($this))->getMethods(\ReflectionMethod::IS_PUBLIC);
		$items = array_map(fn($item) => $item->getName(), $items);
		$hint = ($t = Helpers::getSuggestion($items, $name))
			? ", did you mean $t()?"
			: '.';
		throw new LogicException("Call to undefined method $class::$name()$hint");
	}


	/**
	 * Call to undefined static method.
	 * @param  mixed[]  $args
	 * @throws LogicException
	 */
	public static function __callStatic(string $name, array $args): mixed
	{
		$rc = new \ReflectionClass(static::class);
		$items = array_filter($rc->getMethods(\ReflectionMethod::IS_STATIC), fn($m) => $m->isPublic());
		$items = array_map(fn($item) => $item->getName(), $items);
		$hint = ($t = Helpers::getSuggestion($items, $name))
			? ", did you mean $t()?"
			: '.';
		throw new LogicException("Call to undefined static method $rc->name::$name()$hint");
	}


	/**
	 * Access to undeclared property.
	 * @throws LogicException
	 */
	public function &__get(string $name): mixed
	{
		$rc = new \ReflectionClass($this);
		$items = array_filter($rc->getProperties(\ReflectionProperty::IS_PUBLIC), fn($p) => !$p->isStatic());
		$items = array_map(fn($item) => $item->getName(), $items);
		$hint = ($t = Helpers::getSuggestion($items, $name))
			? ", did you mean $$t?"
			: '.';
		throw new LogicException("Attempt to read undeclared property $rc->name::$$name$hint");
	}


	/**
	 * Access to undeclared property.
	 * @throws LogicException
	 */
	public function __set(string $name, mixed $value): void
	{
		$rc = new \ReflectionClass($this);
		$items = array_filter($rc->getProperties(\ReflectionProperty::IS_PUBLIC), fn($p) => !$p->isStatic());
		$items = array_map(fn($item) => $item->getName(), $items);
		$hint = ($t = Helpers::getSuggestion($items, $name))
			? ", did you mean $$t?"
			: '.';
		throw new LogicException("Attempt to write to undeclared property $rc->name::$$name$hint");
	}


	public function __isset(string $name): bool
	{
		return false;
	}


	/**
	 * Access to undeclared property.
	 * @throws LogicException
	 */
	public function __unset(string $name): void
	{
		$class = static::class;
		throw new LogicException("Attempt to unset undeclared property $class::$$name.");
	}
}
