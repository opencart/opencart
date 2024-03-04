<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte\Template;

use ApiGen\Index\Index;


class SitemapTemplate
{
	public function __construct(
		public Index $index,
		public ConfigParameters $config,
	) {
	}
}
