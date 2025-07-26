<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Order Status
 *
 * @package Opencart\Admin\Controller\Event
 */
class OrderStatus extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new order_status list
	 *
	 * model/localisation/order_status/addOrderStatus
	 * model/localisation/order_status/editOrderStatus
	 * model/localisation/order_status/deleteOrderStatus
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$tasks = [];

		$tasks[] = [
			'code'   => 'order_status',
			'action' => 'catalog/order_status',
			'args'   => []
		];

		$tasks[] = [
			'code'   => 'order_status',
			'action' => 'admin/order_status',
			'args'   => []
		];

		$this->load->model('setting/task');

		foreach ($tasks as $task) {
			$this->model_setting_task->addTask($task);
		}
	}
}
