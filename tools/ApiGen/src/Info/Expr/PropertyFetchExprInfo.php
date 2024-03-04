<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ExprInfo;


class PropertyFetchExprInfo implements ExprInfo
{
	public function __construct(
		public ExprInfo $expr,
		public ExprInfo|string $property,
	) {
	}
}
