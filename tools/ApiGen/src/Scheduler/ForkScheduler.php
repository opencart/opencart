<?php declare(strict_types = 1);

namespace ApiGen\Scheduler;

use ApiGen\Task\Task;
use ApiGen\Task\TaskHandler;

use function fclose;
use function pcntl_fork;
use function pcntl_waitpid;
use function pcntl_wexitstatus;
use function pcntl_wifexited;
use function pcntl_wifsignaled;
use function pcntl_wtermsig;
use function stream_socket_pair;

use const STREAM_IPPROTO_IP;
use const STREAM_PF_UNIX;
use const STREAM_SOCK_STREAM;


/**
 * @template TTask of Task
 * @template TResult
 * @extends  WorkerScheduler<TTask, TResult>
 */
class ForkScheduler extends WorkerScheduler
{
	/** @var int[] $workers indexed by [workerId] */
	protected array $workers = [];


	/**
	 * @param TaskHandler<TTask, TResult> $handler
	 */
	public function __construct(
		protected TaskHandler $handler,
		int $workerCount,
	) {
		parent::__construct($workerCount);
	}


	protected function start(): void
	{
		for ($workerId = 0; $workerId < $this->workerCount; $workerId++) {
			$pair = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);

			if ($pair === false) {
				throw new \RuntimeException('Failed to create socket pair, try running ApiGen with --workers 1');
			}

			$pid = pcntl_fork();

			if ($pid < 0) {
				throw new \RuntimeException('Failed to fork process, try running ApiGen with --workers 1');

			} elseif ($pid === 0) {
				fclose($pair[0]);
				self::workerLoop($this->handler, $pair[1], $pair[1]);
				exit(0);

			} else {
				fclose($pair[1]);
				$this->workers[$workerId] = $pid;
				$this->workerReadableStreams[$workerId] = $pair[0];
				$this->workerWritableStreams[$workerId] = $pair[0];
			}
		}
	}


	protected function stop(): void
	{
		foreach ($this->workerWritableStreams as $stream) {
			fclose($stream);
		}

		foreach ($this->workers as $pid) {
			pcntl_waitpid($pid, $status);

			if (pcntl_wifexited($status)) {
				if (($exitCode = pcntl_wexitstatus($status)) !== 0) {
					throw new \RuntimeException("Worker with PID $pid exited with code $exitCode, try running ApiGen with --workers 1");
				}

			} elseif (pcntl_wifsignaled($status)) {
				$signal = pcntl_wtermsig($status);
				throw new \RuntimeException("Worker with PID $pid was killed by signal $signal, try running ApiGen with --workers 1");

			} else {
				throw new \LogicException('Invalid worker state');
			}
		}
	}
}
