<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Information
 *
 * Generates information data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Information extends \Opencart\System\Engine\Controller {
	/**
	 * List
	 *
	 * Generate information list task for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/information');

		// Stores
		$this->load->model('setting/store');
		$this->load->model('setting/setting');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$language_ids = $this->model_setting_setting->getValue('config_language_list', $store_id);

			foreach ($language_ids as $language_id) {
				$task_data = [
					'code'   => 'information._list.' . $store_id . '.' . $language_id,
					'action' => 'task/catalog/information._list',
					'args'   => [
						'store_id'    => $store_id,
						'language_id' => $language_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * _list
	 *
	 * Generate country list by store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function _list(array $args = []): array {
		$this->load->language('task/catalog/information');

		// Store
		$store_info = [
			'name' => $this->config->get('config_name'),
			'url'  => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info || !$language_info['status']) {
			return ['error' => $this->language->get('error_language')];
		}

		// Information List
		$information_data = [];

		$this->load->model('catalog/information');

		$information_ids = $this->model_catalog_information->getStoresByStoreId((int)$args['store_id']);

		foreach ($information_ids as $information_id) {
			$information_info = $this->model_catalog_information->getInformation($information_id);

			if (!$information_info || !$information_info['status']) {
				continue;
			}

			$description_info = $this->model_localisation_country->getDesciptions($information_id, $language_info['language_id']);

			if (!$description_info) {
				continue;
			}

			$information_data[] = $information_info + $description_info;
		}

		$sort_order = [];

		foreach ($information_data as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $information_data);

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/catalog/';
		$filename = 'information.yaml';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, oc_yaml_encode($information_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $language_info['code'])];
	}

	/**
	 * Info
	 *
	 * Generate information data by information ID.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/information');

		if (!array_key_exists('information_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Information
		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation($args['information_id']);

		if (!$information_info || !$information_info['status']) {
			return ['error' => $this->language->get('error_information')];
		}

		$this->load->model('setting/setting');

		$store_ids = $this->model_catalog_information->getStores($args['information_id']);

		foreach ($store_ids as $store_id) {
			$language_ids = $this->model_setting_setting->getValue('config_language_list', $store_id);

			foreach ($language_ids as $language_id) {
				$task_data = [
					'code'   => 'information.addTask.' . $store_id . '.' . $language_id . '.' . $information_info['information_id'],
					'action' => 'task/catalog/information.createTask',
					'args'   => [
						'information_id' => $information_info['information_id'],
						'store_id'       => $store_id,
						'language_id'    => $language_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => sprintf($this->language->get('text_info'), $information_info['name'])];
	}

	public function _info(array $args = []): array {
		$this->load->language('task/catalog/information');

		if (!array_key_exists('information_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'name' => $this->config->get('config_name'),
			'url'  => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info || !$language_info['status']) {
			return ['error' => $this->language->get('error_language')];
		}

		// Information
		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation((int)$args['information_id']);

		if (!$information_info || !$information_info['status']) {
			return ['error' => $this->language->get('error_information')];
		}

		// Description
		$description_info = $this->model_catalog_information->getDescription($information_info['information_id'], $language_info['language_id']);

		if (!$description_info) {
			return ['error' => $this->language->get('error_description')];
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/catalog/';
		$filename = 'information-' . $information_info['information_id'] . '.yaml';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, oc_yaml_encode($description_info + $information_info))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $information_info['name'])];
	}

	/**
	 * Delete
	 *
	 * Delete generated JSON information files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/admin/information');

		$file = HTTP_SERVER . 'view/data/admin/information.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_delete')];
	}
}