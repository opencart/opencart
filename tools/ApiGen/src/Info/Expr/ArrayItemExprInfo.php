<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ExprInfo;


class ArrayItemExprInfo
{
	public function __construct(
		public ?ExprInfo $key,
		public ExprInfo $value,
	) {
	}
}
