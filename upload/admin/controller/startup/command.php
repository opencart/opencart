<?php
namespace Opencart\Admin\Controller\Startup;
/**
 * Class Command
 *
 * @package Opencart\Admin\Controller\Startup
 */
class Command extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function index(): ?\Opencart\System\Engine\Action {
		if (php_sapi_name() == 'cli') {
			//set_exception_handler([$this, 'exception']);

			return new \Opencart\System\Engine\Action('startup/command.run');
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
		$command = array_shift($argv);

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

		$output = $this->load->controller('cli/' . $command, $args);

		$this->response->setOutput($output);
	}

	public function exception() {

	}
}
