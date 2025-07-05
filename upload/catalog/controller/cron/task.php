<?php
namespace Opencart\Catalog\Controller\Cron;
/**
 * Class Task
 *
 * @package Opencart\Catalog\Controller\Cron
 */
class Task extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param int    $cron_id
	 * @param string $code
	 * @param string $cycle
	 * @param string $date_added
	 * @param string $date_modified
	 *
	 * @return void
	 */
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
		// Extension
		$this->load->model('setting/task');

		$results = $this->model_setting_task->getTasks();

		foreach ($results as $result) {
			$this->load->controller($result['route'], $result['args']);

			shell_exec($command);



		}


		if (isset($this->request->server['argv'])) {
			$argv = $this->request->server['argv'];
		} else {
			$argv = [];
		}

		// Just displays the path to the file
		$script = array_shift($argv);

		// Get the arguments passed with the command
		$command = array_shift($argv);

		switch ($command) {
			case 'install':
				$output = $this->install($argv);
				break;
			case 'usage':
			default:
				$output = $this->usage();
				break;
		}
		foreach ($results as $result) {
			shell_exec($command);
		}
	}
}
