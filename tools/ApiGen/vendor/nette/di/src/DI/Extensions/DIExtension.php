<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI\Extensions;

use Nette;
use Tracy;


/**
 * DI extension.
 */
final class DIExtension extends Nette\DI\CompilerExtension
{
	/** @var array */
	public $exportedTags = [];

	/** @var array */
	public $exportedTypes = [];

	/** @var bool */
	private $debugMode;

	/** @var float */
	private $time;


	public function __construct(bool $debugMode = false)
	{
		$this->debugMode = $debugMode;
		$this->time = microtime(true);

		$this->config = new class {
			/** @var ?bool */
			public $debugger;

			/** @var string[] */
			public $excluded = [];

			/** @var ?string */
			public $parentClass;

			/** @var object */
			public $export;
		};
		$this->config->export = new class {
			/** @var bool */
			public $parameters = true;

			/** @var string[]|bool|null */
			public $tags = true;

			/** @var string[]|bool|null */
			public $types = true;
		};
	}


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$builder->addExcludedClasses($this->config->excluded);
	}


	public function beforeCompile()
	{
		if (!$this->config->export->parameters) {
			$this->getContainerBuilder()->parameters = [];
		}
	}


	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		if ($this->config->parentClass) {
			$class->setExtends($this->config->parentClass);
		}

		$this->restrictTags($class);
		$this->restrictTypes($class);

		if (
			$this->debugMode &&
			($this->config->debugger ?? $this->getContainerBuilder()->getByType(Tracy\Bar::class))
		) {
			$this->enableTracyIntegration();
		}
	}


	private function restrictTags(Nette\PhpGenerator\ClassType $class): void
	{
		$option = $this->config->export->tags;
		if ($option === true) {
		} elseif ($option === false) {
			$class->removeProperty('tags');
		} elseif ($prop = $class->getProperties()['tags'] ?? null) {
			$prop->setValue(array_intersect_key($prop->getValue(), $this->exportedTags + array_flip((array) $option)));
		}
	}


	private function restrictTypes(Nette\PhpGenerator\ClassType $class): void
	{
		$option = $this->config->export->types;
		if ($option === true) {
			return;
		}

		$prop = $class->getProperty('wiring');
		$prop->setValue(array_intersect_key(
			$prop->getValue(),
			$this->exportedTypes + (is_array($option) ? array_flip($option) : [])
		));
	}


	private function enableTracyIntegration(): void
	{
		Nette\Bridges\DITracy\ContainerPanel::$compilationTime = $this->time;
		$this->initialization->addBody($this->getContainerBuilder()->formatPhp('?;', [
			new Nette\DI\Definitions\Statement('@Tracy\Bar::addPanel', [new Nette\DI\Definitions\Statement(Nette\Bridges\DITracy\ContainerPanel::class)]),
		]));
	}
}
