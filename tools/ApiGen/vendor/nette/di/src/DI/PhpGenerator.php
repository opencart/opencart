<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI;

use Nette;
use Nette\DI\Definitions\Reference;
use Nette\DI\Definitions\Statement;
use Nette\PhpGenerator as Php;
use Nette\Utils\Strings;


/**
 * Container PHP code generator.
 */
class PhpGenerator
{
	use Nette\SmartObject;

	/** @var ContainerBuilder */
	private $builder;

	/** @var string */
	private $className;


	public function __construct(ContainerBuilder $builder)
	{
		$this->builder = $builder;
	}


	/**
	 * Generates PHP classes. First class is the container.
	 */
	public function generate(string $className): Php\ClassType
	{
		$this->className = $className;
		$class = new Php\ClassType($this->className);
		$class->setExtends(Container::class);
		$class->addMethod('__construct')
			->addBody('parent::__construct($params);')
			->addParameter('params', [])
				->setType('array');

		foreach ($this->builder->exportMeta() as $key => $value) {
			$class->addProperty($key)
				->setProtected()
				->setValue($value);
		}

		$definitions = $this->builder->getDefinitions();
		ksort($definitions);

		foreach ($definitions as $def) {
			$class->addMember($this->generateMethod($def));
		}

		$class->getMethod(Container::getMethodName(ContainerBuilder::ThisContainer))
			->setReturnType($className)
			->setBody('return $this;');

		$class->addMethod('initialize');

		return $class;
	}


	public function toString(Php\ClassType $class): string
	{
		return '/** @noinspection PhpParamsInspection,PhpMethodMayBeStaticInspection */

declare(strict_types=1);

' . $class->__toString();
	}


	public function addInitialization(Php\ClassType $class, CompilerExtension $extension): void
	{
		$closure = $extension->getInitialization();
		if ($closure->getBody()) {
			$class->getMethod('initialize')
				->addBody('// ' . $extension->prefix(''))
				->addBody("($closure)();");
		}
	}


	public function generateMethod(Definitions\Definition $def): Php\Method
	{
		$name = $def->getName();
		try {
			$method = new Php\Method(Container::getMethodName($name));
			$method->setPublic();
			$method->setReturnType($def->getType());
			$def->generateMethod($method, $this);
			return $method;

		} catch (\Throwable $e) {
			throw new ServiceCreationException("Service '$name': " . $e->getMessage(), 0, $e);
		}
	}


	/**
	 * Formats PHP code for class instantiating, function calling or property setting in PHP.
	 */
	public function formatStatement(Statement $statement): string
	{
		$entity = $statement->getEntity();
		$arguments = $statement->arguments;

		switch (true) {
			case is_string($entity) && Strings::contains($entity, '?'): // PHP literal
				return $this->formatPhp($entity, $arguments);

			case is_string($entity): // create class
				return $arguments
					? $this->formatPhp("new $entity(...?:)", [$arguments])
					: $this->formatPhp("new $entity", []);

			case is_array($entity):
				switch (true) {
					case $entity[1][0] === '$': // property getter, setter or appender
						$name = substr($entity[1], 1);
						if ($append = (substr($name, -2) === '[]')) {
							$name = substr($name, 0, -2);
						}

						$prop = $entity[0] instanceof Reference
							? $this->formatPhp('?->?', [$entity[0], $name])
							: $this->formatPhp('?::$?', [$entity[0], $name]);
						return $arguments
							? $this->formatPhp(($append ? '?[]' : '?') . ' = ?', [new Php\Literal($prop), $arguments[0]])
							: $prop;

					case $entity[0] instanceof Statement:
						$inner = $this->formatPhp('?', [$entity[0]]);
						if (substr($inner, 0, 4) === 'new ') {
							$inner = "($inner)";
						}

						return $this->formatPhp('?->?(...?:)', [new Php\Literal($inner), $entity[1], $arguments]);

					case $entity[0] instanceof Reference:
						return $this->formatPhp('?->?(...?:)', [$entity[0], $entity[1], $arguments]);

					case $entity[0] === '': // function call
						return $this->formatPhp('?(...?:)', [new Php\Literal($entity[1]), $arguments]);

					case is_string($entity[0]): // static method call
						return $this->formatPhp('?::?(...?:)', [new Php\Literal($entity[0]), $entity[1], $arguments]);
				}
		}

		throw new Nette\InvalidStateException;
	}


	/**
	 * Formats PHP statement.
	 * @internal
	 */
	public function formatPhp(string $statement, array $args): string
	{
		array_walk_recursive($args, function (&$val): void {
			if ($val instanceof Statement) {
				$val = new Php\Literal($this->formatStatement($val));

			} elseif ($val instanceof Reference) {
				$name = $val->getValue();
				if ($val->isSelf()) {
					$val = new Php\Literal('$service');
				} elseif ($name === ContainerBuilder::ThisContainer) {
					$val = new Php\Literal('$this');
				} else {
					$val = ContainerBuilder::literal('$this->getService(?)', [$name]);
				}
			}
		});
		return (new Php\Dumper)->format($statement, ...$args);
	}


	/**
	 * Converts parameters from Definition to PhpGenerator.
	 * @return Php\Parameter[]
	 */
	public function convertParameters(array $parameters): array
	{
		$res = [];
		foreach ($parameters as $k => $v) {
			$tmp = explode(' ', is_int($k) ? $v : $k);
			$param = $res[] = new Php\Parameter(end($tmp));
			if (!is_int($k)) {
				$param->setDefaultValue($v);
			}

			if (isset($tmp[1])) {
				$param->setType($tmp[0]);
			}
		}

		return $res;
	}


	public function getClassName(): ?string
	{
		return $this->className;
	}
}
