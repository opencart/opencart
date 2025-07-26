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

			return new \Opencart\System\Engine\Action('startup/task.run');
		} else {
			return null;
		}
	}

	public function run() {
		if (isset($this->request->server['argv'])) {
			$argv = (array)$this->request->server['argv'];
		} else {
			$argv = [];
		}

		// Just displays the path to the file
		$script = array_shift($argv);

		// Get the arguments passed with the command
		$task = array_shift($argv);

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

		$this->response->setOutput($this->load->controller('task/' . $task, $args));
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
