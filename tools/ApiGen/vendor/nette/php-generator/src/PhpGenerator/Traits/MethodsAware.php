<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator\Traits;

use Nette;
use Nette\PhpGenerator\Method;


/**
 * @internal
 */
trait MethodsAware
{
	/** @var array<string, Method> */
	private array $methods = [];


	/** @param  Method[]  $methods */
	public function setMethods(array $methods): static
	{
		(function (Method ...$methods) {})(...$methods);
		$this->methods = [];
		foreach ($methods as $m) {
			$this->methods[strtolower($m->getName())] = $m;
		}

		return $this;
	}


	/** @return Method[] */
	public function getMethods(): array
	{
		$res = [];
		foreach ($this->methods as $m) {
			$res[$m->getName()] = $m;
		}

		return $res;
	}


	public function getMethod(string $name): Method
	{
		$m = $this->methods[strtolower($name)] ?? null;
		if (!$m) {
			throw new Nette\InvalidArgumentException("Method '$name' not found.");
		}

		return $m;
	}


	public function addMethod(string $name): Method
	{
		$lower = strtolower($name);
		if (isset($this->methods[$lower])) {
			throw new Nette\InvalidStateException("Cannot add method '$name', because it already exists.");
		}
		$method = new Method($name);
		if (!$this->isInterface()) {
			$method->setPublic();
		}

		return $this->methods[$lower] = $method;
	}


	public function removeMethod(string $name): static
	{
		unset($this->methods[strtolower($name)]);
		return $this;
	}


	public function hasMethod(string $name): bool
	{
		return isset($this->methods[strtolower($name)]);
	}
}
