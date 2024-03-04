<?php declare(strict_types = 1);

namespace ApiGen\Scheduler;

use ApiGen\Scheduler;
use ApiGen\Task\Task;
use ApiGen\Task\TaskHandler;
use SplQueue;


/**
 * @template   TTask of Task
 * @template   TResult
 * @implements Scheduler<TTask, TResult>
 */
class SimpleScheduler implements Scheduler
{
	/** @var SplQueue<TTask>  */
	protected SplQueue $tasks;


	/**
	 * @param TaskHandler<TTask, TResult> $handler
	 */
	public function __construct(
		protected TaskHandler $handler,
	) {
		$this->tasks = new SplQueue();
	}


	/**
	 * @param  TTask $task
	 */
	public function schedule(Task $task): void
	{
		$this->tasks->enqueue($task);
	}


	/**
	 * @return iterable<TTask, TResult>
	 */
	public function process(): iterable
	{
		while (!$this->tasks->isEmpty()) {
			$task = $this->tasks->dequeue();
			$result = $this->handler->handle($task);
			yield $task => $result;
		}
	}
}
