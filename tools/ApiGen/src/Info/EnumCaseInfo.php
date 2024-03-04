<?php declare(strict_types = 1);

namespace ApiGen\Info;


class EnumCaseInfo extends MemberInfo
{
	public function __construct(
		string $name,
		public ?ExprInfo $value,
	) {
		parent::__construct($name);
	}
}
