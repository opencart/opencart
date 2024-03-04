<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI;

use Nette;
use Nette\DI\Definitions\Definition;
use Nette\DI\Definitions\Reference;
use Nette\DI\Definitions\Statement;
use Nette\PhpGenerator\Helpers as PhpHelpers;
use Nette\Utils\Arrays;
use Nette\Utils\Callback;
use Nette\Utils\Reflection;
use Nette\Utils\Strings;
use Nette\Utils\Validators;
use ReflectionClass;


/**
 * Services resolver
 * @internal
 */
class Resolver
{
	use Nette\SmartObject;

	/** @var ContainerBuilder */
	private $builder;

	/** @var Definition|null */
	private $currentService;

	/** @var string|null */
	private $currentServiceType;

	/** @var bool */
	private $currentServiceAllowed = false;

	/** @var \SplObjectStorage  circular reference detector */
	private $recursive;


	public function __construct(ContainerBuilder $builder)
	{
		$this->builder = $builder;
		$this->recursive = new \SplObjectStorage;
	}


	public function getContainerBuilder(): ContainerBuilder
	{
		return $this->builder;
	}


	public function resolveDefinition(Definition $def): void
	{
		if ($this->recursive->contains($def)) {
			$names = array_map(function ($item) { return $item->getName(); }, iterator_to_array($this->recursive));
			throw new ServiceCreationException(sprintf('Circular reference detected for services: %s.', implode(', ', $names)));
		}

		try {
			$this->recursive->attach($def);

			$def->resolveType($this);

			if (!$def->getType()) {
				throw new ServiceCreationException('Type of service is unknown.');
			}
		} catch (\Throwable $e) {
			throw $this->completeException($e, $def);

		} finally {
			$this->recursive->detach($def);
		}
	}


	public function resolveReferenceType(Reference $ref): ?string
	{
		if ($ref->isSelf()) {
			return $this->currentServiceType;
		} elseif ($ref->isType()) {
			return ltrim($ref->getValue(), '\\');
		}

		$def = $this->resolveReference($ref);
		if (!$def->getType()) {
			$this->resolveDefinition($def);
		}

		return $def->getType();
	}


	public function resolveEntityType(Statement $statement): ?string
	{
		$entity = $this->normalizeEntity($statement);

		if (is_array($entity)) {
			if ($entity[0] instanceof Reference || $entity[0] instanceof Statement) {
				$entity[0] = $this->resolveEntityType($entity[0] instanceof Statement ? $entity[0] : new Statement($entity[0]));
				if (!$entity[0]) {
					return null;
				}
			}

			try {
				$reflection = Callback::toReflection($entity[0] === '' ? $entity[1] : $entity);
				assert($reflection instanceof \ReflectionMethod || $reflection instanceof \ReflectionFunction);
				$refClass = $reflection instanceof \ReflectionMethod
					? $reflection->getDeclaringClass()
					: null;
			} catch (\ReflectionException $e) {
				$refClass = $reflection = null;
			}

			if (isset($e) || ($refClass && (!$reflection->isPublic()
				|| ($refClass->isTrait() && !$reflection->isStatic())
			))) {
				throw new ServiceCreationException(sprintf('Method %s() is not callable.', Callback::toString($entity)), 0, $e ?? null);
			}

			$this->addDependency($reflection);

			$type = Nette\Utils\Type::fromReflection($reflection) ?? ($annotation = Helpers::getReturnTypeAnnotation($reflection));
			if ($type && !in_array($type->getSingleName(), ['object', 'mixed'], true)) {
				if (isset($annotation)) {
					trigger_error('Annotation @return should be replaced with native return type at ' . Callback::toString($entity), E_USER_DEPRECATED);
				}

				return Helpers::ensureClassType($type, sprintf('return type of %s()', Callback::toString($entity)));
			}

			return null;

		} elseif ($entity instanceof Reference) { // alias or factory
			return $this->resolveReferenceType($entity);

		} elseif (is_string($entity)) { // class
			if (!class_exists($entity)) {
				throw new ServiceCreationException(sprintf(
					interface_exists($entity)
						? "Interface %s can not be used as 'create' or 'factory', did you mean 'implement'?"
						: "Class '%s' not found.",
					$entity
				));
			}

			return $entity;
		}

		return null;
	}


	public function completeDefinition(Definition $def): void
	{
		$this->currentService = in_array($def, $this->builder->getDefinitions(), true)
			? $def
			: null;
		$this->currentServiceType = $def->getType();
		$this->currentServiceAllowed = false;

		try {
			$def->complete($this);

			$this->addDependency(new \ReflectionClass($def->getType()));

		} catch (\Throwable $e) {
			throw $this->completeException($e, $def);

		} finally {
			$this->currentService = $this->currentServiceType = null;
		}
	}


	public function completeStatement(Statement $statement, bool $currentServiceAllowed = false): Statement
	{
		$this->currentServiceAllowed = $currentServiceAllowed;
		$entity = $this->normalizeEntity($statement);
		$arguments = $this->convertReferences($statement->arguments);
		$getter = function (string $type, bool $single) {
			return $single
				? $this->getByType($type)
				: array_values(array_filter($this->builder->findAutowired($type), function ($obj) { return $obj !== $this->currentService; }));
		};

		switch (true) {
			case is_string($entity) && Strings::contains($entity, '?'): // PHP literal
				break;

			case $entity === 'not':
				if (count($arguments) !== 1) {
					throw new ServiceCreationException(sprintf('Function %s() expects 1 parameter, %s given.', $entity, count($arguments)));
				}

				$entity = ['', '!'];
				break;

			case $entity === 'bool':
			case $entity === 'int':
			case $entity === 'float':
			case $entity === 'string':
				if (count($arguments) !== 1) {
					throw new ServiceCreationException(sprintf('Function %s() expects 1 parameter, %s given.', $entity, count($arguments)));
				}

				$arguments = [$arguments[0], $entity];
				$entity = [Helpers::class, 'convertType'];
				break;

			case is_string($entity): // create class
				if (!class_exists($entity)) {
					throw new ServiceCreationException(sprintf("Class '%s' not found.", $entity));
				} elseif ((new ReflectionClass($entity))->isAbstract()) {
					throw new ServiceCreationException(sprintf('Class %s is abstract.', $entity));
				} elseif (($rm = (new ReflectionClass($entity))->getConstructor()) !== null && !$rm->isPublic()) {
					throw new ServiceCreationException(sprintf('Class %s has %s constructor.', $entity, $rm->isProtected() ? 'protected' : 'private'));
				} elseif ($constructor = (new ReflectionClass($entity))->getConstructor()) {
					$arguments = self::autowireArguments($constructor, $arguments, $getter);
					$this->addDependency($constructor);
				} elseif ($arguments) {
					throw new ServiceCreationException(sprintf(
						'Unable to pass arguments, class %s has no constructor.',
						$entity
					));
				}

				break;

			case $entity instanceof Reference:
				$entity = [new Reference(ContainerBuilder::ThisContainer), Container::getMethodName($entity->getValue())];
				break;

			case is_array($entity):
				if (!preg_match('#^\$?(\\\\?' . PhpHelpers::PHP_IDENT . ')+(\[\])?$#D', $entity[1])) {
					throw new ServiceCreationException(sprintf(
						"Expected function, method or property name, '%s' given.",
						$entity[1]
					));
				}

				switch (true) {
					case $entity[0] === '': // function call
						if (!Arrays::isList($arguments)) {
							throw new ServiceCreationException(sprintf(
								'Unable to pass specified arguments to %s.',
								$entity[0]
							));
						} elseif (!function_exists($entity[1])) {
							throw new ServiceCreationException(sprintf("Function %s doesn't exist.", $entity[1]));
						}

						$rf = new \ReflectionFunction($entity[1]);
						$arguments = self::autowireArguments($rf, $arguments, $getter);
						$this->addDependency($rf);
						break;

					case $entity[0] instanceof Statement:
						$entity[0] = $this->completeStatement($entity[0], $this->currentServiceAllowed);
						// break omitted

					case is_string($entity[0]): // static method call
					case $entity[0] instanceof Reference:
						if ($entity[1][0] === '$') { // property getter, setter or appender
							Validators::assert($arguments, 'list:0..1', "setup arguments for '" . Callback::toString($entity) . "'");
							if (!$arguments && substr($entity[1], -2) === '[]') {
								throw new ServiceCreationException(sprintf('Missing argument for %s.', $entity[1]));
							}
						} elseif (
							$type = $entity[0] instanceof Reference
								? $this->resolveReferenceType($entity[0])
								: $this->resolveEntityType($entity[0] instanceof Statement ? $entity[0] : new Statement($entity[0]))
						) {
							$rc = new ReflectionClass($type);
							if ($rc->hasMethod($entity[1])) {
								$rm = $rc->getMethod($entity[1]);
								if (!$rm->isPublic()) {
									throw new ServiceCreationException(sprintf('%s::%s() is not callable.', $type, $entity[1]));
								}

								$arguments = self::autowireArguments($rm, $arguments, $getter);
								$this->addDependency($rm);

							} elseif (!Arrays::isList($arguments)) {
								throw new ServiceCreationException(sprintf('Unable to pass specified arguments to %s::%s().', $type, $entity[1]));
							}
						}
				}
		}

		try {
			$arguments = $this->completeArguments($arguments);
		} catch (ServiceCreationException $e) {
			if (!strpos($e->getMessage(), ' (used in')) {
				$e->setMessage($e->getMessage() . " (used in {$this->entityToString($entity)})");
			}

			throw $e;
		}

		return new Statement($entity, $arguments);
	}


	public function completeArguments(array $arguments): array
	{
		array_walk_recursive($arguments, function (&$val): void {
			if ($val instanceof Statement) {
				$entity = $val->getEntity();
				if ($entity === 'typed' || $entity === 'tagged') {
					$services = [];
					$current = $this->currentService
						? $this->currentService->getName()
						: null;
					foreach ($val->arguments as $argument) {
						foreach ($entity === 'tagged' ? $this->builder->findByTag($argument) : $this->builder->findAutowired($argument) as $name => $foo) {
							if ($name !== $current) {
								$services[] = new Reference($name);
							}
						}
					}

					$val = $this->completeArguments($services);
				} else {
					$val = $this->completeStatement($val, $this->currentServiceAllowed);
				}
			} elseif ($val instanceof Definition || $val instanceof Reference) {
				$val = $this->normalizeEntity(new Statement($val));
			}
		});
		return $arguments;
	}


	/** @return string|array|Reference  literal, Class, Reference, [Class, member], [, globalFunc], [Reference, member], [Statement, member] */
	private function normalizeEntity(Statement $statement)
	{
		$entity = $statement->getEntity();
		if (is_array($entity)) {
			$item = &$entity[0];
		} else {
			$item = &$entity;
		}

		if ($item instanceof Definition) {
			$name = current(array_keys($this->builder->getDefinitions(), $item, true));
			if ($name === false) {
				throw new ServiceCreationException(sprintf("Service '%s' not found in definitions.", $item->getName()));
			}

			$item = new Reference($name);
		}

		if ($item instanceof Reference) {
			$item = $this->normalizeReference($item);
		}

		return $entity;
	}


	/**
	 * Normalizes reference to 'self' or named reference (or leaves it typed if it is not possible during resolving) and checks existence of service.
	 */
	public function normalizeReference(Reference $ref): Reference
	{
		$service = $ref->getValue();
		if ($ref->isSelf()) {
			return $ref;
		} elseif ($ref->isName()) {
			if (!$this->builder->hasDefinition($service)) {
				throw new ServiceCreationException(sprintf("Reference to missing service '%s'.", $service));
			}

			return $this->currentService && $service === $this->currentService->getName()
				? new Reference(Reference::Self)
				: $ref;
		}

		try {
			return $this->getByType($service);
		} catch (NotAllowedDuringResolvingException $e) {
			return new Reference($service);
		}
	}


	public function resolveReference(Reference $ref): Definition
	{
		return $ref->isSelf()
			? $this->currentService
			: $this->builder->getDefinition($ref->getValue());
	}


	/**
	 * Returns named reference to service resolved by type (or 'self' reference for local-autowiring).
	 * @throws ServiceCreationException when multiple found
	 * @throws MissingServiceException when not found
	 */
	public function getByType(string $type): Reference
	{
		if (
			$this->currentService
			&& $this->currentServiceAllowed
			&& is_a($this->currentServiceType, $type, true)
		) {
			return new Reference(Reference::Self);
		}

		$name = $this->builder->getByType($type, true);
		if (
			!$this->currentServiceAllowed
			&& $this->currentService === $this->builder->getDefinition($name)
		) {
			throw new MissingServiceException;
		}

		return new Reference($name);
	}


	/**
	 * Adds item to the list of dependencies.
	 * @param  \ReflectionClass|\ReflectionFunctionAbstract|string  $dep
	 * @return static
	 */
	public function addDependency($dep)
	{
		$this->builder->addDependency($dep);
		return $this;
	}


	private function completeException(\Throwable $e, Definition $def): ServiceCreationException
	{
		if ($e instanceof ServiceCreationException && Strings::startsWith($e->getMessage(), "Service '")) {
			return $e;
		}

		$name = $def->getName();
		$type = $def->getType();
		if ($name && !ctype_digit($name)) {
			$message = "Service '$name'" . ($type ? " (type of $type)" : '') . ': ';
		} elseif ($type) {
			$message = "Service of type $type: ";
		} elseif ($def instanceof Definitions\ServiceDefinition && $def->getEntity()) {
			$message = 'Service (' . $this->entityToString($def->getEntity()) . '): ';
		} else {
			$message = '';
		}

		$message .= $type
			? str_replace("$type::", preg_replace('~.*\\\\~', '', $type) . '::', $e->getMessage())
			: $e->getMessage();

		return $e instanceof ServiceCreationException
			? $e->setMessage($message)
			: new ServiceCreationException($message, 0, $e);
	}


	private function entityToString($entity): string
	{
		$referenceToText = function (Reference $ref): string {
			return $ref->isSelf() && $this->currentService
				? '@' . $this->currentService->getName()
				: '@' . $ref->getValue();
		};
		if (is_string($entity)) {
			return $entity . '::__construct()';
		} elseif ($entity instanceof Reference) {
			$entity = $referenceToText($entity);
		} elseif (is_array($entity)) {
			if (strpos($entity[1], '$') === false) {
				$entity[1] .= '()';
			}

			if ($entity[0] instanceof Reference) {
				$entity[0] = $referenceToText($entity[0]);
			} elseif (!is_string($entity[0])) {
				return $entity[1];
			}

			return implode('::', $entity);
		}

		return (string) $entity;
	}


	private function convertReferences(array $arguments): array
	{
		array_walk_recursive($arguments, function (&$val): void {
			if (is_string($val) && strlen($val) > 1 && $val[0] === '@' && $val[1] !== '@') {
				$pair = explode('::', substr($val, 1), 2);
				if (!isset($pair[1])) { // @service
					$val = new Reference($pair[0]);
				} elseif (preg_match('#^[A-Z][a-zA-Z0-9_]*$#D', $pair[1], $m)) { // @service::CONSTANT
					$val = ContainerBuilder::literal($this->resolveReferenceType(new Reference($pair[0])) . '::' . $pair[1]);
				} else { // @service::property
					$val = new Statement([new Reference($pair[0]), '$' . $pair[1]]);
				}
			} elseif (is_string($val) && substr($val, 0, 2) === '@@') { // escaped text @@
				$val = substr($val, 1);
			}
		});
		return $arguments;
	}


	/**
	 * Add missing arguments using autowiring.
	 * @param  (callable(string $type, bool $single): (object|object[]|null))  $getter
	 * @throws ServiceCreationException
	 */
	public static function autowireArguments(
		\ReflectionFunctionAbstract $method,
		array $arguments,
		callable $getter
	): array
	{
		$optCount = 0;
		$useName = false;
		$num = -1;
		$res = [];

		foreach ($method->getParameters() as $num => $param) {
			$paramName = $param->name;

			if ($param->isVariadic()) {
				if ($useName && Arrays::some($arguments, function ($val, $key) { return is_int($key); })) {
					throw new ServiceCreationException(sprintf(
						'Cannot use positional argument after named or omitted argument in %s.',
						Reflection::toString($param)
					));
				}

				$res = array_merge($res, $arguments);
				$arguments = [];
				$optCount = 0;
				break;

			} elseif (array_key_exists($key = $paramName, $arguments) || array_key_exists($key = $num, $arguments)) {
				$res[$useName ? $paramName : $num] = $arguments[$key];
				unset($arguments[$key], $arguments[$num]); // unset $num to enable overwriting in configuration

			} elseif (($aw = self::autowireArgument($param, $getter)) !== null) {
				$res[$useName ? $paramName : $num] = $aw;

			} elseif (PHP_VERSION_ID >= 80000) {
				if ($param->isOptional()) {
					$useName = true;
				} else {
					$res[$num] = null;
					trigger_error(sprintf(
						'The parameter %s should have a declared value in the configuration.',
						Reflection::toString($param)
					), E_USER_DEPRECATED);
				}

			} else {
				$res[$num] = $param->isDefaultValueAvailable()
					? Reflection::getParameterDefaultValue($param)
					: null;

				if (!$param->isOptional()) {
					trigger_error(sprintf(
						'The parameter %s should have a declared value in the configuration.',
						Reflection::toString($param)
					), E_USER_DEPRECATED);
				}
			}

			if (PHP_VERSION_ID < 80000) {
				$optCount = $param->isOptional() && $res[$num] === ($param->isDefaultValueAvailable() ? Reflection::getParameterDefaultValue($param) : null)
					? $optCount + 1
					: 0;
			}
		}

		// extra parameters
		while (!$useName && !$optCount && array_key_exists(++$num, $arguments)) {
			$res[$num] = $arguments[$num];
			unset($arguments[$num]);
		}

		if ($arguments) {
			throw new ServiceCreationException(sprintf(
				'Unable to pass specified arguments to %s.',
				Reflection::toString($method)
			));
		} elseif ($optCount) {
			$res = array_slice($res, 0, -$optCount);
		}

		return $res;
	}


	/**
	 * Resolves missing argument using autowiring.
	 * @param  (callable(string $type, bool $single): (object|object[]|null))  $getter
	 * @throws ServiceCreationException
	 * @return mixed
	 */
	private static function autowireArgument(\ReflectionParameter $parameter, callable $getter)
	{
		$desc = Reflection::toString($parameter);
		$type = Nette\Utils\Type::fromReflection($parameter);

		if ($type && $type->isClass()) {
			$class = $type->getSingleName();
			try {
				$res = $getter($class, true);
			} catch (MissingServiceException $e) {
				$res = null;
			} catch (ServiceCreationException $e) {
				throw new ServiceCreationException("{$e->getMessage()} (required by $desc)", 0, $e);
			}

			if ($res !== null || $parameter->allowsNull()) {
				return $res;
			} elseif (class_exists($class) || interface_exists($class)) {
				throw new ServiceCreationException(sprintf(
					'Service of type %s required by %s not found. Did you add it to configuration file?',
					$class,
					$desc
				));
			} else {
				throw new ServiceCreationException(sprintf(
					"Class '%s' required by %s not found. Check the parameter type and 'use' statements.",
					$class,
					$desc
				));
			}

		} elseif ($itemType = self::isArrayOf($parameter, $type)) {
			return $getter($itemType, false);

		} elseif (
			($type && $parameter->allowsNull())
			|| $parameter->isOptional()
			|| $parameter->isDefaultValueAvailable()
		) {
			// !optional + defaultAvailable, !optional + !defaultAvailable since 8.1.0 = func($a = null, $b)
			// optional + !defaultAvailable, optional + defaultAvailable since 8.0.0 = i.e. Exception::__construct, mysqli::mysqli, ...
			// optional + !defaultAvailable = variadics
			// in other cases the optional and defaultAvailable are identical
			return null;

		} else {
			throw new ServiceCreationException(sprintf(
				'Parameter %s has %s, so its value must be specified.',
				$desc,
				$type && !$type->isSingle() ? 'complex type and no default value' : 'no class type or default value'
			));
		}
	}


	private static function isArrayOf(\ReflectionParameter $parameter, ?Nette\Utils\Type $type): ?string
	{
		$method = $parameter->getDeclaringFunction();
		return $method instanceof \ReflectionMethod
			&& $type
			&& $type->getSingleName() === 'array'
			&& preg_match(
				'#@param[ \t]+(?|([\w\\\\]+)\[\]|array<int,\s*([\w\\\\]+)>)[ \t]+\$' . $parameter->name . '#',
				(string) $method->getDocComment(),
				$m
			)
			&& ($itemType = Reflection::expandClassName($m[1], $method->getDeclaringClass()))
			&& (class_exists($itemType) || interface_exists($itemType))
				? $itemType
				: null;
	}
}
