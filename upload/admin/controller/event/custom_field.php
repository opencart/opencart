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
	 * Called using model/customer/custom_field/addCustomField/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function addCustomField(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$this->load->model('setting/custom_field');

		$results = $this->model_setting_custom_field->getCustomerGroups($output);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'customer_group.info',
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
	 * Called using model/customer/custom_field/editCustomField/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function editCustomField(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$this->load->model('setting/custom_field');

		$results = $this->model_setting_custom_field->getCustomerGroups($output);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'customer_group',
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
	 * Called using model/customer/custom_field/deleteCustomField/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function deleteCustomField(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$this->load->model('setting/custom_field');

		$results = $this->model_setting_custom_field->getCustomerGroups($args[0]['custom_field_id']);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'customer_group',
				'action' => 'task/catalog/customer_group',
				'args'   => []
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
