<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Customer Group
 *
 * @package Opencart\Admin\Controller\Event
 */
class CustomerGroup extends \Opencart\System\Engine\Controller {
	/**
	 * add Customer Group
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
	public function index(string &$route, array &$args, &$output): void {
		$pos = strpos($route, '.');

		if ($pos == false) {
			return;
		}

		$method = substr($route, 0, $pos);

		$callable = [$this, $method];

		if (is_callable($callable)) {
			$callable($route, $args, $output);
		}
	}

	public function addCustomerGroup(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'customer_group.info.' . $output,
			'action' => 'task/catalog/customer_group.info',
			'args'   => ['customer_group_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function editCustomerGroup(string &$route, array &$args, &$output): void {
		// Catalog
		$task_data = [
			'code'   => 'customer_group',
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

	public function deleteCustomerGroup(string &$route, array &$args, &$output): void {
		// Catalog
		$task_data = [
			'code'   => 'customer_group',
			'action' => 'task/catalog/customer_group.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'customer_group',
			'action' => 'task/catalog/customer_group.delete',
			'args'   => ['customer_group_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Admin
		/*
		$task_data = [
			'code'   => 'country',
			'action' => 'task/admin/country.delete',
			'args'   => ['country_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);
		*/
	}
}
