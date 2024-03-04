<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ExprInfo;


class NullExprInfo implements ExprInfo
{
	public function toString(): string
	{
		return 'null';
	}
}
