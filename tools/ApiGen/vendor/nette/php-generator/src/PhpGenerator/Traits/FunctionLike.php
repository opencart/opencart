<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator\Traits;

use JetBrains\PhpStorm\Language;
use Nette;
use Nette\PhpGenerator\Dumper;
use Nette\PhpGenerator\Parameter;
use Nette\Utils\Type;


/**
 * @internal
 */
trait FunctionLike
{
	private string $body = '';

	/** @var Parameter[] */
	private array $parameters = [];
	private bool $variadic = false;
	private ?string $returnType = null;
	private bool $returnReference = false;
	private bool $returnNullable = false;


	/** @param  ?mixed[]  $args */
	public function setBody(
		#[Language('PHP')]
		string $code,
		?array $args = null,
	): static
	{
		$this->body = $args === null
			? $code
			: (new Dumper)->format($code, ...$args);
		return $this;
	}


	public function getBody(): string
	{
		return $this->body;
	}


	/** @param  ?mixed[]  $args */
	public function addBody(
		#[Language('PHP')]
		string $code,
		?array $args = null,
	): static
	{
		$this->body .= ($args === null ? $code : (new Dumper)->format($code, ...$args)) . "\n";
		return $this;
	}


	/**
	 * @param  Parameter[]  $val
	 */
	public function setParameters(array $val): static
	{
		(function (Parameter ...$val) {})(...$val);
		$this->parameters = [];
		foreach ($val as $v) {
			$this->parameters[$v->getName()] = $v;
		}

		return $this;
	}


	/** @return Parameter[] */
	public function getParameters(): array
	{
		return $this->parameters;
	}


	/**
	 * @param  string  $name without $
	 */
	public function addParameter(string $name, mixed $defaultValue = null): Parameter
	{
		$param = new Parameter($name);
		if (func_num_args() > 1) {
			$param->setDefaultValue($defaultValue);
		}

		return $this->parameters[$name] = $param;
	}


	/**
	 * @param  string  $name without $
	 */
	public function removeParameter(string $name): static
	{
		unset($this->parameters[$name]);
		return $this;
	}


	public function setVariadic(bool $state = true): static
	{
		$this->variadic = $state;
		return $this;
	}


	public function isVariadic(): bool
	{
		return $this->variadic;
	}


	public function setReturnType(?string $type): static
	{
		$this->returnType = Nette\PhpGenerator\Helpers::validateType($type, $this->returnNullable);
		return $this;
	}


	public function getReturnType(bool $asObject = false): Type|string|null
	{
		return $asObject && $this->returnType
			? Type::fromString($this->returnType)
			: $this->returnType;
	}


	public function setReturnReference(bool $state = true): static
	{
		$this->returnReference = $state;
		return $this;
	}


	public function getReturnReference(): bool
	{
		return $this->returnReference;
	}


	public function setReturnNullable(bool $state = true): static
	{
		$this->returnNullable = $state;
		return $this;
	}


	public function isReturnNullable(): bool
	{
		return $this->returnNullable;
	}


	/** @deprecated  use isReturnNullable() */
	public function getReturnNullable(): bool
	{
		trigger_error(__METHOD__ . '() is deprecated, use isReturnNullable().', E_USER_DEPRECATED);
		return $this->returnNullable;
	}
}
