<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Customer Group
 *
 * Generates customer group data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class CustomerGroup extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate customer group list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
     */
	public function index(array $args = []): array {
		$this->load->language('task/admin/customer_group');

		$customer_group_data = [];

		$this->load->model('customer/customer_group');

		$customer_groups = $this->model_customer_customer_group->getCustomerGroups();

		foreach ($customer_groups as $customer_group) {
			$customer_group_data[] = $customer_group + ['description' => $this->model_customer_customer_group->getDesciptions($customer_group['customer_group_id'])];
		}

		$directory = DIR_APPLICATION . 'view/data/customer/';
		$filename = 'customer_group.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($customer_group_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Info
	 *
	 * Generate JSON customer group information file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/admin/customer_group');

		if (!array_key_exists('customer_group_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('customer/customer_group');

		$customer_group_info = $this->model_customer_customer_group->getCustomerGroup((int)$args['customer_group_id']);

		if (!$customer_group_info) {
			return ['error' => $this->language->get('error_customer_group')];
		}

		$custom_field_data = [];

		$this->load->model('customer/custom_field');

		$custom_fields = $this->model_customer_custom_field->getCustomFields(['filter_customer_group_id' => $customer_group_info['customer_group_id']]);

		foreach ($custom_fields as $custom_field) {
			$custom_field_data[] = $custom_field + ['description' => $this->model_customer_custom_field->getDesciptions($custom_field['custom_field_id'])];
		}

		$directory = DIR_APPLICATION . 'view/data/customer/';
		$filename = 'customer_group-' . $customer_group_info['customer_group_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($customer_group_info + ['description' => $this->model_customer_customer_group->getDescriptions($customer_group_info['customer_group_id'])] + ['custom_field' => $custom_field_data]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $customer_group_info['name'])];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON customer group files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/customer_group');

		$directory = DIR_APPLICATION . 'view/data/customer/';
		$file = $directory . 'customer_group.json';

		if (is_file($file)) {
			unlink($file);
		}

		$files = oc_directory_read($directory, false, '/customer_group-\d+\.json$/');

		foreach ($files as $file) {
			unlink($file);
		}
		
		return ['success' => $this->language->get('text_clear')];
	}
}
