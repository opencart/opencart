<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ExprInfo;


class ArgExprInfo
{
	public function __construct(
		public ?string $name,
		public ExprInfo $value,
	) {
	}
}
