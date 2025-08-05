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
	 * Generates customer group task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/customer_group');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

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

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * List
	 *
	 * Generates customer group list file.
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/customer_group');

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

		$customer_group_data = [];

		$this->load->model('customer/customer_group');

		$customer_groups = $this->model_customer_customer_group->getCustomerGroups();

		foreach ($customer_groups as $customer_group) {
			$description_info = $this->model_customer_customer_group->getDescription($customer_group['customer_group_id'], $language_info['language_id']);

			if ($description_info) {
				$customer_group_data[$customer_group['customer_group_id']] = $description_info + $customer_group;
			}
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/customer/';
		$filename = 'customer_group.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($customer_group_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
	}

	/**
	 * Clear
	 *
	 * Clears generated country files.
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/customer_group');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

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
