<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Customer Group
 *
 * @package Opencart\Admin\Controller\Event
 */
class CustomerGroup extends \Opencart\System\Engine\Controller {
	/**
	 * Add Customer Group
	 *
	 * Adds task to generate new customer group data.
	 *
	 * Triggered using admin/model/customer/customer_group/addCustomerGroup/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function addCustomerGroup(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'customer_group.list',
			'action' => 'task/catalog/customer_group.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'customer_group.info.' . $output,
			'action' => 'task/catalog/customer_group.info',
			'args'   => ['customer_group_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Edit Customer Group
	 *
	 * Adds task to generate new customer group data.
	 *
	 * Triggered using admin/model/customer/customer_group/editCustomerGroup/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function editCustomerGroup(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'customer_group.list',
			'action' => 'task/catalog/customer_group.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'customer_group.info.' . $args[0],
			'action' => 'task/catalog/customer_group.info',
			'args'   => ['customer_group_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);

		// Admin
		/*
		$task_data = [
			'code'   => 'customer_group',
			'action' => 'task/admin/customer_group.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'customer_group',
			'action' => 'task/admin/customer_group.info',
			'args'   => ['customer_group_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);
		*/
	}

	/**
	 * Delete Customer Group
	 *
	 * Adds task to generate new customer group data.
	 *
	 * Triggered using admin/model/customer/customer_group/deleteCustomerGroup/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function deleteCustomerGroup(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'customer_group.list',
			'action' => 'task/catalog/customer_group.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'customer_group.delete.' . $args[0],
			'action' => 'task/catalog/customer_group.delete',
			'args'   => ['customer_group_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);

		/*
		// Admin
		$task_data = [
			'code'   => 'country',
			'action' => 'task/admin/customer_group.delete',
			'args'   => ['country_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);
		*/
	}
}
