<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Nette\PhpGenerator\Traits;

use Nette;
use Nette\PhpGenerator\ClassLike;


/**
 * @internal
 */
trait VisibilityAware
{
	/** public|protected|private */
	private ?string $visibility = null;


	/**
	 * @param  string|null  $val  public|protected|private
	 */
	public function setVisibility(?string $val): static
	{
		if (!in_array($val, [ClassLike::VisibilityPublic, ClassLike::VisibilityProtected, ClassLike::VisibilityPrivate, null], true)) {
			throw new Nette\InvalidArgumentException('Argument must be public|protected|private.');
		}

		$this->visibility = $val;
		return $this;
	}


	public function getVisibility(): ?string
	{
		return $this->visibility;
	}


	public function setPublic(): static
	{
		$this->visibility = ClassLike::VisibilityPublic;
		return $this;
	}


	public function isPublic(): bool
	{
		return $this->visibility === ClassLike::VisibilityPublic || $this->visibility === null;
	}


	public function setProtected(): static
	{
		$this->visibility = ClassLike::VisibilityProtected;
		return $this;
	}


	public function isProtected(): bool
	{
		return $this->visibility === ClassLike::VisibilityProtected;
	}


	public function setPrivate(): static
	{
		$this->visibility = ClassLike::VisibilityPrivate;
		return $this;
	}


	public function isPrivate(): bool
	{
		return $this->visibility === ClassLike::VisibilityPrivate;
	}
}
