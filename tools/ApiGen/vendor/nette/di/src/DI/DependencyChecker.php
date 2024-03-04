<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI;

use Nette;
use Nette\Utils\Reflection;
use Nette\Utils\Type;
use ReflectionClass;
use ReflectionMethod;


/**
 * Cache dependencies checker.
 */
class DependencyChecker
{
	use Nette\SmartObject;

	public const Version = 1;

	/** @deprecated use DependencyChecker::Version */
	public const VERSION = self::Version;

	/** @var array of ReflectionClass|\ReflectionFunctionAbstract|string */
	private $dependencies = [];


	/**
	 * Adds dependencies to the list.
	 * @return static
	 */
	public function add(array $deps)
	{
		$this->dependencies = array_merge($this->dependencies, $deps);
		return $this;
	}


	/**
	 * Exports dependencies.
	 */
	public function export(): array
	{
		$files = $phpFiles = $classes = $functions = [];
		foreach ($this->dependencies as $dep) {
			if (is_string($dep)) {
				$files[] = $dep;

			} elseif ($dep instanceof ReflectionClass) {
				if (empty($classes[$name = $dep->name])) {
					$all = [$name] + class_parents($name) + class_implements($name);
					foreach ($all as &$item) {
						$all += class_uses($item);
						$phpFiles[] = (new ReflectionClass($item))->getFileName();
						$classes[$item] = true;
					}
				}
			} elseif ($dep instanceof \ReflectionFunctionAbstract) {
				$phpFiles[] = $dep->getFileName();
				$functions[] = rtrim(Reflection::toString($dep), '()');

			} else {
				throw new Nette\InvalidStateException(sprintf('Unexpected dependency %s', gettype($dep)));
			}
		}

		$classes = array_keys($classes);
		$functions = array_unique($functions, SORT_REGULAR);
		$hash = self::calculateHash($classes, $functions);
		$files = @array_map('filemtime', array_combine($files, $files)); // @ - file may not exist
		$phpFiles = @array_map('filemtime', array_combine($phpFiles, $phpFiles)); // @ - file may not exist
		return [self::Version, $files, $phpFiles, $classes, $functions, $hash];
	}


	/**
	 * Are dependencies expired?
	 */
	public static function isExpired(
		int $version,
		array $files,
		array &$phpFiles,
		array $classes,
		array $functions,
		string $hash
	): bool
	{
		try {
			$currentFiles = @array_map('filemtime', array_combine($tmp = array_keys($files), $tmp)); // @ - files may not exist
			$origPhpFiles = $phpFiles;
			$phpFiles = @array_map('filemtime', array_combine($tmp = array_keys($phpFiles), $tmp)); // @ - files may not exist
			return $version !== self::Version
				|| $files !== $currentFiles
				|| ($phpFiles !== $origPhpFiles && $hash !== self::calculateHash($classes, $functions));
		} catch (\ReflectionException $e) {
			return true;
		}
	}


	private static function calculateHash(array $classes, array $functions): string
	{
		$hash = [];
		foreach ($classes as $name) {
			$class = new ReflectionClass($name);
			$hash[] = [
				$name,
				Reflection::getUseStatements($class),
				$class->isAbstract(),
				get_parent_class($name),
				class_implements($name),
				class_uses($name),
			];

			foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
				if ($prop->getDeclaringClass() == $class) { // intentionally ==
					$hash[] = [
						$name,
						$prop->name,
						$prop->getDocComment(),
						(string) Type::fromReflection($prop),
						PHP_VERSION_ID >= 80000 ? count($prop->getAttributes(Attributes\Inject::class)) : null,
					];
				}
			}

			foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
				if ($method->getDeclaringClass() == $class) { // intentionally ==
					$hash[] = [
						$name,
						$method->name,
						$method->getDocComment(),
						self::hashParameters($method),
						(string) Type::fromReflection($method),
					];
				}
			}
		}

		$flip = array_flip($classes);
		foreach ($functions as $name) {
			if (strpos($name, '::')) {
				$method = new ReflectionMethod($name);
				$class = $method->getDeclaringClass();
				if (isset($flip[$class->name])) {
					continue;
				}

				$uses = Reflection::getUseStatements($class);
			} else {
				$method = new \ReflectionFunction($name);
				$uses = null;
			}

			$hash[] = [
				$name,
				$uses,
				$method->getDocComment(),
				self::hashParameters($method),
				(string) Type::fromReflection($method),
			];
		}

		return md5(serialize($hash));
	}


	private static function hashParameters(\ReflectionFunctionAbstract $method): array
	{
		$res = [];
		foreach ($method->getParameters() as $param) {
			$res[] = [
				$param->name,
				(string) Type::fromReflection($param),
				$param->isVariadic(),
				$param->isDefaultValueAvailable()
					? is_object($tmp = Reflection::getParameterDefaultValue($param)) ? ['object' => get_class($tmp)] : ['value' => $tmp]
					: null,
			];
		}

		return $res;
	}
}
