<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ExprInfo;

use function is_finite;
use function json_encode;
use function str_contains;

use const JSON_THROW_ON_ERROR;


class FloatExprInfo implements ExprInfo
{
	public function __construct(
		public float $value,
		public string $raw,
	) {
	}


	public function toString(): string
	{
		if (!is_finite($this->value)) {
			return (string) $this->value;
		}

		$json = json_encode($this->value, JSON_THROW_ON_ERROR);
		return str_contains($json, '.') ? $json : "$json.0";
	}
}
