<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI;

use Nette;


/**
 * Autowiring.
 */
class Autowiring
{
	use Nette\SmartObject;

	/** @var ContainerBuilder */
	private $builder;

	/** @var array[]  type => services, used by getByType() */
	private $highPriority = [];

	/** @var array[]  type => services, used by findByType() */
	private $lowPriority = [];

	/** @var string[] of classes excluded from autowiring */
	private $excludedClasses = [];


	public function __construct(ContainerBuilder $builder)
	{
		$this->builder = $builder;
	}


	/**
	 * Resolves service name by type.
	 * @param  bool  $throw exception if service not found?
	 * @throws MissingServiceException when not found
	 * @throws ServiceCreationException when multiple found
	 */
	public function getByType(string $type, bool $throw = false): ?string
	{
		$type = Helpers::normalizeClass($type);
		$types = $this->highPriority;
		if (empty($types[$type])) {
			if ($throw) {
				if (!class_exists($type) && !interface_exists($type)) {
					throw new MissingServiceException(sprintf("Service of type '%s' not found. Check the class name because it cannot be found.", $type));
				}

				throw new MissingServiceException(sprintf('Service of type %s not found. Did you add it to configuration file?', $type));
			}

			return null;

		} elseif (count($types[$type]) === 1) {
			return $types[$type][0];

		} else {
			$list = $types[$type];
			natsort($list);
			$hint = count($list) === 2 && ($tmp = strpos($list[0], '.') xor strpos($list[1], '.'))
				? '. If you want to overwrite service ' . $list[$tmp ? 0 : 1] . ', give it proper name.'
				: '';
			throw new ServiceCreationException(sprintf(
				"Multiple services of type $type found: %s%s",
				implode(', ', $list),
				$hint
			));
		}
	}


	/**
	 * Gets the service names and definitions of the specified type.
	 * @return Definitions\Definition[]  service name is key
	 */
	public function findByType(string $type): array
	{
		$type = Helpers::normalizeClass($type);
		$definitions = $this->builder->getDefinitions();
		$names = array_merge($this->highPriority[$type] ?? [], $this->lowPriority[$type] ?? []);
		$res = [];
		foreach ($names as $name) {
			$res[$name] = $definitions[$name];
		}

		return $res;
	}


	/**
	 * @param  string[]  $types
	 */
	public function addExcludedClasses(array $types): void
	{
		foreach ($types as $type) {
			if (class_exists($type) || interface_exists($type)) {
				$type = Helpers::normalizeClass($type);
				$this->excludedClasses += class_parents($type) + class_implements($type) + [$type => $type];
			}
		}
	}


	public function getClassList(): array
	{
		return [$this->lowPriority, $this->highPriority];
	}


	public function rebuild(): void
	{
		$this->lowPriority = $this->highPriority = $preferred = [];

		foreach ($this->builder->getDefinitions() as $name => $def) {
			if (!($type = $def->getType())) {
				continue;
			}

			$autowired = $def->getAutowired();
			if (is_array($autowired)) {
				foreach ($autowired as $k => $autowiredType) {
					if ($autowiredType === ContainerBuilder::ThisService) {
						$autowired[$k] = $type;
					} elseif (!is_a($type, $autowiredType, true)) {
						throw new ServiceCreationException(sprintf(
							"Incompatible class %s in autowiring definition of service '%s'.",
							$autowiredType,
							$name
						));
					}
				}
			}

			foreach (class_parents($type) + class_implements($type) + [$type] as $parent) {
				if (!$autowired || isset($this->excludedClasses[$parent])) {
					continue;
				} elseif (is_array($autowired)) {
					$priority = false;
					foreach ($autowired as $autowiredType) {
						if (is_a($parent, $autowiredType, true)) {
							if (empty($preferred[$parent]) && isset($this->highPriority[$parent])) {
								$this->lowPriority[$parent] = array_merge($this->lowPriority[$parent] ?? [], $this->highPriority[$parent]);
								$this->highPriority[$parent] = [];
							}

							$preferred[$parent] = $priority = true;
							break;
						}
					}
				} else {
					$priority = empty($preferred[$parent]);
				}

				$list = $priority ? 'highPriority' : 'lowPriority';
				$this->$list[$parent][] = $name;
			}
		}
	}
}
