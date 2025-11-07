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
 * php index.php start
 *
 * @example:
 *
 * php c://xampp/htdocs/opencart-master/upload/install/cli_install.php start
 */
class Cli extends \Opencart\System\Engine\Controller {
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
					if (stream_isatty(STDOUT)) {
						fwrite(STDOUT, $this->language->get('text_start') . "\n");
					}

					return new \Opencart\System\Engine\Action('marketplace/task.cli', $argv);

					break;
				case 'usage':
				default:
					return new \Opencart\System\Engine\Action('startup/task.usage', $argv);

					break;
			}
		}

		return null;
	}

	/**
	 * Usage
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function usage() {

	}

	public function end() {
		//if () {

		//}
	}
}
