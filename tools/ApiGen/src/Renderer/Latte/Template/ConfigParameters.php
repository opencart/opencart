<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte\Template;


class ConfigParameters
{
	public function __construct(
		public string $title,
		public string $version,
	) {
	}
}
