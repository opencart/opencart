<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte\Template;

use ApiGen\Index\Index;


class IndexTemplate
{
	public function __construct(
		public Index $index,
		public ConfigParameters $config,
		public LayoutParameters $layout,
	) {
	}
}
