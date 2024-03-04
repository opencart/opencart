<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte;

use ApiGen\Index\Index;
use ApiGen\Renderer\Latte\Template\ConfigParameters;


class LatteRenderTaskContext
{
	public function __construct(
		public Index $index,
		public ConfigParameters $config,
	) {
	}
}
