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
	public function index() {
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
			$command = array_shift($argv);

			fwrite(STDIN, $command);


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
	}

	public function start() {
		$this->load->model('setting/task');

		$task_total = $this->model_setting_task->getTotalTasks(['filter_status' => 'processing']);

		if (!$task_total) {
			$results = $this->model_setting_task->getTasks(['filter_status' => 'pending']);

			foreach ($results as $result) {
				$this->model_setting_task->editStatus($result['task_id'], 'processing');

				$pos = strpos($result['action'], '/');

				if (substr($result['action'], 0, $pos + 1) == 'admin/') {
					$application = DIR_APPLICATION;
				} else {
					$application = DIR_OPENCART;
				}

				$output = $this->load->controller('task/' . substr($result['action'], $pos + 1), $result['args']);

				fwrite(STDIN, '$output');
				fwrite(STDIN, $output);

				if ($output instanceof \Exception) {
					$this->model_setting_task->editStatus($result['task_id'], 'failed');

					break;
				}

				sleep(1);
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

		echo $message;
	}
}
