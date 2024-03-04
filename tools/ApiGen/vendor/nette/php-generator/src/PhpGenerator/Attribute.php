<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;

use Nette;


/**
 * PHP Attribute.
 */
final class Attribute
{
	use Nette\SmartObject;

	private string $name;

	/** @var mixed[] */
	private array $args;


	/** @param  mixed[]  $args */
	public function __construct(string $name, array $args)
	{
		if (!Helpers::isNamespaceIdentifier($name)) {
			throw new Nette\InvalidArgumentException("Value '$name' is not valid attribute name.");
		}

		$this->name = $name;
		$this->args = $args;
	}


	public function getName(): string
	{
		return $this->name;
	}


	/** @return mixed[] */
	public function getArguments(): array
	{
		return $this->args;
	}
}
