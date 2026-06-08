<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Custom Field
 *
 * @package Opencart\Admin\Controller\Event
 */
class CustomField extends \Opencart\System\Engine\Controller {
	/**
	 * Add Custom Field
	 *
	 * Adds task to generate new customer group data with the updated customer fields.
	 *
	 * Trigger model/customer/custom_field/addCustomField/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function addCustomField(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$customer_group_ids = [];

		if (isset($args[1]['custom_field_customer_group'])) {
			$customer_group_ids = (array)$args[1]['custom_field_customer_group'];
		}

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			foreach ($customer_group_ids as $customer_group_id) {
				$task_data = [
					'code'   => 'customer_group.info.' . $store_id . '.' . $customer_group_id,
					'action' => 'task/catalog/customer_group',
					'args'   => [
						'customer_group_id' => $customer_group_id,
						'store_id'          => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}

	/**
	 * Edit Custom Field
	 *
	 * Adds task to generate new customer group data with the updated customer fields.
	 *
	 * Trigger model/customer/custom_field/editCustomField/before
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function editCustomField(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$customer_group_ids = [];

		if (isset($args[1]['custom_field_customer_group'])) {
			$customer_group_ids = (array)$args[1]['custom_field_customer_group'];
		}

		// Remove Customer Groups ID's
		$this->load->model('customer/custom_field');

		$customer_group_ids = array_unique(array_merge(array_column($this->model_customer_custom_field->getCustomerGroups($args[0]), 'customer_group_id'), $customer_group_ids));

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			foreach ($customer_group_ids as $customer_group_id) {
				$task_data = [
					'code'   => 'customer_group.info.' . $store_id . '.' . $customer_group_id,
					'action' => 'task/catalog/customer_group',
					'args'   => [
						'customer_group_id' => $customer_group_id,
						'store_id'          => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}

	/**
	 * Delete Custom Field
	 *
	 * Adds task to generate new customer group data with the updated customer fields.
	 *
	 * Trigger model/customer/custom_field/deleteCustomField/before
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function deleteCustomField(string &$route, array &$args, &$output): void {
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$this->load->model('customer/custom_field');

		$results = $this->model_customer_custom_field->getCustomerGroups($args[0]);

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			foreach ($results as $result) {
				$task_data = [
					'code'   => 'customer_group.info.' . $store_id . '.' . $result['customer_group_id'],
					'action' => 'task/catalog/customer_group',
					'args'   => [
						'customer_group_id' => $result['customer_group_id'],
						'store_id'          => $store_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
	}
}
