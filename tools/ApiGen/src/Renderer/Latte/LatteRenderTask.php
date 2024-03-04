<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte;

use ApiGen\Task\Task;


class LatteRenderTask implements Task
{
	public function __construct(
		public LatteRenderTaskType $type,
		public string $key = '',
	) {
	}
}
