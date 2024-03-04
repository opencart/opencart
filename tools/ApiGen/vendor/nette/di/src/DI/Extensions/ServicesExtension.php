<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI\Extensions;

use Nette;
use Nette\DI\Definitions;
use Nette\DI\Definitions\Statement;
use Nette\DI\Helpers;


/**
 * Service definitions loader.
 */
final class ServicesExtension extends Nette\DI\CompilerExtension
{
	use Nette\SmartObject;

	public function getConfigSchema(): Nette\Schema\Schema
	{
		return Nette\Schema\Expect::arrayOf(new DefinitionSchema($this->getContainerBuilder()));
	}


	public function loadConfiguration()
	{
		$this->loadDefinitions($this->config);
	}


	/**
	 * Loads list of service definitions.
	 */
	public function loadDefinitions(array $config)
	{
		foreach ($config as $key => $defConfig) {
			$this->loadDefinition($this->convertKeyToName($key), $defConfig);
		}
	}


	/**
	 * Loads service definition from normalized configuration.
	 */
	private function loadDefinition(?string $name, \stdClass $config): void
	{
		try {
			if ((array) $config === [false]) {
				$this->getContainerBuilder()->removeDefinition($name);
				return;
			} elseif (!empty($config->alteration) && !$this->getContainerBuilder()->hasDefinition($name)) {
				throw new Nette\DI\InvalidConfigurationException('missing original definition for alteration.');
			}

			$def = $this->retrieveDefinition($name, $config);

			$methods = [
				Definitions\ServiceDefinition::class => 'updateServiceDefinition',
				Definitions\AccessorDefinition::class => 'updateAccessorDefinition',
				Definitions\FactoryDefinition::class => 'updateFactoryDefinition',
				Definitions\LocatorDefinition::class => 'updateLocatorDefinition',
				Definitions\ImportedDefinition::class => 'updateImportedDefinition',
			];
			$this->{$methods[$config->defType]}($def, $config);
			$this->updateDefinition($def, $config);
		} catch (\Throwable $e) {
			throw new Nette\DI\InvalidConfigurationException(($name ? "Service '$name': " : '') . $e->getMessage(), 0, $e);
		}
	}


	/**
	 * Updates service definition according to normalized configuration.
	 */
	private function updateServiceDefinition(Definitions\ServiceDefinition $definition, \stdClass $config): void
	{
		if ($config->create) {
			$definition->setCreator(Helpers::filterArguments([$config->create])[0]);
			$definition->setType(null);
		}

		if ($config->type) {
			$definition->setType($config->type);
		}

		if ($config->arguments) {
			$arguments = Helpers::filterArguments($config->arguments);
			if (empty($config->reset['arguments']) && !Nette\Utils\Arrays::isList($arguments)) {
				$arguments = array_replace($definition->getCreator()->arguments, $arguments);
			}

			$definition->setArguments($arguments);
		}

		if (isset($config->setup)) {
			if (!empty($config->reset['setup'])) {
				$definition->setSetup([]);
			}

			foreach (Helpers::filterArguments($config->setup) as $id => $setup) {
				if (is_array($setup)) {
					$setup = new Statement(key($setup), array_values($setup));
				}

				$definition->addSetup($setup);
			}
		}

		if (isset($config->inject)) {
			$definition->addTag(InjectExtension::TagInject, $config->inject);
		}
	}


	private function updateAccessorDefinition(Definitions\AccessorDefinition $definition, \stdClass $config): void
	{
		if (isset($config->implement)) {
			$definition->setImplement($config->implement);
		}

		if ($ref = $config->create ?? $config->type ?? null) {
			$definition->setReference($ref);
		}
	}


	private function updateFactoryDefinition(Definitions\FactoryDefinition $definition, \stdClass $config): void
	{
		$resultDef = $definition->getResultDefinition();

		if (isset($config->implement)) {
			$definition->setImplement($config->implement);
			$definition->setAutowired(true);
		}

		if ($config->create) {
			$resultDef->setCreator(Helpers::filterArguments([$config->create])[0]);
		}

		if ($config->type) {
			$resultDef->setCreator($config->type);
		}

		if ($config->arguments) {
			$arguments = Helpers::filterArguments($config->arguments);
			if (empty($config->reset['arguments']) && !Nette\Utils\Arrays::isList($arguments)) {
				$arguments = array_replace($resultDef->getCreator()->arguments, $arguments);
			}

			$resultDef->setArguments($arguments);
		}

		if (isset($config->setup)) {
			if (!empty($config->reset['setup'])) {
				$resultDef->setSetup([]);
			}

			foreach (Helpers::filterArguments($config->setup) as $id => $setup) {
				if (is_array($setup)) {
					$setup = new Statement(key($setup), array_values($setup));
				}

				$resultDef->addSetup($setup);
			}
		}

		if (isset($config->parameters)) {
			$definition->setParameters($config->parameters);
		}

		if (isset($config->inject)) {
			$definition->addTag(InjectExtension::TagInject, $config->inject);
		}
	}


	private function updateLocatorDefinition(Definitions\LocatorDefinition $definition, \stdClass $config): void
	{
		if (isset($config->implement)) {
			$definition->setImplement($config->implement);
		}

		if (isset($config->references)) {
			$definition->setReferences($config->references);
		}

		if (isset($config->tagged)) {
			$definition->setTagged($config->tagged);
		}
	}


	private function updateImportedDefinition(Definitions\ImportedDefinition $definition, \stdClass $config): void
	{
		if ($config->type) {
			$definition->setType($config->type);
		}
	}


	private function updateDefinition(Definitions\Definition $definition, \stdClass $config): void
	{
		if (isset($config->autowired)) {
			$definition->setAutowired($config->autowired);
		}

		if (isset($config->tags)) {
			if (!empty($config->reset['tags'])) {
				$definition->setTags([]);
			}

			foreach ($config->tags as $tag => $attrs) {
				if (is_int($tag) && is_string($attrs)) {
					$definition->addTag($attrs);
				} else {
					$definition->addTag($tag, $attrs);
				}
			}
		}
	}


	private function convertKeyToName($key): ?string
	{
		if (is_int($key)) {
			return null;
		} elseif (preg_match('#^@[\w\\\\]+$#D', $key)) {
			return $this->getContainerBuilder()->getByType(substr($key, 1), true);
		}

		return $key;
	}


	private function retrieveDefinition(?string $name, \stdClass $config): Definitions\Definition
	{
		$builder = $this->getContainerBuilder();
		if (!empty($config->reset['all'])) {
			$builder->removeDefinition($name);
		}

		return $name && $builder->hasDefinition($name)
			? $builder->getDefinition($name)
			: $builder->addDefinition($name, new $config->defType);
	}
}
