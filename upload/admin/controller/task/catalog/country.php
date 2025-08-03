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
	 * Generates country task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/country');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'country',
					'action' => 'catalog/country.list',
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
	 * Generates country list file.
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/country');

		$this->load->model('setting/task');

		// Validate Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Validate Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$country_data = [];

		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountriesByStoreId($store_info['store_id']);

		foreach ($countries as $country) {
			if ($country['status']) {
				$description_info = $this->model_localisation_country->getDescription($country['country_id'], $language_info['language_id']);

				if ($description_info) {
					$country_data[$country['country_id']] = $description_info + $country;

					$task_data = [
						'code'   => 'country',
						'action' => 'catalog/country.info',
						'args'   => [
							'store_id'    => $store_info['store_id'],
							'language_id' => $language_info['language_id'],
							'country_id'  => $country['country_id']
						]
					];

					$this->model_setting_task->addTask($task_data);
				}
			}
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

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $language_info['name'])];
	}

	/**
	 * Info
	 *
	 * Generates country information file.
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/country');

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

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$args['country_id']);

		if (!$country_info) {
			return ['error' => $this->language->get('error_country')];
		}

		$zone_data = [];

		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZonesByCountryId((int)$country_info['country_id']);

		foreach ($zones as $zone) {
			if ($zone['status']) {
				$zone_description_info = $this->model_localisation_zone->getDescription((int)$zone['zone_id'], (int)$language_info['language_id']);

				if ($zone_description_info) {
					$zone_data[] = $zone_description_info + $zone;
				}
			}
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/localisation/';
		$filename = 'country-' . $args['country_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($country_info + ['zone' => $zone_data]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $language_info['name'], $country_info['name'])];
	}

	/**
	 * Clear
	 *
	 * Clears generated country files.
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/language');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$store_url = parse_url($store['url'], PHP_URL_HOST);

			foreach ($languages as $language) {
				$base = DIR_CATALOG . 'view/data/';
				$directory = $store_url . '/' . $language['code'] . '/localisation/';

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

		return ['success' => $this->language->get('text_success')];


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
