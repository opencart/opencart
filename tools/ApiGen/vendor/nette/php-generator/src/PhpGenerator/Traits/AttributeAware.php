<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator\Traits;

use Nette\PhpGenerator\Attribute;


/**
 * @internal
 */
trait AttributeAware
{
	/** @var Attribute[] */
	private array $attributes = [];


	/** @param  mixed[]  $args */
	public function addAttribute(string $name, array $args = []): static
	{
		$this->attributes[] = new Attribute($name, $args);
		return $this;
	}


	/**
	 * @param  Attribute[]  $attrs
	 */
	public function setAttributes(array $attrs): static
	{
		(function (Attribute ...$attrs) {})(...$attrs);
		$this->attributes = $attrs;
		return $this;
	}


	/** @return Attribute[] */
	public function getAttributes(): array
	{
		return $this->attributes;
	}
}
