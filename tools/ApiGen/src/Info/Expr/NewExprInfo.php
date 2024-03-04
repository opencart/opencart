<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ClassLikeReferenceInfo;
use ApiGen\Info\ExprInfo;


class NewExprInfo implements ExprInfo
{
	/**
	 * @param ArgExprInfo[] $args
	 */
	public function __construct(
		public ClassLikeReferenceInfo $classLike,
		public array $args,
	) {
	}
}
