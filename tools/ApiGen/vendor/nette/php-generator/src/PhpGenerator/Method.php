<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;

use Nette;


/**
 * Class method.
 *
 * @property-deprecated string|null $body
 */
final class Method
{
	use Nette\SmartObject;
	use Traits\FunctionLike;
	use Traits\NameAware;
	use Traits\VisibilityAware;
	use Traits\CommentAware;
	use Traits\AttributeAware;

	private bool $static = false;
	private bool $final = false;
	private bool $abstract = false;


	public static function from(string|array $method): static
	{
		return (new Factory)->fromMethodReflection(Nette\Utils\Callback::toReflection($method));
	}


	public function __toString(): string
	{
		return (new Printer)->printMethod($this);
	}


	public function setStatic(bool $state = true): static
	{
		$this->static = $state;
		return $this;
	}


	public function isStatic(): bool
	{
		return $this->static;
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


	/**
	 * @param  string  $name without $
	 */
	public function addPromotedParameter(string $name, mixed $defaultValue = null): PromotedParameter
	{
		$param = new PromotedParameter($name);
		if (func_num_args() > 1) {
			$param->setDefaultValue($defaultValue);
		}

		return $this->parameters[$name] = $param;
	}


	/** @throws Nette\InvalidStateException */
	public function validate(): void
	{
		if ($this->abstract && ($this->final || $this->visibility === ClassLike::VisibilityPrivate)) {
			throw new Nette\InvalidStateException("Method $this->name() cannot be abstract and final or private at the same time.");
		}
	}
}
