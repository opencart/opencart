<?php declare(strict_types = 1);

namespace ApiGen\Info\Expr;

use ApiGen\Info\ExprInfo;

use function mb_ord;
use function ord;
use function preg_match;
use function preg_replace_callback;
use function sprintf;
use function strlen;


class StringExprInfo implements ExprInfo
{
	public function __construct(
		public string $value,
		public ?string $raw = null,
	) {
	}


	public function toString(): string
	{
		$utf8 = (bool) preg_match('##u', $this->value);
		$pattern = $utf8 ? '#[\p{C}"\'\\\\]#u' : '#[\x00-\x1F\x7F-\xFF"\'\\\\]#';
		$special = ["\r" => '\r', "\n" => '\n', "\t" => '\t', '\\' => '\\\\', '"' => '\\"', '\'' => '\''];

		$s = preg_replace_callback(
			$pattern,
			function ($m) use ($special) {
				if (isset($special[$m[0]])) {
					return $special[$m[0]];

				} elseif (strlen($m[0]) === 1) {
					return sprintf('\x%02X', ord($m[0]));

				} else {
					return sprintf('\u{%X}', mb_ord($m[0]));
				}
			},
			$this->value,
			-1,
			$count,
		);

		$quote = $count === 0 ? "'" : '"';
		return $quote . $s . $quote;
	}
}
