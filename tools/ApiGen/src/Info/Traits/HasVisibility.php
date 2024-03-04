<?php declare(strict_types = 1);

namespace ApiGen\Info\Traits;


trait HasVisibility
{
	/** @var bool */
	public bool $public = false;

	/** @var bool */
	public bool $protected = false;

	/** @var bool */
	public bool $private = false;
}
