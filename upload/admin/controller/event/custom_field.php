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

		$this->load->model('customer/custom_field');

		$results = $this->model_customer_custom_field->getCustomerGroups($output);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'customer_group.info.' . $result['customer_group_id'],
				'action' => 'task/catalog/customer_group',
				'args'   => ['customer_group_id' => $result['customer_group_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/**
	 * Edit Custom Field
	 *
	 * Adds task to generate new customer group data with the updated customer fields.
	 *
	 * Trigger model/customer/custom_field/editCustomField/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function editCustomField(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$this->load->model('customer/custom_field');

		$results = $this->model_customer_custom_field->getCustomerGroups($args[0]);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'customer_group.info.' . $result['customer_group_id'],
				'action' => 'task/catalog/customer_group',
				'args'   => []
			];

			$this->model_setting_task->addTask($task_data);
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
		$this->load->model('setting/task');

		$this->load->model('customer/custom_field');

		$results = $this->model_customer_custom_field->getCustomerGroups($args[0]);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'customer_group.delete.' . $args[0],
				'action' => 'task/catalog/customer_group',
				'args'   => ['customer_group_id' => $args[0]]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
