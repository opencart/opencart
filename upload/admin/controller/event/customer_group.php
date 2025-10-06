<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Customer Group
 *
 * @package Opencart\Admin\Controller\Event
 */
class CustomerGroup extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new customer group list
	 *
	 * Called using admin/model/customer/customer_group/addCustomerGroup/after
	 * Called using admin/model/customer/customer_group/editCustomerGroup/after
	 * Called using admin/model/customer/customer_group/deleteCustomerGroup/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args): void {
		$task_data = [
			'code'   => 'customer_group',
			'action' => 'task/catalog/customer_group',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'customer_group',
			'action' => 'task/admin/customer_group',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);
	}
}
