<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Country
 *
 * Generates country data files
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate all country data.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/country');

		// Clear old data
		$task_data = [
			'code'   => 'country.clear',
			'action' => 'task/catalog/country.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// List
		$task_data = [
			'code'   => 'country.list',
			'action' => 'task/catalog/country.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		// Info
		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountries();

		foreach ($countries as $country) {
			$task_data = [
				'code'   => 'country.info.' . $country['country_id'],
				'action' => 'task/catalog/country.info',
				'args'   => ['country_id' => $country['country_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate all country list data for all stores.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		// Stores
		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$stores = array_merge($stores, $this->model_setting_store->getStores());

		foreach ($stores as $store) {
			$language_ids = $this->model_setting_setting->getValue('config_language_list', $store['store_id']);

			foreach ($language_ids as $language_id) {
				$task_data = [
					'code'   => 'country',
					'action' => 'task/catalog/country.countries',
					'args'   => [
						'store_id'    => $store['store_id'],
						'language_id' => $language_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => $this->language->get('text_task')];
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

		$stores = array_merge($stores, $this->model_setting_store->getStores());

		foreach ($stores as $store) {
			$language_ids = $this->model_setting_setting->getValue('config_language_list', $store['store_id']);

			foreach ($language_ids as $language_id) {
				$task_data = [
					'code'   => 'country',
					'action' => 'task/catalog/country.info',
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

	/**
	 * List
	 *
	 * Generate country lists.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function countries(array $args = []): array {
		$this->load->language('task/catalog/country');

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStores($args['store_id']);

		if ($args['store_id'] != 0 && !$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info || !$language_info['status']) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('localisation/country');

		// Country
		$country_data = [];

		$this->load->model('setting/setting');

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

	/*
	 *
	 */
	public function country(array $args = []): array {
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
