<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI\Extensions;

use Nette;
use Nette\DI;
use Nette\DI\Definitions;
use Nette\Utils\Reflection;


/**
 * Calls inject methods and fills @inject properties.
 */
final class InjectExtension extends DI\CompilerExtension
{
	public const TagInject = 'nette.inject';

	/** @deprecated use InjectExtension::TagInject */
	public const TAG_INJECT = self::TagInject;


	public function getConfigSchema(): Nette\Schema\Schema
	{
		return Nette\Schema\Expect::structure([]);
	}


	public function beforeCompile()
	{
		foreach ($this->getContainerBuilder()->getDefinitions() as $def) {
			if ($def->getTag(self::TagInject)) {
				$def = $def instanceof Definitions\FactoryDefinition
					? $def->getResultDefinition()
					: $def;
				if ($def instanceof Definitions\ServiceDefinition) {
					$this->updateDefinition($def);
				}
			}
		}
	}


	private function updateDefinition(Definitions\ServiceDefinition $def): void
	{
		$resolvedType = (new DI\Resolver($this->getContainerBuilder()))->resolveEntityType($def->getCreator());
		$class = is_subclass_of($resolvedType, $def->getType())
			? $resolvedType
			: $def->getType();
		$setups = $def->getSetup();

		foreach (self::getInjectProperties($class) as $property => $type) {
			$builder = $this->getContainerBuilder();
			$inject = new Definitions\Statement('$' . $property, [Definitions\Reference::fromType((string) $type)]);
			foreach ($setups as $key => $setup) {
				if ($setup->getEntity() === $inject->getEntity()) {
					$inject = $setup;
					$builder = null;
					unset($setups[$key]);
				}
			}

			array_unshift($setups, $inject);
		}

		foreach (array_reverse(self::getInjectMethods($class)) as $method) {
			$inject = new Definitions\Statement($method);
			foreach ($setups as $key => $setup) {
				if ($setup->getEntity() === $inject->getEntity()) {
					$inject = $setup;
					unset($setups[$key]);
				}
			}

			array_unshift($setups, $inject);
		}

		$def->setSetup($setups);
	}


	/**
	 * Generates list of inject methods.
	 * @internal
	 */
	public static function getInjectMethods(string $class): array
	{
		$classes = [];
		foreach (get_class_methods($class) as $name) {
			if (substr($name, 0, 6) === 'inject') {
				$classes[$name] = (new \ReflectionMethod($class, $name))->getDeclaringClass()->name;
			}
		}

		$methods = array_keys($classes);
		uksort($classes, function (string $a, string $b) use ($classes, $methods): int {
			return $classes[$a] === $classes[$b]
				? array_search($a, $methods, true) <=> array_search($b, $methods, true)
				: (is_a($classes[$a], $classes[$b], true) ? 1 : -1);
		});
		return array_keys($classes);
	}


	/**
	 * Generates list of properties with annotation @inject.
	 * @internal
	 */
	public static function getInjectProperties(string $class): array
	{
		$res = [];
		foreach ((new \ReflectionClass($class))->getProperties() as $rp) {
			$name = $rp->getName();
			$hasAttr = PHP_VERSION_ID >= 80000 && $rp->getAttributes(DI\Attributes\Inject::class);
			if ($hasAttr || DI\Helpers::parseAnnotation($rp, 'inject') !== null) {
				if (!$rp->isPublic() || $rp->isStatic()) {
					trigger_error(sprintf('Property %s for injection must be public and non-static.', Reflection::toString($rp)), E_USER_WARNING);
					continue;
				}

				if (PHP_VERSION_ID >= 80100 && $rp->isReadOnly()) {
					throw new Nette\InvalidStateException(sprintf('Property %s for injection must not be readonly.', Reflection::toString($rp)));
				}

				$type = Nette\Utils\Type::fromReflection($rp);
				if (!$type && !$hasAttr && ($annotation = DI\Helpers::parseAnnotation($rp, 'var'))) {
					$annotation = Reflection::expandClassName($annotation, Reflection::getPropertyDeclaringClass($rp));
					$type = Nette\Utils\Type::fromString($annotation);
				}

				$res[$rp->getName()] = DI\Helpers::ensureClassType($type, 'type of property ' . Reflection::toString($rp));
			}
		}

		ksort($res);
		return $res;
	}


	/**
	 * Calls all methods starting with with "inject" using autowiring.
	 * @param  object  $service
	 */
	public static function callInjects(DI\Container $container, $service): void
	{
		if (!is_object($service)) {
			throw new Nette\InvalidArgumentException(sprintf('Service must be object, %s given.', gettype($service)));
		}

		foreach (self::getInjectMethods(get_class($service)) as $method) {
			$container->callMethod([$service, $method]);
		}

		foreach (self::getInjectProperties(get_class($service)) as $property => $type) {
			$service->$property = $container->getByType($type);
		}
	}
}
