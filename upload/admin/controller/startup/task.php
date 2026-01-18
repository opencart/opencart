<?php
namespace Opencart\Admin\Controller\Startup;
/**
 * Class Task
 *
 * @package Opencart\Admin\Controller\Event
 */
class Task extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Register shutdown function  to start any tasks in the queue.
	 *
	 * Called using model/setting/task.addTask/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(): void {
		register_shutdown_function([$this, 'start']);
	}

	/*
	 * Start
	 *
	 * Starts task list by command line if queue not running.
	 */
	public function start() {
		$this->load->model('setting/task');

		$task_total = $this->model_setting_task->getTotalTasks(['filter_status' => 'processing']);

		if ($task_total) {
			return;
		}

		if (strtoupper(substr(php_uname(), 0, 3)) == 'WIN') {
			$handle = popen(DIR_APPLICATION . 'index.php start > ' . DIR_LOGS . 'command.log 2>&1', 'r');

			$read = fread($handle, 4096);

			pclose($handle);

			//pclose(popen('start /B php ' . DIR_APPLICATION . 'index.php start', 'r'));
		} else {
			shell_exec('php ' . DIR_APPLICATION . 'index.php start > /dev/null 2>&1 &');
		}
	}
}