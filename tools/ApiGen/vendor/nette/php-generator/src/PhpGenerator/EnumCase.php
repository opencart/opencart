<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;

use Nette;


/**
 * Enum case.
 */
final class EnumCase
{
	use Nette\SmartObject;
	use Traits\NameAware;
	use Traits\CommentAware;
	use Traits\AttributeAware;

	private string|int|Literal|null $value = null;


	public function setValue(string|int|Literal|null $val): static
	{
		$this->value = $val;
		return $this;
	}


	public function getValue(): string|int|Literal|null
	{
		return $this->value;
	}
}
