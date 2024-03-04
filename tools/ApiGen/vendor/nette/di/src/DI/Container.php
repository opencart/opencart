<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI;

use Nette;


/**
 * The dependency injection container default implementation.
 */
class Container
{
	use Nette\SmartObject;

	/** @var array  user parameters */
	public $parameters = [];

	/** @var string[]  services name => type (complete list of available services) */
	protected $types = [];

	/** @var string[]  alias => service name */
	protected $aliases = [];

	/** @var array[]  tag name => service name => tag value */
	protected $tags = [];

	/** @var array[]  type => level => services */
	protected $wiring = [];

	/** @var object[]  service name => instance */
	private $instances = [];

	/** @var array circular reference detector */
	private $creating;

	/** @var array */
	private $methods;


	public function __construct(array $params = [])
	{
		$this->parameters = $params;
		$this->methods = array_flip(array_filter(
			get_class_methods($this),
			function ($s) { return preg_match('#^createService.#', $s); }
		));
	}


	public function getParameters(): array
	{
		return $this->parameters;
	}


	/**
	 * Adds the service to the container.
	 * @param  object  $service  service or its factory
	 * @return static
	 */
	public function addService(string $name, object $service)
	{
		$name = $this->aliases[$name] ?? $name;
		if (isset($this->instances[$name])) {
			throw new Nette\InvalidStateException(sprintf("Service '%s' already exists.", $name));
		}

		if ($service instanceof \Closure) {
			$rt = Nette\Utils\Type::fromReflection(new \ReflectionFunction($service));
			$type = $rt ? Helpers::ensureClassType($rt, 'return type of closure') : '';
		} else {
			$type = get_class($service);
		}

		if (!isset($this->methods[self::getMethodName($name)])) {
			$this->types[$name] = $type;

		} elseif (($expectedType = $this->getServiceType($name)) && !is_a($type, $expectedType, true)) {
			throw new Nette\InvalidArgumentException(sprintf(
				"Service '%s' must be instance of %s, %s.",
				$name,
				$expectedType,
				$type ? "$type given" : 'add typehint to closure'
			));
		}

		if ($service instanceof \Closure) {
			$this->methods[self::getMethodName($name)] = $service;
			$this->types[$name] = $type;
		} else {
			$this->instances[$name] = $service;
		}

		return $this;
	}


	/**
	 * Removes the service from the container.
	 */
	public function removeService(string $name): void
	{
		$name = $this->aliases[$name] ?? $name;
		unset($this->instances[$name]);
	}


	/**
	 * Gets the service object by name.
	 * @throws MissingServiceException
	 */
	public function getService(string $name): object
	{
		if (!isset($this->instances[$name])) {
			if (isset($this->aliases[$name])) {
				return $this->getService($this->aliases[$name]);
			}

			$this->instances[$name] = $this->createService($name);
		}

		return $this->instances[$name];
	}


	/**
	 * Gets the service object by name.
	 * @throws MissingServiceException
	 */
	public function getByName(string $name): object
	{
		return $this->getService($name);
	}


	/**
	 * Gets the service type by name.
	 * @throws MissingServiceException
	 */
	public function getServiceType(string $name): string
	{
		$method = self::getMethodName($name);
		if (isset($this->aliases[$name])) {
			return $this->getServiceType($this->aliases[$name]);

		} elseif (isset($this->types[$name])) {
			return $this->types[$name];

		} elseif (isset($this->methods[$method])) {
			$type = (new \ReflectionMethod($this, $method))->getReturnType();
			return $type ? $type->getName() : '';

		} else {
			throw new MissingServiceException(sprintf("Service '%s' not found.", $name));
		}
	}


	/**
	 * Does the service exist?
	 */
	public function hasService(string $name): bool
	{
		$name = $this->aliases[$name] ?? $name;
		return isset($this->methods[self::getMethodName($name)]) || isset($this->instances[$name]);
	}


	/**
	 * Is the service created?
	 */
	public function isCreated(string $name): bool
	{
		if (!$this->hasService($name)) {
			throw new MissingServiceException(sprintf("Service '%s' not found.", $name));
		}

		$name = $this->aliases[$name] ?? $name;
		return isset($this->instances[$name]);
	}


	/**
	 * Creates new instance of the service.
	 * @throws MissingServiceException
	 */
	public function createService(string $name, array $args = []): object
	{
		$name = $this->aliases[$name] ?? $name;
		$method = self::getMethodName($name);
		$cb = $this->methods[$method] ?? null;
		if (isset($this->creating[$name])) {
			throw new Nette\InvalidStateException(sprintf('Circular reference detected for services: %s.', implode(', ', array_keys($this->creating))));

		} elseif ($cb === null) {
			throw new MissingServiceException(sprintf("Service '%s' not found.", $name));
		}

		try {
			$this->creating[$name] = true;
			$service = $cb instanceof \Closure
				? $cb(...$args)
				: $this->$method(...$args);

		} finally {
			unset($this->creating[$name]);
		}

		if (!is_object($service)) {
			throw new Nette\UnexpectedValueException(sprintf(
				"Unable to create service '$name', value returned by %s is not object.",
				$cb instanceof \Closure ? 'closure' : "method $method()"
			));
		}

		return $service;
	}


	/**
	 * Resolves service by type.
	 * @template T of object
	 * @param  class-string<T>  $type
	 * @return ?T
	 * @throws MissingServiceException
	 */
	public function getByType(string $type, bool $throw = true): ?object
	{
		$type = Helpers::normalizeClass($type);
		if (!empty($this->wiring[$type][0])) {
			if (count($names = $this->wiring[$type][0]) === 1) {
				return $this->getService($names[0]);
			}

			natsort($names);
			throw new MissingServiceException(sprintf("Multiple services of type $type found: %s.", implode(', ', $names)));

		} elseif ($throw) {
			if (!class_exists($type) && !interface_exists($type)) {
				throw new MissingServiceException(sprintf("Service of type '%s' not found. Check the class name because it cannot be found.", $type));
			}

			foreach ($this->methods as $method => $foo) {
				$methodType = (new \ReflectionMethod(static::class, $method))->getReturnType()->getName();
				if (is_a($methodType, $type, true)) {
					throw new MissingServiceException(sprintf(
						"Service of type %s is not autowired or is missing in di\u{a0}›\u{a0}export\u{a0}›\u{a0}types.",
						$type
					));
				}
			}

			throw new MissingServiceException(sprintf(
				'Service of type %s not found. Did you add it to configuration file?',
				$type
			));
		}

		return null;
	}


	/**
	 * Gets the autowired service names of the specified type.
	 * @return string[]
	 * @internal
	 */
	public function findAutowired(string $type): array
	{
		$type = Helpers::normalizeClass($type);
		return array_merge($this->wiring[$type][0] ?? [], $this->wiring[$type][1] ?? []);
	}


	/**
	 * Gets the service names of the specified type.
	 * @return string[]
	 */
	public function findByType(string $type): array
	{
		$type = Helpers::normalizeClass($type);
		return empty($this->wiring[$type])
			? []
			: array_merge(...array_values($this->wiring[$type]));
	}


	/**
	 * Gets the service names of the specified tag.
	 * @return array of [service name => tag attributes]
	 */
	public function findByTag(string $tag): array
	{
		return $this->tags[$tag] ?? [];
	}


	/********************* autowiring ****************d*g**/


	/**
	 * Creates new instance using autowiring.
	 * @throws Nette\InvalidArgumentException
	 */
	public function createInstance(string $class, array $args = []): object
	{
		$rc = new \ReflectionClass($class);
		if (!$rc->isInstantiable()) {
			throw new ServiceCreationException(sprintf('Class %s is not instantiable.', $class));

		} elseif ($constructor = $rc->getConstructor()) {
			return $rc->newInstanceArgs($this->autowireArguments($constructor, $args));

		} elseif ($args) {
			throw new ServiceCreationException(sprintf('Unable to pass arguments, class %s has no constructor.', $class));
		}

		return new $class;
	}


	/**
	 * Calls all methods starting with with "inject" using autowiring.
	 */
	public function callInjects(object $service): void
	{
		Extensions\InjectExtension::callInjects($this, $service);
	}


	/**
	 * Calls method using autowiring.
	 * @return mixed
	 */
	public function callMethod(callable $function, array $args = [])
	{
		return $function(...$this->autowireArguments(Nette\Utils\Callback::toReflection($function), $args));
	}


	private function autowireArguments(\ReflectionFunctionAbstract $function, array $args = []): array
	{
		return Resolver::autowireArguments($function, $args, function (string $type, bool $single) {
			return $single
				? $this->getByType($type)
				: array_map([$this, 'getService'], $this->findAutowired($type));
		});
	}


	public static function getMethodName(string $name): string
	{
		if ($name === '') {
			throw new Nette\InvalidArgumentException('Service name must be a non-empty string.');
		}

		return 'createService' . str_replace('.', '__', ucfirst($name));
	}
}
