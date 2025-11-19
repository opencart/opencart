<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Store
 *
 * @package Opencart\Admin\Controller\Event
 */
class Store extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new store list
     *
	 * model/setting/store/addStore
	 * model/setting/store/editStore
	 * model/setting/store/deleteStore
	 *
     * model/setting/store/addStore
	 * model/setting/store/editStore
	 * model/setting/store/deleteStore
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {





		$task_data = [
			'code'   => 'store',
			'action' => 'task/admin/store',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
