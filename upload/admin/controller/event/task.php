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
	 * model/marketplace/task/addTask/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {




		$this->load->model('setting/task');

		$this->model_setting_task->isRunning();

		$this->model_setting_task->isRunning();
	}
}
