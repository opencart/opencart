<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ExprInfo;


class BooleanExprInfo implements ExprInfo
{
	public function __construct(
		public bool $value,
	) {
	}


	public function toString(): string
	{
		return $this->value ? 'true' : 'false';
	}
}
