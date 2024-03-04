<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;

use Nette;


/**
 * Class description.
 *
 * @property-deprecated Method[] $methods
 * @property-deprecated Property[] $properties
 */
final class ClassType extends ClassLike
{
	use Traits\ConstantsAware;
	use Traits\MethodsAware;
	use Traits\PropertiesAware;
	use Traits\TraitsAware;

	/** @deprecated */
	public const
		TYPE_CLASS = 'class',
		TYPE_INTERFACE = 'interface',
		TYPE_TRAIT = 'trait',
		TYPE_ENUM = 'enum';

	private string $type = self::TYPE_CLASS;
	private bool $final = false;
	private bool $abstract = false;
	private ?string $extends = null;
	private bool $readOnly = false;

	/** @var string[] */
	private array $implements = [];


	/** @deprecated  create object using 'new Nette\PhpGenerator\ClassType' */
	public static function class(?string $name): self
	{
		return new self($name);
	}


	/** @deprecated  create object using 'new Nette\PhpGenerator\InterfaceType' */
	public static function interface(string $name): InterfaceType
	{
		return new InterfaceType($name);
	}


	/** @deprecated  create object using 'new Nette\PhpGenerator\TraitType' */
	public static function trait(string $name): TraitType
	{
		return new TraitType($name);
	}


	/** @deprecated  create object using 'new Nette\PhpGenerator\EnumType' */
	public static function enum(string $name): EnumType
	{
		return new EnumType($name);
	}


	public function __construct(?string $name = null, ?PhpNamespace $namespace = null)
	{
		if ($name === null) {
			parent::__construct('foo', $namespace);
			$this->setName(null);
		} else {
			parent::__construct($name, $namespace);
		}
	}


	/** @deprecated */
	public function setClass(): static
	{
		trigger_error(__METHOD__ . '() is deprecated.', E_USER_DEPRECATED);
		$this->type = self::TYPE_CLASS;
		return $this;
	}


	public function isClass(): bool
	{
		return $this->type === self::TYPE_CLASS;
	}


	/** @deprecated  create object using 'new Nette\PhpGenerator\InterfaceType' */
	public function setInterface(): static
	{
		trigger_error(__METHOD__ . "() is deprecated, create object using 'new Nette\\PhpGenerator\\InterfaceType'", E_USER_DEPRECATED);
		$this->type = self::TYPE_INTERFACE;
		return $this;
	}


	public function isInterface(): bool
	{
		return $this->type === self::TYPE_INTERFACE;
	}


	/** @deprecated  create object using 'new Nette\PhpGenerator\TraitType' */
	public function setTrait(): static
	{
		trigger_error(__METHOD__ . "() is deprecated, create object using 'new Nette\\PhpGenerator\\TraitType'", E_USER_DEPRECATED);
		$this->type = self::TYPE_TRAIT;
		return $this;
	}


	public function isTrait(): bool
	{
		return $this->type === self::TYPE_TRAIT;
	}


	/** @deprecated  create object using 'new Nette\PhpGenerator\InterfaceType' or 'TraitType' */
	public function setType(string $type): static
	{
		$upper = ucfirst($type);
		trigger_error(__METHOD__ . "() is deprecated, create object using 'new Nette\\PhpGenerator\\{$upper}Type'", E_USER_DEPRECATED);
		if (!in_array($type, [self::TYPE_CLASS, self::TYPE_INTERFACE, self::TYPE_TRAIT], true)) {
			throw new Nette\InvalidArgumentException('Argument must be class|interface|trait.');
		}

		$this->type = $type;
		return $this;
	}


	/** @deprecated */
	public function getType(): string
	{
		return $this->type;
	}


	public function setFinal(bool $state = true): static
	{
		$this->final = $state;
		return $this;
	}


	public function isFinal(): bool
	{
		return $this->final;
	}


	public function setAbstract(bool $state = true): static
	{
		$this->abstract = $state;
		return $this;
	}


	public function isAbstract(): bool
	{
		return $this->abstract;
	}


	public function setReadOnly(bool $state = true): static
	{
		$this->readOnly = $state;
		return $this;
	}


	public function isReadOnly(): bool
	{
		return $this->readOnly;
	}


	public function setExtends(?string $name): static
	{
		if ($name) {
			$this->validateNames([$name]);
		}
		$this->extends = $name;
		return $this;
	}


	public function getExtends(): ?string
	{
		return $this->extends;
	}


	/**
	 * @param  string[]  $names
	 */
	public function setImplements(array $names): static
	{
		$this->validateNames($names);
		$this->implements = $names;
		return $this;
	}


	/** @return string[] */
	public function getImplements(): array
	{
		return $this->implements;
	}


	public function addImplement(string $name): static
	{
		$this->validateNames([$name]);
		$this->implements[] = $name;
		return $this;
	}


	public function removeImplement(string $name): static
	{
		$this->implements = array_diff($this->implements, [$name]);
		return $this;
	}


	public function addMember(Method|Property|Constant|TraitUse $member): static
	{
		$name = $member->getName();
		[$type, $n] = match (true) {
			$member instanceof Constant => ['consts', $name],
			$member instanceof Method => ['methods', strtolower($name)],
			$member instanceof Property => ['properties', $name],
			$member instanceof TraitUse => ['traits', $name],
		};
		if (isset($this->$type[$n])) {
			throw new Nette\InvalidStateException("Cannot add member '$name', because it already exists.");
		}
		$this->$type[$n] = $member;
		return $this;
	}


	/** @throws Nette\InvalidStateException */
	public function validate(): void
	{
		$name = $this->getName();
		if ($name === null && ($this->abstract || $this->final)) {
			throw new Nette\InvalidStateException('Anonymous class cannot be abstract or final.');

		} elseif ($this->abstract && $this->final) {
			throw new Nette\InvalidStateException("Class '$name' cannot be abstract and final at the same time.");
		}
	}


	public function __clone()
	{
		$clone = fn($item) => clone $item;
		$this->consts = array_map($clone, $this->consts);
		$this->methods = array_map($clone, $this->methods);
		$this->properties = array_map($clone, $this->properties);
		$this->traits = array_map($clone, $this->traits);
	}
}
