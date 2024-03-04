<?php declare(strict_types = 1);

namespace ApiGen\Info\Traits;


trait HasLineLocation
{
	/** @var int|null */
	public ?int $startLine = null;

	/** @var int|null */
	public ?int $endLine = null;
}
