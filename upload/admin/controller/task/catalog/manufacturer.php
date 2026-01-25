<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Manufacturer
 *
 * Generates manufacturer data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Manufacturer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate country list task for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/manufacturer');

		$this->load->model('setting/task');

		// Stores
		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$language_ids = $this->model_setting_setting->getValue('config_language_list', $store_id);

			foreach ($language_ids as $language_id) {
				$task_data = [
					'code'   => 'manufacturer._list.' . $store_id . '.' . $language_id,
					'action' => 'task/catalog/manufacturer._list',
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
	 * Generate country list by store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function _list(array $args = []): array {
		$this->load->language('task/catalog/manufacturer');

		// Store
		$store_info = [
			'name' => $this->config->get('config_name'),
			'url'  => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStores($args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info || !$language_info['status']) {
			return ['error' => $this->language->get('error_language')];
		}

		// Country
		$country_data = [];

		$this->load->model('setting/setting');
		$this->load->model('localisation/country');

		$country_ids = $this->model_setting_setting->getValue('config_country_list', $args['store_id']);

		foreach ($country_ids as $country_id) {
			$country_info = $this->model_localisation_country->getCountry($country_id);

			if (!$country_info || !$country_info['status']) {
				continue;
			}

			$description_info = $this->model_localisation_country->getDescription($country_info['country_id'], $language_info['language_id']);

			if (!$description_info) {
				continue;
			}

			$country_data[] = $country_info + $description_info;
		}

		$sort_order = [];

		foreach ($country_data as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $country_data);

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/localisation/';
		$filename = 'country.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($country_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $language_info['code'])];
	}

	/**
	 * Info
	 *
	 * Generate country information.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/country');

		if (!array_key_exists('country_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Country
		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$args['country_id']);

		if (!$country_info || !$country_info['status']) {
			return ['error' => $this->language->get('error_country')];
		}

		// Stores
		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($stores as $store) {
			$language_ids = $this->model_setting_setting->getValue('config_language_list', $store_id);

			foreach ($language_ids as $language_id) {
				$task_data = [
					'code'   => 'country',
					'action' => 'task/catalog/country.info_',
					'args'   => [
						'country_id'  => $country_info['country_id'],
						'store_id'    => $store['store_id'],
						'language_id' => $language_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => sprintf($this->language->get('text_info'), $country_info['name'])];
	}

	/*
	 *
	 */
	public function info_(array $args = []): array {
		// Country
		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$args['country_id']);

		if (!$country_info || !$country_info['status']) {
			return ['error' => $this->language->get('error_country')];
		}

		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($args['language_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['country_id']);

		if (!$language_info || !$language_info['status']) {
			return ['error' => $this->language->get('error_language')];
		}

		// Setup allowed countries
		$this->load->model('setting/setting');

		$country_ids = $this->model_setting_setting->getValue('config_country_list', $store_info['store_id']);

		if (!in_array($country_info['country_id'], $country_ids)) {
			return ['error' => $this->language->get('error_country')];
		}

		// Description
		$description_data = $this->model_localisation_country->getDescription($country_info['country_id'], $language_info['language_id']);

		if (!$description_data) {
			return ['error' => $this->language->get('error_description')];
		}

		// Zones
		$zone_data = [];

		// Zones
		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZonesByCountryId($country_info['country_id']);

		foreach ($zones as $zone) {
			if (!$zone['status']) {
				continue;
			}

			$zone_description_info = $this->model_localisation_zone->getDescription($zone['zone_id'], $language_info['language_id']);

			if (!$zone_description_info) {
				continue;
			}

			$zone_data[] = $zone + $zone_description_info;
		}

		// Geo Zones
		$geo_zone_data = [];

		$this->load->model('localisation/geo_zone');

		$geo_zones = $this->model_localisation_geo_zone->getZonesByCountryId($country_info['country_id']);

		foreach ($geo_zones as $geo_zone) {
			$geo_zone_data['geo_zone'][$geo_zone['zone_id']] = $geo_zone['geo_zone_id'];
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/localisation/';
		$filename = 'country-' . $country_info['country_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($country_info + $description_data + ['zone' => $zone_data] + ['geo_zone' => $geo_zone_data]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $country_info['name'])];
	}

	/*
	 * Delete files based on country ID
	 *
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/language');

		// Refresh Lists
		$task_data = [
			'code'   => 'country',
			'action' => 'task/catalog/country.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Delete pages
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
				$base = DIR_CATALOG . 'view/data/';
				$directory = parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/localisation/';

				$file = $base . $directory . 'country.json';

				if (is_file($file)) {
					unlink($file);
				}

				$files = oc_directory_read($base . $directory, false, '/country\-.+\.json$/');

				foreach ($files as $file) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_delete')];
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
		$this->load->language('task/catalog/language');

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
				$base = DIR_CATALOG . 'view/data/';
				$directory = parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/localisation/';

				$file = $base . $directory . 'country.json';

				if (is_file($file)) {
					unlink($file);
				}

				$files = oc_directory_read($base . $directory, false, '/country\-.+\.json$/');

				foreach ($files as $file) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
