<?php declare(strict_types = 1);

namespace ApiGen\Info;

use function strtolower;


class MethodReferenceInfo extends MemberReferenceInfo
{
	public string $nameLower;

	public function __construct(
		ClassLikeReferenceInfo $classLike,
		string $name,
	) {
		parent::__construct($classLike, $name);
		$this->nameLower = strtolower($name);
	}
}
