<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte;

use ApiGen\Task\TaskHandlerFactory;


/**
 * @extends TaskHandlerFactory<LatteRenderTaskContext, LatteRenderTaskHandler>
 */
interface LatteRenderTaskHandlerFactory extends TaskHandlerFactory
{
	/**
	 * @param  LatteRenderTaskContext $context
	 */
	public function create(mixed $context): LatteRenderTaskHandler;
}
