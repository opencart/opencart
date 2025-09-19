<?php
namespace Opencart\Admin\Controller\Startup;
/**
 * Class Task
 *
 * @package Opencart\Admin\Controller\Startup
 *
 * Command line tool for managing tasks in opencart
 *
 * Usage:
 *
 * php cli_install.php start
 *
 * @example:
 *
 * php c://xampp/htdocs/opencart-master/upload/install/cli_install.php start
 */
class Task extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function index() {
		if (php_sapi_name() == 'cli') {
			if (isset($this->request->server['argv'])) {
				$argv = (array)$this->request->server['argv'];
			} else {
				$argv = [];
			}

			// Just displays the path to the file
			$script = array_shift($argv);

			// Get the arguments passed with the command
			$command = array_shift($argv);

			switch ($command) {
				case 'start':
					return new \Opencart\System\Engine\Action('startup/task.start', $argv);

					break;
				case 'usage':
				default:
					return new \Opencart\System\Engine\Action('startup/task.usage', $argv);

					break;
			}
		}

		return null;
	}

	public function start() {
		$this->load->model('setting/task');

		$task_total = $this->model_setting_task->getTotalTasks(['filter_status' => 'processing']);

		if ($task_total) {
			return;
		}

		$filter_data = [
			'filter_status' => 'pending',
			'start'         => 0,
			'limit'         => 1
		];

		$results = $this->model_setting_task->getTasks($filter_data);

		while ($results) {
			$task = array_shift($results);

			$this->model_setting_task->editStatus($task['task_id'], 'processing');

			try {
				$output = $this->load->controller($task['action'], $task['args']);
			} catch (\Exception $e) {
				$output = $e;
			}

			if ($output instanceof \Exception) {
				$output = ['error' => $output->getMessage() . ' in ' . $output->getFile() . ' on line ' . $output->getLine()];
			}

			// If task does not exist
			if (isset($output['error'])) {
				$this->model_setting_task->editStatus($task['task_id'], 'failed', $output);

				fwrite(STDOUT, $output['error'] . "\n");
			}

			if (isset($output['success'])) {
				$this->model_setting_task->editStatus($task['task_id'], 'complete', $output);

				fwrite(STDOUT, $output['success'] . "\n");

				$this->model_setting_task->deleteTask($task['task_id']);

				$next = $this->model_setting_task->getTasks($filter_data);

				if ($next) {
					array_push($results, $next[0]);
				}
			}

			sleep(1);
		}
	}

	public function usage() {
		$results = oc_directory_read(DIR_CATALOG);

	}
}
