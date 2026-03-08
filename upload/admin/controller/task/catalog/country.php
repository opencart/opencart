<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Country
 *
 * Generates country data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * List
	 *
	 * Generate country list task by store.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/country');

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'country.list.' . $store_id,
				'action' => 'task/catalog/country._list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * list
	 *
	 * Generate country list by store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/country');

		// Store
		$store_info = [
			'name' => $this->config->get('config_name'),
			'url'  => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStores((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		// Country List
		$country_data = [];

		$this->load->model('setting/setting');
		$this->load->model('localisation/country');

		$country_ids = $this->model_setting_setting->getValue('config_country_list', $store_info['store_id']);

		foreach ($country_ids as $country_id) {
			$country_info = $this->model_localisation_country->getCountry($country_id);

			if (!$country_info || !$country_info['status']) {
				continue;
			}

			$country_data[] = $country_info + ['description' => $this->model_localisation_country->getDescriptions($country_info['country_id'])];
		}

		$sort_order = [];

		foreach ($country_data as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $country_data);

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/localisation/';
		$filename = 'country.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($country_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'])];
	}

	/**
	 * Info
	 *
	 * Generate country data by country ID.
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

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'country._info.' . $store_id . '.' . $country_info['country_id'],
				'action' => 'task/catalog/country.info',
				'args'   => [
					'country_id' => $country_info['country_id'],
					'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_info'), $country_info['name'])];
	}

	/*
	 *
	 */
	public function _info(array $args = []): array {
		$this->load->language('task/catalog/country');

		if (!array_key_exists('country_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'name' => $this->config->get('config_name'),
			'url'  => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStores((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		// Country
		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$args['country_id']);

		if (!$country_info || !$country_info['status']) {
			return ['error' => $this->language->get('error_country')];
		}

		// Zones
		$zone_data = [];

		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZonesByCountryId($country_info['country_id']);

		foreach ($zones as $zone) {
			if (!$zone['status']) {
				continue;
			}

			$zone_data[] = $zone + ['description' => $this->model_localisation_zone->getDescription($zone['zone_id'])];
		}

		// Geo Zones
		$geo_zone_data = [];

		$this->load->model('localisation/geo_zone');

		$geo_zones = $this->model_localisation_geo_zone->getZonesByCountryId($country_info['country_id']);

		foreach ($geo_zones as $geo_zone) {
			$geo_zone_data['geo_zone'][$geo_zone['zone_id']] = $geo_zone['geo_zone_id'];
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/localisation/';
		$filename = 'country-' . $country_info['country_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($country_info + ['description' => $this->model_localisation_country->getDescriptions($country_info['country_id'])] + ['zone' => $zone_data] + ['geo_zone' => $geo_zone_data]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $country_info['name'])];
	}

	/*
	 * Delete files based on country ID
	 *
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/language');

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
		$this->load->model('setting/store');
		
		$stores = array_merge(['url' => HTTP_CATALOG], $this->model_setting_store->getStores());

		foreach ($stores as $store) {
			$base = DIR_CATALOG . 'view/data/';
			$directory = parse_url($store['url'], PHP_URL_HOST) . '/localisation/';
			$file = $base . $directory . 'country.json';

			if (is_file($file)) {
				unlink($file);
			}

			$files = oc_directory_read($base . $directory, false, '/country\-.+\.json$/');

			foreach ($files as $file) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_delete')];
	}
}
