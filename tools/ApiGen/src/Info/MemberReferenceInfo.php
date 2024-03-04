<?php declare(strict_types = 1);

namespace ApiGen\Info;


abstract class MemberReferenceInfo
{
	public function __construct(
		public ClassLikeReferenceInfo $classLike,
		public string $name,
	) {
	}
}
