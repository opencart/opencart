<?php
namespace Opencart\Admin\Controller\Startup;
/**
 * Class Task
 *
 * @package Opencart\Admin\Controller\Startup
 *
 * Command line tool for installing opencart
 * Original Author: Vineet Naik <vineet.naik@kodeplay.com> <naikvin@gmail.com>
 * Updated and maintained by OpenCart
 * (Currently tested on linux only)
 *
 * Usage:
 *
 * php cli_install.php start
 *
 * @example:
 *
 * php c://xampp/htdocs/opencart-master/upload/install/cli_install.php install --username admin --password --email email@example.com --http_server http://localhost/opencart-master/upload/ --language en-gb --db_driver mysqli --db_hostname localhost --db_username root --db_database opencart-master --db_port 3306 --db_prefix oc_
 */
class Task extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function index(): ?\Opencart\System\Engine\Action {
		if (php_sapi_name() == 'cli') {
			//set_exception_handler([$this, 'exception']);

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
				$output = $this->load->controller('task/' . $task['action'], $task['args']);
			} catch (\Exception $e) {
				$output = ['error' => $e->getMessage()];
			}

			// If task does not exist
			if ($output instanceof \Exception) {
				$this->model_setting_task->editStatus($task['task_id'], 'failed', $output);

				fwrite(STDOUT, $output['error'] . "\n");

				break;
			}

			if (isset($output['error'])) {
				$this->model_setting_task->editStatus($task['task_id'], 'failed', $output);

				fwrite(STDOUT, $output['error'] . "\n");

				break;
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

	public function exception(object $e): void {
		$message = $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();

		if ($this->config->get('error_log')) {
			$this->log->write($message);
		}

		fwrite(STDOUT, $message . "\n");
	}
}
