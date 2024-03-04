<?php declare(strict_types = 1);

namespace ApiGen\Info;

use function strtolower;


class AliasReferenceInfo
{
	/** @var ClassLikeReferenceInfo */
	public ClassLikeReferenceInfo $classLike;

	/** @var string e.g. 'DatabaseOptions' */
	public string $alias;

	/** @var string e.g. 'databaseoptions' */
	public string $aliasLower;


	public function __construct(
		ClassLikeReferenceInfo $classLike,
		string $alias,
		?string $aliasLower = null,
	) {
		$this->classLike = $classLike;
		$this->alias = $alias;
		$this->aliasLower = $aliasLower ?? strtolower($alias);
	}
}
