<?php declare(strict_types = 1);

namespace ApiGen\Info;

use ApiGen\Index\Index;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;

use function array_values;
use function count;


class ParameterInfo
{
	/** @var string */
	public string $name;

	/** @var int */
	public int $position;

	/** @var PhpDocTextNode[] indexed by [] */
	public array $description = [];

	/** @var TypeNode|null */
	public ?TypeNode $type = null;

	/** @var bool */
	public bool $byRef = false;

	/** @var bool */
	public bool $variadic = false;

	/** @var ExprInfo|null */
	public ?ExprInfo $default = null;


	public function __construct(string $name, int $position)
	{
		$this->name = $name;
		$this->position = $position;
	}


	/**
	 * @return PhpDocTextNode[] indexed by []
	 */
	public function getEffectiveDescription(Index $index, ClassLikeInfo $classLike, MethodInfo $method): array
	{
		if (count($this->description) > 0) {
			return $this->description;
		}

		foreach ($method->ancestors($index, $classLike) as $ancestor) {
			$ancestorMethod = $ancestor->methods[$method->nameLower];
			$ancestorParameter = array_values($ancestorMethod->parameters)[$this->position] ?? null;
			$description = $ancestorParameter?->getEffectiveDescription($index, $ancestor, $ancestorMethod) ?? [];

			if (count($description) > 0) {
				return $description;
			}
		}

		return [];
	}
}
