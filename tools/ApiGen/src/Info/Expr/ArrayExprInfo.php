<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ExprInfo;


class ArrayExprInfo implements ExprInfo
{
	/**
	 * @param ArrayItemExprInfo[] $items
	 */
	public function __construct(
		public array $items,
	) {
	}
}
