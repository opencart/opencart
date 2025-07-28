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

		return null;
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

				//fwrite(STDIN, '$output');
				//fwrite(STDIN, $output);

				if ($output instanceof \Exception) {
					$this->model_setting_task->editStatus($result['task_id'], 'failed');

					break;
				}

				sleep(1);
			}
		}
	}

	public function exception() {

	}

	public function usage() {
		$option = implode(' ', [
			'--username',
			'admin',
			'--email',
			'email@example.com',
			'--password',
			'password',
			'--http_server',
			'http://localhost/opencart/',
			'--db_driver',
			'mysqli',
			'--db_hostname',
			'localhost',
			'--db_username',
			'root',
			'--db_password',
			'pass',
			'--db_database',
			'opencart',
			'--db_port',
			'3306',
			'--db_prefix',
			'oc_'
		]);

		$output  = 'Usage:' . "\n";
		$output .= '======' . "\n\n";
		$output .= 'php cli_install.php install ' . $option . "\n\n";

		return $output;
	}
}
