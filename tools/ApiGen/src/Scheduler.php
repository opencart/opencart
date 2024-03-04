<?php declare(strict_types = 1);

namespace ApiGen;

use ApiGen\Task\Task;


/**
 * @template TTask of Task
 * @template TResult
 */
interface Scheduler
{
	/**
	 * @param  TTask $task
	 */
	public function schedule(Task $task): void;


	/**
	 * @return iterable<TTask, TResult>
	 */
	public function process(): iterable;
}
