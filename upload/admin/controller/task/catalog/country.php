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

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'country',
					'action' => 'task/catalog/country.list',
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
	 * Generate JSON country list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/country');

		$this->load->model('setting/task');

		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$filter_data = [
			'filter_store_id'    => $store_info['store_id'],
			'filter_language_id' => $language_info['language_id'],
			'status'             => 1
		];

		$this->load->model('localisation/country');

		$countries = $this->model_localisation_country->getCountries($filter_data);

		foreach ($countries as $country) {
			$task_data = [
				'code'   => 'country',
				'action' => 'task/catalog/country.info',
				'args'   => [
					'country_id'  => $country['country_id'],
					'store_id'    => $store_info['store_id'],
					'language_id' => $language_info['language_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		$sort_order = [];

		foreach ($countries as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $countries);

		$base = DIR_OPENCART . 'shop/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/data/localisation/';
		$filename = 'country.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => $this->language->get('error_directory', $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($countries))) {
			return ['error' => $this->language->get('error_file', $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list', $store_info['name'], $language_info['name'])];
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

		$required = [
			'country_id',
			'store_id',
			'language_id'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => $this->language->get('error_required', $value)];
			}
		}

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

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

		if (!$country_info['status']) {
			return [];
		}

		$description_info = $this->model_localisation_country->getDescription((int)$country_info['country_id'], $language_info['language_id']);

		if (!$description_info) {
			return ['error' => $this->language->get('error_description')];
		}

		$stores = $this->model_localisation_country->getStores((int)$country_info['country_id']);

		if (!in_array($language_info['language_id'], $stores)) {
			return [];
		}

		$filter_data = [
			'filter_country_id'  => $country_info['country_id'],
			'filter_language_id' => $language_info['language_id'],
			'filter_status'      => 1
		];

		$this->load->model('localisation/zone');

		$zones = $this->model_localisation_zone->getZones($filter_data);

		$base = DIR_OPENCART . 'shop/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/data/localisation/';
		$filename = 'country-' . $args['country_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => $this->language->get('error_directory', $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($country_info + $description_info + ['zone' => $zones]))) {
			return ['error' => $this->language->get('error_file', $directory . $filename)];
		}

		return ['success' => $this->language->get('text_info', $store_info['name'], $language_info['name'], $country_info['name'])];
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

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$base = DIR_OPENCART . 'shop/';
				$directory = parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/data/localisation/';

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
