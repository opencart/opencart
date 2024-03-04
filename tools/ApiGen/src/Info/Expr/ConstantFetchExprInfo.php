<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ExprInfo;


class ConstantFetchExprInfo implements ExprInfo
{
	public function __construct(
		public string $name,
	) {
	}
}
