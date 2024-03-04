<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator;

use Nette;


/**
 * Promoted parameter in constructor.
 */
final class PromotedParameter extends Parameter
{
	use Traits\VisibilityAware;
	use Traits\CommentAware;

	private bool $readOnly = false;


	public function setReadOnly(bool $state = true): static
	{
		$this->readOnly = $state;
		return $this;
	}


	public function isReadOnly(): bool
	{
		return $this->readOnly;
	}


	/** @throws Nette\InvalidStateException */
	public function validate(): void
	{
		if ($this->readOnly && !$this->getType()) {
			throw new Nette\InvalidStateException("Property \${$this->getName()}: Read-only properties are only supported on typed property.");
		}
	}
}
