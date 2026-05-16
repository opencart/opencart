<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Customer Group
 *
 * Generates customer group information for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class CustomerGroup extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate customer group task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/customer_group');

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'customer_group.list.' . $store_id,
				'action' => 'task/catalog/customer_group.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate country list by store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/customer_group');

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		$customer_group_data = [];

		$this->load->model('setting/setting');
		$this->load->model('customer/customer_group');

		$customer_group_ids = $this->model_setting_setting->getValue('config_customer_group_list', $store_info['store_id']);

		if ($customer_group_ids) {
			foreach ($customer_group_ids as $customer_group_id) {
				$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($customer_group_id);

				if ($customer_group_info) {
					$description_data = [];

					$descriptions = $this->model_customer_customer_group->getDescriptions($customer_group_info['customer_group_id']);

					foreach ($descriptions as $code => $description) {
						$description_data[$code] = [
							'name'        => $description['name'],
							'description' => $description['description']
						];
					}

					$customer_group_data[] = [
						'customer_group_id' => $customer_group_info['customer_group_id'],
						'description'       => $description_data,
						'sort_order'        => $customer_group_info['sort_order']
					];
				}
			}
		}

		$sort_order = [];

		foreach ($customer_group_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $customer_group_data);

		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/customer/';
		$filename = 'customer_group.yaml';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, oc_yaml_encode($customer_group_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'])];
	}

	/**
	 * Info
	 *
	 * Generate customer group information.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/customer_group');

		if (!array_key_exists('customer_group_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('customer/customer_group');

		$customer_group_info = $this->model_customer_customer_group->getCustomerGroup((int)$args['customer_group_id']);

		if (!$customer_group_info) {
			return ['error' => $this->language->get('error_customer_group')];
		}

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'customer_group.info.' . $store_id,
				'action' => 'task/catalog/customer_group._info',
				'args'   => [
					'customer_group_id' => $customer_group_info['customer_group_id'],
				    'store_id'          => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_info'), $customer_group_info['name'])];
	}

	/**
	 * Info
	 *
	 * Generate customer group information.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function _info(array $args = []): array {
		$this->load->language('task/catalog/customer_group');

		if (!array_key_exists('customer_group_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		$this->load->model('customer/customer_group');

		$customer_group_info = $this->model_customer_customer_group->getCustomerGroup((int)$args['customer_group_id']);

		if (!$customer_group_info) {
			return ['error' => $this->language->get('error_customer_group')];
		}

		// Description
		$description_data = [];

		$descriptions = $this->model_customer_customer_group->getDescriptions($customer_group_info['customer_group_id']);

		foreach ($descriptions as $code => $description) {
			$description_data[$code] = [
				'name'        => $description['name'],
				'description' => $description['description']
			];
		}

		// Custom Field
		$custom_field_data = [];

		$this->load->model('customer/custom_field');

		$custom_fields = $this->model_customer_customer_group->getCustomFields(['filter_customer_group_id' => $customer_group_info['customer_group_id']]);

		foreach ($custom_fields as $custom_field) {
			$custom_field_description_data = [];

			$custom_field_descriptions = $this->model_customer_custom_field->getDescriptions($custom_field['custom_field_id']);

			foreach ($custom_field_descriptions as $code => $custom_field_description) {
				$custom_field_description_data[$code] = ['name' => $custom_field_description['name']];
			}

			$custom_field_value_data = [];

			if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio' || $custom_field['type'] == 'checkbox') {
				foreach ($custom_field['custom_field_value'] as $custom_field_value) {
					$custom_field_value_description_data = [];

					$custom_field_descriptions = $this->model_customer_custom_field->getValueDescriptions($custom_field['custom_field_id']);

					foreach ($custom_field_descriptions as $custom_field_description) {
						$custom_field_value_description_data[] = ['name' => $custom_field_description['name']];
					}

					$custom_field_value_data[] = [
						'custom_field_value_id' => $custom_field['custom_field_value_id'],
						'description'           => $custom_field_value_description_data,
						'sort_order'            => $custom_field['sort_order']
					];
				}

				$sort_order = [];

				foreach ($custom_field_value_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $custom_field_value_data);
			}

			$custom_field_data[] = [
				'custom_field_id'    => $custom_field['custom_field_id'],
				'description'        => $custom_field_description_data,
				'type'               => $custom_field['type'],
				'custom_field_value' => $custom_field_value_data,
				'value'              => $custom_field['value'],
				'required'           => $custom_field['required'],
				'validation'         => $custom_field['validation'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order']
			];
		}

		$sort_order = [];

		foreach ($custom_field_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $custom_field_data);

		$customer_group_data = [
			'customer_group_id' => $customer_group_info['customer_group_id'],
			'description'       => $description_data,
			'custom_field'      => $custom_field_data,
			'approval'          => $customer_group_info['approval']
		];

		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/customer/';
		$filename = 'customer_group-' . $customer_group_info['customer_group_id'] . '.yaml';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, oc_yaml_encode($customer_group_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $customer_group_info['name'])];
	}

	/**
	 * Delete
	 *
	 * Delete generated JSON customer group files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/customer_group');

		if (!array_key_exists('customer_group_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('customer/customer_group');

		$customer_group_info = $this->model_customer_customer_group->getCustomerGroup((int)$args['customer_group_id']);

		if (!$customer_group_info) {
			return ['error' => $this->language->get('error_customer_group')];
		}

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/customer/customer_group-' . $customer_group_info['customer_group_id'] . '.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => sprintf($this->language->get('text_delete'), $customer_group_info['name'])];
	}
}
