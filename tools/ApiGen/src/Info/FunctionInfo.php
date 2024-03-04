<?php declare(strict_types = 1);

namespace ApiGen\Info;

use ApiGen\Info\Traits\HasGenericParameters;
use ApiGen\Info\Traits\HasLineLocation;
use ApiGen\Info\Traits\HasTags;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;


class FunctionInfo implements ElementInfo
{
	use HasTags;
	use HasLineLocation;
	use HasGenericParameters;


	/** @var string|null */
	public ?string $file = null;

	/** @var ParameterInfo[] indexed by [parameterName] */
	public array $parameters = [];

	/** @var TypeNode|null */
	public ?TypeNode $returnType = null;

	/** @var PhpDocTextNode[] indexed by [] */
	public array $returnDescription = [];

	/** @var bool */
	public bool $byRef = false;


	public function __construct(
		public NameInfo $name,
		public bool $primary,
	) {
	}
}
