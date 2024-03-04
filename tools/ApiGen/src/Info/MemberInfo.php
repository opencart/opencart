<?php declare(strict_types = 1);

namespace ApiGen\Info;

use ApiGen\Index\Index;
use ApiGen\Info\Traits\HasLineLocation;
use ApiGen\Info\Traits\HasTags;
use ApiGen\Info\Traits\HasVisibility;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;


abstract class MemberInfo
{
	use HasTags;
	use HasLineLocation;
	use HasVisibility;


	/** @var string */
	public string $name;

	/** @var bool */
	public bool $magic = false;


	public function __construct(string $name)
	{
		$this->name = $name;
	}


	/**
	 * @return PhpDocTextNode[] indexed by []
	 */
	public function getEffectiveDescription(Index $index, ClassLikeInfo $classLike): array
	{
		return $this->description;
	}
}
