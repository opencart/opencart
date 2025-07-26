<?php
namespace Opencart\Admin\Controller\Startup;
/**
 * Class Task
 *
 * @package Opencart\Admin\Controller\Startup
 */
class Task extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function index(): ?\Opencart\System\Engine\Action {
		if (php_sapi_name() == 'cli') {
			set_exception_handler([$this, 'exception']);

			if (isset($this->request->server['argv'])) {
				$argv = (array)$this->request->server['argv'];
			} else {
				$argv = [];
			}

			// Just displays the path to the file
			$script = array_shift($argv);

			// Get the arguments passed with the command
			$action = array_shift($argv);

			$args = [];

			// Turn args into an array
			for ($i = 0; $i < count($argv); $i++) {
				if (substr($argv[$i], 0, 2) == '--') {
					$key = substr($argv[$i], 2);

					// If the next line also starts with -- we need to fill in a null value for the current one
					if (isset($argv[$i + 1]) && substr($argv[$i + 1], 0, 2) != '--') {
						$args[$key] = $argv[$i + 1];

						// Skip the counter by 2
						$i++;
					} else {
						$args[$key] = '';
					}
				}
			}

			return new \Opencart\System\Engine\Action('startup/task.run', $args);
		} else {
			return null;
		}
	}

	public function start() {
		$task_total = $this->model_setting_task->getTotalTasks(['filter_status' => 'processing']);

		if (!$task_total) {
			$task_data =  [];

			$this->load->model('setting/task');

			$results = $this->model_setting_task->getTasks(['filter_status' => 'pending']);

			foreach ($results as $result) {
				$this->model_setting_task->editStatus($result['task_id'], 'processing');

				$pos = strpos($result['action'], '/');

				$path = substr($result['action'], 0, $pos + 1);

				if ($path == 'admin/') {
					$application = DIR_APPLICATION;
				} else {
					$application = DIR_OPENCART;
				}

				$argv = '';

				$args = json_decode($result['args'], true);

				foreach ($args as $key => $value) {
					$argv .= ' --' . $key . ' ' . escapeshellarg($value);
				}

				$output = shell_exec('php ' . $application . 'index.php ' . substr($result['action'], $pos + 1) . $argv);

				fwrite(STDIN, $output);

				sleep(1);

				if ($output) {
					$this->model_setting_task->editStatus($result['task_id'], 'failed');

					break;
				}
			}
		}
	}

	/*
	 *
	 *
	 * */
	public function run(string $command, array $argv = []) {

		$this->response->setOutput($this->load->controller('task/' . $command, $args));


		/*
		$pos = strpos($task_info['action'], '/');

		$path = substr($task_info['action'], 0, $pos + 1);

		$task = substr($task_info['action'], $pos + 1);

		if ($path == 'admin/') {
			$output = shell_exec('php ' . DIR_APPLICATION . 'index.php ' . $task . ' --page 1');
		}

		if ($path == 'catalog/') {
			$output = shell_exec('php ' . DIR_OPENCART . 'index.php ' . $task . ' --page 1');
		}*/
	}

	public function exception(object $e): void {
		$message = $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();

		if ($this->config->get('error_log')) {
			$this->log->write($message);
		}

		echo $message;
	}

	public function usage() {
		$results = oc_directory_read(DIR_CATALOG);


	}
}
