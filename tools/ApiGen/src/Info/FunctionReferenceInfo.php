<?php declare(strict_types = 1);

namespace ApiGen\Info;

use function strtolower;


class FunctionReferenceInfo
{
	/** @var string e.g. 'Tracy\dump' */
	public string $full;

	/** @var string e.g. 'tracy\dump' */
	public string $fullLower;


	public function __construct(string $full, ?string $fullLower = null)
	{
		$this->full = $full;
		$this->fullLower = $fullLower ?? strtolower($full);
	}
}
