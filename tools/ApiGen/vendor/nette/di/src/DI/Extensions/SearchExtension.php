<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\DI\Extensions;

use Nette;
use Nette\Loaders\RobotLoader;
use Nette\Schema\Expect;
use Nette\Utils\Arrays;


/**
 * Services auto-discovery.
 */
final class SearchExtension extends Nette\DI\CompilerExtension
{
	/** @var array */
	private $classes = [];

	/** @var string */
	private $tempDir;


	public function __construct(string $tempDir)
	{
		$this->tempDir = $tempDir;
	}


	public function getConfigSchema(): Nette\Schema\Schema
	{
		return Expect::arrayOf(
			Expect::structure([
				'in' => Expect::string()->required(),
				'files' => Expect::anyOf(Expect::listOf('string'), Expect::string()->castTo('array'))->default([]),
				'classes' => Expect::anyOf(Expect::listOf('string'), Expect::string()->castTo('array'))->default([]),
				'extends' => Expect::anyOf(Expect::listOf('string'), Expect::string()->castTo('array'))->default([]),
				'implements' => Expect::anyOf(Expect::listOf('string'), Expect::string()->castTo('array'))->default([]),
				'exclude' => Expect::structure([
					'classes' => Expect::anyOf(Expect::listOf('string'), Expect::string()->castTo('array'))->default([]),
					'extends' => Expect::anyOf(Expect::listOf('string'), Expect::string()->castTo('array'))->default([]),
					'implements' => Expect::anyOf(Expect::listOf('string'), Expect::string()->castTo('array'))->default([]),
				]),
				'tags' => Expect::array(),
			])
		)->before(function ($val) {
			return is_string($val['in'] ?? null)
				? ['default' => $val]
				: $val;
		});
	}


	public function loadConfiguration()
	{
		foreach (array_filter($this->config) as $name => $batch) {
			if (!is_dir($batch->in)) {
				throw new Nette\DI\InvalidConfigurationException(sprintf(
					"Option '%s\u{a0}›\u{a0}%s\u{a0}›\u{a0}in' must be valid directory name, '%s' given.",
					$this->name,
					$name,
					$batch->in
				));
			}

			foreach ($this->findClasses($batch) as $class) {
				$this->classes[$class] = array_merge($this->classes[$class] ?? [], $batch->tags);
			}
		}
	}


	public function findClasses(\stdClass $config): array
	{
		$robot = new RobotLoader;
		$robot->setTempDirectory($this->tempDir);
		$robot->addDirectory($config->in);
		$robot->acceptFiles = $config->files ?: ['*.php'];
		$robot->reportParseErrors(false);
		$robot->refresh();
		$classes = array_unique(array_keys($robot->getIndexedClasses()));

		$exclude = $config->exclude;
		$acceptRE = self::buildNameRegexp($config->classes);
		$rejectRE = self::buildNameRegexp($exclude->classes);
		$acceptParent = array_merge($config->extends, $config->implements);
		$rejectParent = array_merge($exclude->extends, $exclude->implements);

		$found = [];
		foreach ($classes as $class) {
			if (!class_exists($class) && !interface_exists($class) && !trait_exists($class)) {
				throw new Nette\InvalidStateException(sprintf(
					'Class %s was found, but it cannot be loaded by autoloading.',
					$class
				));
			}

			$rc = new \ReflectionClass($class);
			if (
				($rc->isInstantiable()
					||
					($rc->isInterface()
					&& count($methods = $rc->getMethods()) === 1
					&& $methods[0]->name === 'create')
				)
				&& (!$acceptRE || preg_match($acceptRE, $rc->name))
				&& (!$rejectRE || !preg_match($rejectRE, $rc->name))
				&& (!$acceptParent || Arrays::some($acceptParent, function ($nm) use ($rc) { return $rc->isSubclassOf($nm); }))
				&& (!$rejectParent || Arrays::every($rejectParent, function ($nm) use ($rc) { return !$rc->isSubclassOf($nm); }))
			) {
				$found[] = $rc->name;
			}
		}

		return $found;
	}


	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		foreach ($this->classes as $class => $foo) {
			if ($builder->findByType($class)) {
				unset($this->classes[$class]);
			}
		}

		foreach ($this->classes as $class => $tags) {
			$def = class_exists($class)
				? $builder->addDefinition(null)->setType($class)
				: $builder->addFactoryDefinition(null)->setImplement($class);
			$def->setTags(Arrays::normalize($tags, true));
		}
	}


	private static function buildNameRegexp(array $masks): ?string
	{
		$res = [];
		foreach ((array) $masks as $mask) {
			$mask = (strpos($mask, '\\') === false ? '**\\' : '') . $mask;
			$mask = preg_quote($mask, '#');
			$mask = str_replace('\*\*\\\\', '(.*\\\\)?', $mask);
			$mask = str_replace('\\\\\*\*', '(\\\\.*)?', $mask);
			$mask = str_replace('\*', '\w*', $mask);
			$res[] = $mask;
		}

		return $res ? '#^(' . implode('|', $res) . ')$#i' : null;
	}
}
