<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ExprInfo;


class UnaryOpExprInfo implements ExprInfo
{
	public function __construct(
		public string $op,
		public ExprInfo $expr,
	) {
	}
}
