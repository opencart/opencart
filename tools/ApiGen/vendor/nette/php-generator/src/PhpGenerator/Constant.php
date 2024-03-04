<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;

use Nette;


/**
 * Class constant.
 */
final class Constant
{
	use Nette\SmartObject;
	use Traits\NameAware;
	use Traits\VisibilityAware;
	use Traits\CommentAware;
	use Traits\AttributeAware;

	private mixed $value;
	private bool $final = false;
	private ?string $type = null;


	public function setValue(mixed $val): static
	{
		$this->value = $val;
		return $this;
	}


	public function getValue(): mixed
	{
		return $this->value;
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


	public function setType(?string $type): static
	{
		$this->type = Helpers::validateType($type);
		return $this;
	}


	public function getType(): ?string
	{
		return $this->type;
	}
}
