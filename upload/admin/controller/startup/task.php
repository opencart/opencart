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

		fwrite(STDOUT, '$task_total ' . $task_total . "\n");

		if ($task_total) {
			return;
		}



		$results = $this->model_setting_task->getTasks(['filter_status' => 'pending']);

		foreach ($results as $result) {
			$this->model_setting_task->editStatus($result['task_id'], 'processing');

			try {
				$output = $this->load->controller('task/' . $result['action'], $result['args']);

				// If task does not exist
				if ($output instanceof \Exception) {
					$this->model_setting_task->editStatus($result['task_id'], 'failed');

					break;
				}

				if (isset($output['error'])) {
					$this->model_setting_task->editStatus($result['task_id'], 'failed');

					fwrite(STDOUT, $output . "\n");

					break;
				}

				$this->model_setting_task->editStatus($result['task_id'], 'complete');

				sleep(1);
			} catch (\Exception $e) {
				$this->model_setting_task->editStatus($result['task_id'], 'failed');

				fwrite(STDOUT, $e . "\n");

				break;
			}
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
