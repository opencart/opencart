<?php declare(strict_types = 1);

namespace ApiGen\Task;


/**
 * @template           TContext
 * @template-covariant THandler of TaskHandler<never, mixed>
 */
interface TaskHandlerFactory
{
	/**
	 * @param  TContext $context
	 * @return THandler
	 */
	public function create(mixed $context): TaskHandler;
}
