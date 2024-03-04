<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;

use Nette;


/**
 * Enum description.
 */
final class EnumType extends ClassLike
{
	use Traits\ConstantsAware;
	use Traits\MethodsAware;
	use Traits\TraitsAware;

	/** @var string[] */
	private array $implements = [];

	/** @var array<string, EnumCase> */
	private array $cases = [];
	private ?string $type = null;


	public function setType(?string $type): static
	{
		$this->type = $type;
		return $this;
	}


	public function getType(): ?string
	{
		return $this->type;
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


	/**
	 * Sets cases to enum
	 * @param  EnumCase[]  $cases
	 */
	public function setCases(array $cases): static
	{
		(function (EnumCase ...$cases) {})(...$cases);
		$this->cases = [];
		foreach ($cases as $case) {
			$this->cases[$case->getName()] = $case;
		}

		return $this;
	}


	/** @return EnumCase[] */
	public function getCases(): array
	{
		return $this->cases;
	}


	/** Adds case to enum */
	public function addCase(string $name, string|int|Literal|null $value = null): EnumCase
	{
		if (isset($this->cases[$name])) {
			throw new Nette\InvalidStateException("Cannot add cases '$name', because it already exists.");
		}
		return $this->cases[$name] = (new EnumCase($name))
			->setValue($value);
	}


	public function removeCase(string $name): static
	{
		unset($this->cases[$name]);
		return $this;
	}


	public function addMember(Method|Constant|EnumCase|TraitUse $member): static
	{
		$name = $member->getName();
		[$type, $n] = match (true) {
			$member instanceof Constant => ['consts', $name],
			$member instanceof Method => ['methods', strtolower($name)],
			$member instanceof TraitUse => ['traits', $name],
			$member instanceof EnumCase => ['cases', $name],
		};
		if (isset($this->$type[$n])) {
			throw new Nette\InvalidStateException("Cannot add member '$name', because it already exists.");
		}
		$this->$type[$n] = $member;
		return $this;
	}


	public function __clone()
	{
		$clone = fn($item) => clone $item;
		$this->consts = array_map($clone, $this->consts);
		$this->methods = array_map($clone, $this->methods);
		$this->traits = array_map($clone, $this->traits);
		$this->cases = array_map($clone, $this->cases);
	}
}
