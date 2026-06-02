<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Country
 *
 * Generates country information for all stores.
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

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$store_info = [
				'store_id' => 0,
				'name'     => $this->config->get('config_name'),
				'url'      => HTTP_CATALOG
			];

			if ($store_id) {
				$this->load->model('setting/store');

				$store_info = $this->model_setting_store->getStore((int)$store_id);

				if (!$store_info) {
					return ['error' => $this->language->get('error_store')];
				}
			}

			$country_data = [];

			$country_ids = (array)$this->model_setting_setting->getValue('config_country_list', $store_info['store_id']);

			foreach ($country_ids as $country_id) {
				$country_info = $this->model_localisation_country->getCountry((int)$country_id);

				if ($country_info && $country_info['status']) {
					$description_data = [];

					$descriptions = $this->model_localisation_country->getDescriptions($country_info['country_id']);

					foreach ($descriptions as $code => $description) {
						$description_data[$code] = ['name' => $description['name']];
					}

					$country_data[] = [
						'country_id'  => $country_info['country_id'],
						'description' => $description_data
					];
				}
			}

			$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/localisation/';
			$filename = 'country.yaml';

			if (!oc_directory_create($directory, 0777)) {
				return ['error' => sprintf($this->language->get('error_directory'), $directory)];
			}

			if (!file_put_contents($directory . $filename, oc_yaml_encode($country_data))) {
				return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
			}
		}

		return ['success' => $this->language->get('text_list')];
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

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$args['country_id']);

		if (!$country_info || !$country_info['status']) {
			return ['error' => $this->language->get('error_country')];
		}

		// Description
		$description_data = [];

		$descriptions = $this->model_localisation_country->getDescriptions($country_info['country_id']);

		foreach ($descriptions as $code => $description) {
			$description_data[$code] = ['name' => $description['name']];
		}

		// Zones
		$zone_data = [];

		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZonesByCountryId($country_info['country_id']);

		foreach ($zones as $zone) {
			if ($zone['status']) {
				$description_data = [];

				$descriptions = $this->model_localisation_zone->getDescriptions($zone['zone_id']);

				foreach ($descriptions as $code => $description) {
					$description_data[$code] = ['name' => $description['name']];
				}

				$zone_data[] = [
					'zone_id'     => $zone['zone_id'],
					'description' => $description_data,
					'code'        => $zone['code']
				];
			}
		}

		// Geo Zones
		$geo_zone_data = [];

		$this->load->model('localisation/geo_zone');

		$geo_zones = $this->model_localisation_geo_zone->getZonesByCountryId($country_info['country_id']);

		foreach ($geo_zones as $geo_zone) {
			$geo_zone_data['geo_zone'][$geo_zone['zone_id']] = $geo_zone['geo_zone_id'];
		}

		$country_data = [
			'country_id'        => $country_info['country_id'],
			'description'       => $description_data,
			'iso_code_2'        => $country_info['iso_code_2'],
			'iso_code_3'        => $country_info['iso_code_3'],
			'address_format_id' => $country_info['address_format_id'],
			'postcode_required' => $country_info['postcode_required'],
			'zones'             => $zone_data,
			'geo_zones'         => $geo_zone_data
		];

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			// Store
			$store_info = [
				'store_id' => 0,
				'name'     => $this->config->get('config_name'),
				'url'      => HTTP_CATALOG
			];

			if ($store_id) {
				$this->load->model('setting/store');

				$store_info = $this->model_setting_store->getStore($store_id);

				if (!$store_info) {
					return ['error' => $this->language->get('error_store')];
				}
			}

			$country_ids = (array)$this->model_setting_setting->getValue('config_country_list', $store_info['store_id']);

			if (!in_array($country_info['country_id'], $country_ids)) {
				continue;
			}

			$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/localisation/';
			$filename = 'country-' . $country_info['country_id'] . '.yaml';

			if (!oc_directory_create($directory, 0777)) {
				return ['error' => sprintf($this->language->get('error_directory'), $directory)];
			}

			if (!file_put_contents($directory . $filename, oc_yaml_encode($country_data))) {
				return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
			}
		}

		return ['success' => sprintf($this->language->get('text_info'), $country_info['name'])];
	}

	/*
	 * Delete files based on country ID
	 *
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/country');

		if (!array_key_exists('country_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$args['country_id']);

		if (!$country_info) {
			return ['error' => $this->language->get('error_country')];
		}

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/localisation/country-' . $country_info['country_id'] . '.yaml';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => sprintf($this->language->get('text_delete'), $country_info['name'])];
	}
}
