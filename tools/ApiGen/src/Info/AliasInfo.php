<?php declare(strict_types = 1);

namespace ApiGen\Info;

use ApiGen\Info\Traits\HasLineLocation;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;


class AliasInfo
{
	use HasLineLocation;


	public function __construct(
		public string $name,
		public TypeNode $type,
	) {
	}
}
