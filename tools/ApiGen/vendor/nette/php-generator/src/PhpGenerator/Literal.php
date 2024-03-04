<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;


/**
 * PHP literal value.
 */
class Literal
{
	public function __construct(
		private string $value,
		/** @var ?mixed[] */
		private ?array $args = null,
	) {
	}


	public function __toString(): string
	{
		return $this->formatWith(new Dumper);
	}


	/** @internal */
	public function formatWith(Dumper $dumper): string
	{
		return $this->args === null
			? $this->value
			: $dumper->format($this->value, ...$this->args);
	}
}
