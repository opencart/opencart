<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;

use Nette;


/**
 * Instance of PHP file.
 *
 * Generates:
 * - opening tag (<?php)
 * - doc comments
 * - one or more namespaces
 */
final class PhpFile
{
	use Nette\SmartObject;
	use Traits\CommentAware;

	/** @var PhpNamespace[] */
	private array $namespaces = [];
	private bool $strictTypes = false;


	public static function fromCode(string $code): self
	{
		return (new Factory)->fromCode($code);
	}


	public function addClass(string $name): ClassType
	{
		return $this
			->addNamespace(Helpers::extractNamespace($name))
			->addClass(Helpers::extractShortName($name));
	}


	public function addInterface(string $name): InterfaceType
	{
		return $this
			->addNamespace(Helpers::extractNamespace($name))
			->addInterface(Helpers::extractShortName($name));
	}


	public function addTrait(string $name): TraitType
	{
		return $this
			->addNamespace(Helpers::extractNamespace($name))
			->addTrait(Helpers::extractShortName($name));
	}


	public function addEnum(string $name): EnumType
	{
		return $this
			->addNamespace(Helpers::extractNamespace($name))
			->addEnum(Helpers::extractShortName($name));
	}


	public function addNamespace(string|PhpNamespace $namespace): PhpNamespace
	{
		$res = $namespace instanceof PhpNamespace
			? ($this->namespaces[$namespace->getName()] = $namespace)
			: ($this->namespaces[$namespace] ??= new PhpNamespace($namespace));

		foreach ($this->namespaces as $namespace) {
			$namespace->setBracketedSyntax(count($this->namespaces) > 1 && isset($this->namespaces['']));
		}

		return $res;
	}


	public function addFunction(string $name): GlobalFunction
	{
		return $this
			->addNamespace(Helpers::extractNamespace($name))
			->addFunction(Helpers::extractShortName($name));
	}


	/** @return PhpNamespace[] */
	public function getNamespaces(): array
	{
		return $this->namespaces;
	}


	/** @return ClassLike[] */
	public function getClasses(): array
	{
		$classes = [];
		foreach ($this->namespaces as $n => $namespace) {
			$n .= $n ? '\\' : '';
			foreach ($namespace->getClasses() as $c => $class) {
				$classes[$n . $c] = $class;
			}
		}

		return $classes;
	}


	/** @return GlobalFunction[] */
	public function getFunctions(): array
	{
		$functions = [];
		foreach ($this->namespaces as $n => $namespace) {
			$n .= $n ? '\\' : '';
			foreach ($namespace->getFunctions() as $f => $function) {
				$functions[$n . $f] = $function;
			}
		}

		return $functions;
	}


	public function addUse(string $name, ?string $alias = null, string $of = PhpNamespace::NameNormal): static
	{
		$this->addNamespace('')->addUse($name, $alias, $of);
		return $this;
	}


	/**
	 * Adds declare(strict_types=1) to output.
	 */
	public function setStrictTypes(bool $on = true): static
	{
		$this->strictTypes = $on;
		return $this;
	}


	public function hasStrictTypes(): bool
	{
		return $this->strictTypes;
	}


	/** @deprecated  use hasStrictTypes() */
	public function getStrictTypes(): bool
	{
		trigger_error(__METHOD__ . '() is deprecated, use hasStrictTypes().', E_USER_DEPRECATED);
		return $this->strictTypes;
	}


	public function __toString(): string
	{
		return (new Printer)->printFile($this);
	}
}
