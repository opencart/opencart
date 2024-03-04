<?php declare(strict_types = 1);

namespace ApiGen\Scheduler;

use ApiGen\Scheduler;
use ApiGen\Task\Task;
use ApiGen\Task\TaskHandler;
use ApiGen\Task\TaskHandlerFactory;
use Nette\DI\Container;

use function extension_loaded;
use function function_exists;

use const PHP_SAPI;


class SchedulerFactory
{
	public function __construct(
		protected Container $container,
		protected int $workerCount,
	) {
	}


	/**
	 * @template TTask of Task
	 * @template TResult
	 * @template TContext
	 *
	 * @param    class-string<TaskHandlerFactory<TContext, TaskHandler<TTask, TResult>>> $handlerFactoryType
	 * @param    TContext                                                                $context
	 * @return   Scheduler<TTask, TResult>
	 */
	public function create(string $handlerFactoryType, mixed $context): Scheduler
	{
		if ($this->workerCount > 1 && PHP_SAPI === 'cli') {
			if (extension_loaded('pcntl')) {
				$handler = $this->createHandler($handlerFactoryType, $context);
				return new ForkScheduler($handler, $this->workerCount);

			} elseif (function_exists('proc_open')) {
				return new ExecScheduler($this->container::class, $this->container->parameters, $handlerFactoryType, $context, $this->workerCount);
			}
		}

		$handler = $this->createHandler($handlerFactoryType, $context);
		return new SimpleScheduler($handler);
	}


	/**
	 * @template TTask of Task
	 * @template TResult
	 * @template TContext
	 *
	 * @param    class-string<TaskHandlerFactory<TContext, TaskHandler<TTask, TResult>>> $handlerFactoryType
	 * @param    TContext                                                                $context
	 * @return   TaskHandler<TTask, TResult>
	 */
	private function createHandler(string $handlerFactoryType, mixed $context): TaskHandler
	{
		$factory = $this->container->getByType($handlerFactoryType) ?? throw new \LogicException();
		return $factory->create($context);
	}
}
