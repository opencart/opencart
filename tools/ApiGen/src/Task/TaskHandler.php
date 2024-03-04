<?php declare(strict_types = 1);

namespace ApiGen\Task;


/**
 * @template-contravariant T of Task
 * @template-covariant     R
 */
interface TaskHandler
{
	/**
	 * @param  T $task
	 * @return R
	 */
	public function handle(Task $task): mixed;
}
