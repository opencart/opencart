<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Task
 *
 * @package Opencart\Admin\Controller\Event
 */
class Task extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Starts task queue if not running
	 *
	 * model/setting/task.addTask/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$task_total = $this->model_setting_task->getTotalTasks(['filter_status' => 'processing']);

		if ($task_total) {
			return;
		}

		//shell_exec('php ' . DIR_APPLICATION . 'index.php start &');
	}
}