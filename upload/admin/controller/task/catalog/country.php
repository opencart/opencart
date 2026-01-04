<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate country task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/country');

		// List
		$task_data = [
			'code'   => 'country',
			'action' => 'task/catalog/country.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Info
		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountries();

		foreach ($countries as $country) {
			$task_data = [
				'code'   => 'country',
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
	 * Generate JSON country list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/country');

		// Country
		$this->load->model('localisation/country');

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
			$languages = $this->model_setting_setting->getValue('config_language_list', $store['store_id']);
			$countries = $this->model_setting_setting->getValue('config_country_list', $store['store_id']);

			foreach ($languages as $language_id) {
				$language_info = $this->model_localisation_language->getLanguage((int)$language_id);

				if (!$language_info || !$language_info['status']) {
					continue;
				}

				$country_data = [];

				foreach ($countries as $country_id) {
					$country_info = $this->model_localisation_country->getCountry((int)$country_id);

					if (!$country_info || !$country_info['status']) {
						continue;
					}

					$description_info = $this->model_localisation_country->getDescription($country_info['country_id'], $language_id);

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
				$directory = parse_url($store['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/localisation/';
				$filename = 'country.json';

				if (!oc_directory_create($base . $directory, 0777)) {
					return ['error' => sprintf($this->language->get('error_directory'), $directory)];
				}

				if (!file_put_contents($base . $directory . $filename, json_encode($country_data))) {
					return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
				}
			}
		}

		return ['success' => $this->language->get('text_list')];
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
			return ['success' => $this->language->get('error_country')];
		}

		// Zones
		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZonesByCountryId($country_info['country_id']);

		// Geo Zones
		$geo_zone_data = [];

		$this->load->model('localisation/geo_zone');

		$geo_zones = $this->model_localisation_geo_zone->getZonesByCountryId($country_info['country_id']);

		foreach ($geo_zones as $geo_zone) {
			$geo_zone_data['geo_zone'][$geo_zone['zone_id']] = $geo_zone['geo_zone_id'];
		}

		// Stores
		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');
		$this->load->model('setting/setting');
		$this->load->model('localisation/language');

		$stores = array_merge($stores, $this->model_setting_store->getStores());

		foreach ($stores as $store) {
			// Setup allowed countries
			$country_list = $this->model_setting_setting->getValue('config_country_list', $store['store_id']);

			if (!in_array($country_info['country_id'], $country_list)) {
				continue;
			}

			// Setup allowed languages
			$languages = $this->model_setting_setting->getValue('config_language_list', $store['store_id']);

			foreach ($languages as $language_id) {
				$language_info = $this->model_localisation_language->getLanguage($language_id);

				if (!$language_info || !$language_info['status']) {
					continue;
				}

				// Description
				$country_description = $this->model_localisation_country->getDescription($country_info['country_id'], $language_info['language_id']);

				if (!$country_description) {
					continue;
				}

				$zone_data = [];

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

				$base = DIR_CATALOG . 'view/data/';
				$directory = parse_url($store['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/localisation/';
				$filename = 'country-' . $country_info['country_id'] . '.json';

				if (!oc_directory_create($base . $directory, 0777)) {
					return ['error' => sprintf($this->language->get('error_directory'), $directory)];
				}

				if (!file_put_contents($base . $directory . $filename, json_encode($country_info + $country_description + ['zone' => $zone_data] + ['geo_zone' => $geo_zone_data]))) {
					return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
				}
			}
		}

		return ['success' => sprintf($this->language->get('text_info'), $country_info['name'])];
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
