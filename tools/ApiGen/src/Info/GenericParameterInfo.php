<?php declare(strict_types = 1);

namespace ApiGen\Info;

use PHPStan\PhpDocParser\Ast\Type\TypeNode;


class GenericParameterInfo
{
	public function __construct(
		public string $name,
		public GenericParameterVariance $variance,
		public ?TypeNode $bound = null,
		public ?TypeNode $default = null,
		public string $description = '',
	) {
	}
}
