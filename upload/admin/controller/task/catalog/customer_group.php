<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Customer Group
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

		$this->load->model('setting/task');

		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$stores = array_merge($stores, $this->model_setting_store->getStores());

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'customer_group',
					'action' => 'task/catalog/customer_group.list',
					'args'   => [
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate JSON customer group list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/customer_group');

		$required = [
			'store_id',
			'language_id'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('setting/task');

		$this->load->model('customer/customer_group');

		$customer_groups = $this->model_customer_customer_group->getCustomerGroups(['filter_language_id' => $language_info['language_id']]);

		foreach ($customer_groups as $customer_group) {
			// Add a task for generating the country info data
			$task_data = [
				'code'   => 'customer_group',
				'action' => 'task/catalog/customer_group.info',
				'args'   => [
					'customer_group_id' => $customer_group['customer_group_id'],
					'store_id'          => $store_info['store_id'],
					'language_id'       => $language_info['language_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/customer/';
		$filename = 'customer_group.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($customer_groups))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
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

		$required = [
			'customer_group_id',
			'store_id',
			'language_id'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		// Customer Group
		$this->load->model('customer/customer_group');

		$customer_group_info = $this->model_customer_customer_group->getCustomerGroup((int)$args['customer_group_id']);

		if (!$customer_group_info) {
			return ['error' => $this->language->get('error_customer_group')];
		}

		$description_info = $this->model_customer_customer_group->getDescription($customer_group_info['customer_group_id'], $language_info['language_id']);

		if (!$description_info) {
			return ['error' => $this->language->get('error_description')];
		}

		// Custom Fields
		$filter_data = [
			'filter_customer_group_id' => $customer_group_info['customer_group_id'],
			'filter_language_id'       => $language_info['language_id']
		];

		$this->load->model('customer/custom_field');

		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/customer/';
		$filename = 'customer_group-' . $customer_group_info['customer_group_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($customer_group_info + $description_info + ['custom_field' => $custom_fields]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $language_info['name'], $customer_group_info['name'])];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON country files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/customer_group');

		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$stores = array_merge($stores, $this->model_setting_store->getStores());

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$file = DIR_CATALOG . 'view/data/' . parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/customer/customer_group.json';

				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
