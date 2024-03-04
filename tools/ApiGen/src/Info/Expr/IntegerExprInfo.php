<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ExprInfo;

use function abs;
use function base_convert;


class IntegerExprInfo implements ExprInfo
{
	public function __construct(
		public int $value,
		public int $base,
		public string $raw,
	) {
	}


	public function toString(): string
	{
		if ($this->base === 10) {
			return (string) $this->value;
		}

		$sign = $this->value < 0 ? '-' : '';
		$base = [2 => '0b', 8 => '0', 16 => '0x'][$this->base];
		return $sign . $base . base_convert((string) abs($this->value), 10, $this->base);
	}
}
