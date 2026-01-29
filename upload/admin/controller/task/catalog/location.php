<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Location
 *
 * Generates location data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Location extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate location list task for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/location');

		$this->load->model('setting/task');

		// Stores
		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$language_ids = $this->model_setting_setting->getValue('config_language_list', $store_id);

			foreach ($language_ids as $language_id) {
				$task_data = [
					'code'   => 'location.' . $store_id . '.' . $language_id,
					'action' => 'task/catalog/location.list',
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
	 * List
	 *
	 * Generate location list by store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/location');

		// Store
		$store_info = [
			'name' => $this->config->get('config_name'),
			'url'  => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($args['store_id']);

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

		// Location
		$location_data = [];

		$this->load->model('setting/setting');
		$this->load->model('localisation/location');

		$location_ids = $this->model_setting_setting->getValue('config_location_list', $args['store_id']);

		foreach ($location_ids as $location_id) {
			$location_info = $this->model_localisation_location->getLocation($location_id);

			if (!$location_info || !$location_info['status']) {
				$location_data[] = $location_info;
			}
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/localisation/';
		$filename = 'location.yaml';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, oc_yaml_encode($location_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
	}
}
