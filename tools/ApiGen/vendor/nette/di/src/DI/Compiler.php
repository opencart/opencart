<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI;

use Nette;
use Nette\Schema;


/**
 * DI container compiler.
 */
class Compiler
{
	use Nette\SmartObject;

	private const
		Services = 'services',
		Parameters = 'parameters',
		DI = 'di';

	/** @var CompilerExtension[] */
	private $extensions = [];

	/** @var ContainerBuilder */
	private $builder;

	/** @var array */
	private $config = [];

	/** @var array [section => array[]] */
	private $configs = [];

	/** @var string */
	private $sources = '';

	/** @var DependencyChecker */
	private $dependencies;

	/** @var string */
	private $className = 'Container';


	public function __construct(?ContainerBuilder $builder = null)
	{
		$this->builder = $builder ?: new ContainerBuilder;
		$this->dependencies = new DependencyChecker;
		$this->addExtension(self::Services, new Extensions\ServicesExtension);
		$this->addExtension(self::Parameters, new Extensions\ParametersExtension($this->configs));
	}


	/**
	 * Add custom configurator extension.
	 * @return static
	 */
	public function addExtension(?string $name, CompilerExtension $extension)
	{
		if ($name === null) {
			$name = '_' . count($this->extensions);
		} elseif (isset($this->extensions[$name])) {
			throw new Nette\InvalidArgumentException(sprintf("Name '%s' is already used or reserved.", $name));
		}

		$lname = strtolower($name);
		foreach (array_keys($this->extensions) as $nm) {
			if ($lname === strtolower((string) $nm)) {
				throw new Nette\InvalidArgumentException(sprintf(
					"Name of extension '%s' has the same name as '%s' in a case-insensitive manner.",
					$name,
					$nm
				));
			}
		}

		$this->extensions[$name] = $extension->setCompiler($this, $name);
		return $this;
	}


	public function getExtensions(?string $type = null): array
	{
		return $type
			? array_filter($this->extensions, function ($item) use ($type): bool { return $item instanceof $type; })
			: $this->extensions;
	}


	public function getContainerBuilder(): ContainerBuilder
	{
		return $this->builder;
	}


	/** @return static */
	public function setClassName(string $className)
	{
		$this->className = $className;
		return $this;
	}


	/**
	 * Adds new configuration.
	 * @return static
	 */
	public function addConfig(array $config)
	{
		foreach ($config as $section => $data) {
			$this->configs[$section][] = $data;
		}

		$this->sources .= "// source: array\n";
		return $this;
	}


	/**
	 * Adds new configuration from file.
	 * @return static
	 */
	public function loadConfig(string $file, ?Config\Loader $loader = null)
	{
		$sources = $this->sources . "// source: $file\n";
		$loader = $loader ?: new Config\Loader;
		foreach ($loader->load($file, false) as $data) {
			$this->addConfig($data);
		}

		$this->dependencies->add($loader->getDependencies());
		$this->sources = $sources;
		return $this;
	}


	/**
	 * Returns configuration.
	 * @deprecated
	 */
	public function getConfig(): array
	{
		return $this->config;
	}


	/**
	 * Sets the names of dynamic parameters.
	 * @return static
	 */
	public function setDynamicParameterNames(array $names)
	{
		assert($this->extensions[self::Parameters] instanceof Extensions\ParametersExtension);
		$this->extensions[self::Parameters]->dynamicParams = $names;
		return $this;
	}


	/**
	 * Adds dependencies to the list.
	 * @param  array  $deps  of ReflectionClass|\ReflectionFunctionAbstract|string
	 * @return static
	 */
	public function addDependencies(array $deps)
	{
		$this->dependencies->add(array_filter($deps));
		return $this;
	}


	/**
	 * Exports dependencies.
	 */
	public function exportDependencies(): array
	{
		return $this->dependencies->export();
	}


	/** @return static */
	public function addExportedTag(string $tag)
	{
		if (isset($this->extensions[self::DI])) {
			assert($this->extensions[self::DI] instanceof Extensions\DIExtension);
			$this->extensions[self::DI]->exportedTags[$tag] = true;
		}

		return $this;
	}


	/** @return static */
	public function addExportedType(string $type)
	{
		if (isset($this->extensions[self::DI])) {
			assert($this->extensions[self::DI] instanceof Extensions\DIExtension);
			$this->extensions[self::DI]->exportedTypes[$type] = true;
		}

		return $this;
	}


	public function compile(): string
	{
		$this->processExtensions();
		$this->processBeforeCompile();
		return $this->generateCode();
	}


	/** @internal */
	public function processExtensions(): void
	{
		$first = $this->getExtensions(Extensions\ParametersExtension::class) + $this->getExtensions(Extensions\ExtensionsExtension::class);
		foreach ($first as $name => $extension) {
			$config = $this->processSchema($extension->getConfigSchema(), $this->configs[$name] ?? [], $name);
			$extension->setConfig($this->config[$name] = $config);
			$extension->loadConfiguration();
		}

		$last = $this->getExtensions(Extensions\InjectExtension::class);
		$this->extensions = array_merge(array_diff_key($this->extensions, $last), $last);

		if ($decorator = $this->getExtensions(Extensions\DecoratorExtension::class)) {
			Nette\Utils\Arrays::insertBefore($this->extensions, key($decorator), $this->getExtensions(Extensions\SearchExtension::class));
		}

		$extensions = array_diff_key($this->extensions, $first, [self::Services => 1]);
		foreach ($extensions as $name => $extension) {
			$config = $this->processSchema($extension->getConfigSchema(), $this->configs[$name] ?? [], $name);
			$extension->setConfig($this->config[$name] = $config);
		}

		foreach ($extensions as $extension) {
			$extension->loadConfiguration();
		}

		foreach ($this->getExtensions(Extensions\ServicesExtension::class) as $name => $extension) {
			$config = $this->processSchema($extension->getConfigSchema(), $this->configs[$name] ?? [], $name);
			$extension->setConfig($this->config[$name] = $config);
			$extension->loadConfiguration();
		}

		if ($extra = array_diff_key($this->extensions, $extensions, $first, [self::Services => 1])) {
			throw new Nette\DeprecatedException(sprintf(
				"Extensions '%s' were added while container was being compiled.",
				implode("', '", array_keys($extra))
			));

		} elseif ($extra = key(array_diff_key($this->configs, $this->extensions))) {
			$hint = Nette\Utils\Helpers::getSuggestion(array_keys($this->extensions), $extra);
			throw new InvalidConfigurationException(
				sprintf("Found section '%s' in configuration, but corresponding extension is missing", $extra)
				. ($hint ? ", did you mean '$hint'?" : '.')
			);
		}
	}


	private function processBeforeCompile(): void
	{
		$this->builder->resolve();

		foreach ($this->extensions as $extension) {
			$extension->beforeCompile();
			$this->dependencies->add([(new \ReflectionClass($extension))->getFileName()]);
		}

		$this->builder->complete();
	}


	/**
	 * Merges and validates configurations against scheme.
	 * @return array|object
	 */
	private function processSchema(Schema\Schema $schema, array $configs, $name = null)
	{
		$processor = new Schema\Processor;
		$processor->onNewContext[] = function (Schema\Context $context) use ($name) {
			$context->path = $name ? [$name] : [];
			$context->dynamics = &$this->extensions[self::Parameters]->dynamicValidators;
		};
		try {
			$res = $processor->processMultiple($schema, $configs);
		} catch (Schema\ValidationException $e) {
			throw new Nette\DI\InvalidConfigurationException($e->getMessage());
		}

		foreach ($processor->getWarnings() as $warning) {
			trigger_error($warning, E_USER_DEPRECATED);
		}

		return $res;
	}


	/** @internal */
	public function generateCode(): string
	{
		$generator = $this->createPhpGenerator();
		$class = $generator->generate($this->className);
		$this->dependencies->add($this->builder->getDependencies());

		foreach ($this->extensions as $extension) {
			$extension->afterCompile($class);
			$generator->addInitialization($class, $extension);
		}

		return $this->sources . "\n" . $generator->toString($class);
	}


	/**
	 * Loads list of service definitions from configuration.
	 */
	public function loadDefinitionsFromConfig(array $configList): void
	{
		$extension = $this->extensions[self::Services];
		assert($extension instanceof Extensions\ServicesExtension);
		$extension->loadDefinitions($this->processSchema($extension->getConfigSchema(), [$configList]));
	}


	protected function createPhpGenerator(): PhpGenerator
	{
		return new PhpGenerator($this->builder);
	}
}
