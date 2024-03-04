<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ExprInfo;


class TernaryExprInfo implements ExprInfo
{
	public function __construct(
		public ExprInfo $condition,
		public ?ExprInfo $if,
		public ExprInfo $else,
	) {
	}
}
