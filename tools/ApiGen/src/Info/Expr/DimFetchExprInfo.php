<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ExprInfo;


class DimFetchExprInfo implements ExprInfo
{
	public function __construct(
		public ExprInfo $expr,
		public ExprInfo $dim,
	) {
	}
}
