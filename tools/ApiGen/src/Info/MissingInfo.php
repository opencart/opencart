<?php declare(strict_types = 1);

namespace ApiGen\Info;


class MissingInfo extends ClassLikeInfo
{
	public function __construct(
		NameInfo $name,
		public NameInfo $referencedBy,
	) {
		parent::__construct(
			$name,
			primary: false,
		);
	}
}
