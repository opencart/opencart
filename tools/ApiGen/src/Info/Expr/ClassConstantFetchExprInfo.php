<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ClassLikeReferenceInfo;
use ApiGen\Info\ExprInfo;


class ClassConstantFetchExprInfo implements ExprInfo
{
	public function __construct(
		public ClassLikeReferenceInfo $classLike,
		public string $name,
	) {
	}
}
