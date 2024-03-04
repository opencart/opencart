<?php declare(strict_types = 1);

namespace ApiGen\Scheduler;

use ApiGen\Helpers;
use ApiGen\Task\Task;
use ApiGen\Task\TaskHandler;
use ApiGen\Task\TaskHandlerFactory;
use Composer\Autoload\ClassLoader;
use Nette\DI\Container;

use function dirname;
use function proc_close;
use function proc_open;
use function sprintf;
use function var_export;

use const PHP_BINARY;
use const PHP_OS_FAMILY;
use const STDERR;


/**
 * @template TTask of Task
 * @template TResult
 * @template TContext
 * @extends  WorkerScheduler<TTask, TResult>
 */
class ExecScheduler extends WorkerScheduler
{
	/** @var resource[] $workers indexed by [workerId] */
	protected array $workers = [];


	/**
	 * @param  class-string<Container>                                                 $containerClass
	 * @param  class-string<TaskHandlerFactory<TContext, TaskHandler<TTask, TResult>>> $handlerFactoryClass
	 * @param  TContext                                                                $context
	 */
	public function __construct(
		protected string $containerClass,
		protected array $containerParameters,
		protected string $handlerFactoryClass,
		protected mixed $context,
		int $workerCount,
	) {
		parent::__construct($workerCount);
	}


	protected function start(): void
	{
		$command = [
			PHP_BINARY,
			'--run',
			sprintf('require %s;', var_export(__DIR__ . '/worker.php', return: true)),
			'--',
			dirname(Helpers::classLikePath(ClassLoader::class), 2) . '/autoload.php',
			Helpers::classLikePath($this->containerClass),
			$this->containerClass,
			$this->handlerFactoryClass,
		];

		$descriptors = [
			PHP_OS_FAMILY === 'Windows' ? ['socket'] : ['pipe', 'r'],
			PHP_OS_FAMILY === 'Windows' ? ['socket'] : ['pipe', 'w'],
			STDERR,
		];

		for ($workerId = 0; $workerId < $this->workerCount; $workerId++) {
			$workerProcess = proc_open($command, $descriptors, $pipes);

			if ($workerProcess === false) {
				throw new \RuntimeException('Failed to start worker process, try running ApiGen with --workers 1');
			}

			$this->workers[$workerId] = $workerProcess;
			$this->workerWritableStreams[$workerId] = $pipes[0];
			$this->workerReadableStreams[$workerId] = $pipes[1];
			self::writeMessage($this->workerWritableStreams[$workerId], $this->containerParameters);
			self::writeMessage($this->workerWritableStreams[$workerId], $this->context);
		}
	}


	protected function stop(): void
	{
		foreach ($this->workers as $worker) {
			if (proc_close($worker) !== 0) {
				throw new \RuntimeException('Worker process crashed, try running ApiGen with --workers 1');
			}
		}
	}
}
