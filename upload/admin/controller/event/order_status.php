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
	 * model/localisation/order_status/addOrderStatus/after
	 * model/localisation/order_status/editOrderStatus/after
	 * model/localisation/order_status/deleteOrderStatus/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'order_status',
			'action' => 'task/admin/order_status',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
