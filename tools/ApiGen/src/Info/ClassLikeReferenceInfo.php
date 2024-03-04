<?php declare(strict_types = 1);

namespace ApiGen\Info;

use ApiGen\Index\Index;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;

use function strtolower;


class ClassLikeReferenceInfo
{
	/** @var string e.g. 'ApiGen\Info\Traits\HasName' */
	public string $full;

	/** @var string e.g. 'apigen\info\traits\hasname' */
	public string $fullLower;

	/** @var TypeNode[] */
	public array $genericArgs = [];


	public function __construct(string $full, ?string $fullLower = null)
	{
		$this->full = $full;
		$this->fullLower = $fullLower ?? strtolower($full);
	}


	public function resolve(Index $index, ?ClassLikeInfo $scope = null): ?ClassLikeInfo
	{
		if ($this->fullLower === 'self' || $this->fullLower === 'static') {
			return $scope;
		}

		if ($this->fullLower === 'parent' && $scope instanceof ClassInfo && $scope->extends !== null) {
			return $index->classLike[$scope->extends->fullLower] ?? null;
		}

		return $index->classLike[$this->fullLower] ?? null;
	}
}
