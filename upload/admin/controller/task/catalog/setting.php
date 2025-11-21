<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Setting
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Setting extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate setting task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/setting');

		// Clear old data
		$task_data = [
			'code'   => 'setting',
			'action' => 'task/catalog/setting.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'setting',
					'action' => 'task/catalog/setting.store',
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
	 * Store
	 *
	 * Generate JSON currency list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function store(array $args = []): array {
		$this->load->language('task/catalog/setting');

		if (!array_key_exists('store_id', $args)) {
			return ['error' => $this->language->get('error_store')];
		}

		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Setting
		$this->load->model('setting/setting');

		$setting_info = $this->model_setting_setting->getSettings('config', $store_info['store_id']);

		if (!$setting_info) {
			return ['error' => $this->language->get('error_setting')];
		}

		// Country
		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$setting_info['country_id']);

		if (!$country_info) {
			return ['error' => $this->language->get('error_country')];
		}

		// Zone
		$this->load->model('localisation/zone');

		$zone_info = $this->model_localisation_zone->getZone((int)$setting_info['zone_id']);

		if (!$zone_info) {
			return ['error' => $this->language->get('error_zone')];
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		// Currency
		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode((int)$setting_info['currency']);

		if (!$currency_info) {
			return ['error' => $this->language->get('error_currency')];
		}

		$config = [];

		$config['storage_path'] = '';
		$config['language_path'] = '';
		$config['template_path'] = '';

		$config['name'] = $store_info['name'];
		$config['store_url'] = '';

		$config['theme'] = $setting_info['config_theme'];

		$config['country_id'] = (int)$country_info['country_id'];
		$config['zone_id'] = (int)$zone_info['zone_id'];

		$config['language'] = (int)$language_info['code'];
		$config['currency'] = (int)$currency_info['code'];

		$config['pagination'] = (int)$setting_info['config_pagination'];
		$config['tax'] = (int)$setting_info['config_tax'];



		$capture = [
			'store_id',
			'config_name',
			'config_logo',
			'config_icon',
			'config_theme',
			'config_country_id',
			'config_zone_id',
			'config_timezone',
			'config_length_class_id',
			'config_weight_class_id',

			'config_language_catalog',
			'config_currency',
			'config_pagination',

			'config_tax',

			'config_tax_default',
			'config_tax_customer',

			'config_customer_group_id'
		];

		$base = DIR_CATALOG . 'view/data/';

		$setting_data = $setting_info;

		$filename = parse_url($store_info['url'], PHP_URL_HOST) . '-' . $language_info['code'] . '.json';

		if (!file_put_contents($base . $filename, json_encode($setting_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $filename)];
		}


		return ['success' => sprintf($this->language->get('text_list'), $setting_info['name'])];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON currency files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/setting');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$file = DIR_CATALOG . 'view/data/' . parse_url($store['url'], PHP_URL_HOST) . '-' . $language['code'] . '.json';

				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
