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
		// Admin
		$task_data = [
			'code'   => 'admin.customer_group',
			'action' => 'task/admin/customer_group',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'admin.customer_group.info.' . $output,
			'action' => 'task/admin/customer_group.info',
			'args'   => ['customer_group_id' => $output]
		];

		$this->model_setting_task->addTask($task_data);

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'customer_group.list.' . $store_id,
				'action' => 'task/catalog/customer_group',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'customer_group.info.' . $store_id . '.' . $output,
				'action' => 'task/catalog/customer_group.info',
				'args'   => [
					'customer_group_id' => $output,
					'store_id'          => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
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
	public function editCustomerGroup(string &$route, array &$args): void {
		// Admin
		$task_data = [
			'code'   => 'admin.customer_group',
			'action' => 'task/admin/customer_group',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'admin.customer_group.info.' . $args[0],
			'action' => 'task/admin/customer_group.info',
			'args'   => ['customer_group_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'customer_group.list.' . $store_id,
				'action' => 'task/catalog/customer_group.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'customer_group.info.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/customer_group.info',
				'args'   => [
					'customer_group_id' => $args[0],
					'store_id'          => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
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
		// Admin
		$task_data = [
			'code'   => 'admin.customer_group',
			'action' => 'task/admin/customer_group',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'admin.customer_group',
			'action' => 'task/admin/customer_group.delete',
			'args'   => ['customer_group_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'customer_group.list.' . $store_id,
				'action' => 'task/catalog/customer_group.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'customer_group.delete.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/customer_group.delete',
				'args'   => [
					'customer_group_id' => $args[0],
					'store_id'          => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
