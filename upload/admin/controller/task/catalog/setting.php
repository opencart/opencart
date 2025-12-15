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

		$language_info = $this->model_localisation_language->getLanguageByCode($setting_info['language']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		// Currency
		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($setting_info['currency']);

		if (!$currency_info) {
			return ['error' => $this->language->get('error_currency')];
		}

		$config = [];

		// Hostname
		$hostname = parse_url($store_info['url'], PHP_URL_HOST);

		$config['config_path'] = 'catalog/view/config/' . $hostname  . '/' . $language_info['code']  . '/';
		$config['storage_path'] = 'catalog/view/data/' . $hostname  . '/' . $language_info['code']  . '/';
		$config['language_path'] = 'catalog/view/language/' . $hostname  . '/' . $language_info['code']  . '/';
		$config['template_path'] = 'catalog/view/template/' . $hostname  . '/';

		// Store URL toi be used
		$config['store_url'] = $store_info['url'];

		// Meta Information
		$description = $setting_info['config_description'][$language_info['language_id']];

		$config['meta_title'] = $description['meta_title'];
		$config['meta_description'] = $description['meta_description'];
		$config['meta_keyword'] = $description['meta_keyword'];

		// Theme
		$config['theme'] = $setting_info['config_theme'];

		// Store
		$config['name'] = $store_info['name'];

		// Country
		$config['country_id'] = (int)$country_info['country_id'];

		$config['country_list'] = [];

		$countries = $setting_info['config_country_list'];

		foreach ($countries as $country_id) {
			$country_info = $this->model_localisation_country->getCountry((int)$country_id);

			if (!$country_info || !$country_info['status']) {
				continue;
			}

			$description_info = $this->model_localisation_country->getDescription((int)$country_id, $language_info['language_id']);

			if (!$description_info) {
				continue;
			}

			unset($description_info['language_id']);
			
			$config['country_list'][] = $description_info;
		}

		// Zone
		$config['zone_id'] = (int)$zone_info['zone_id'];

		// Language
		$config['language'] = $language_info['code'];

		$config['language_list'] = [];

		$languages = $setting_info['config_language_list'];

		foreach ($languages as $language) {
			$language_info = $this->model_localisation_language->getLanguageByCode((string)$language);

			if ($language_info) {
				$config['language_list'][] = $language_info;
			}
		}

		// Currency
		$config['currency'] = $currency_info['code'];

		$currencies = $setting_info['config_currency_list'];

		foreach ($currencies as $currency) {
			$currency_info = $this->model_localisation_currency->getCurrencyByCode((string)$currency);

			if ($currency_info) {
				$config['currency_list'][] = $currency_info;
			}
		}

		$config['pagination'] = (int)$setting_info['config_pagination'];

		// Customer Group
		$config['customer_group_id'] = (int)$setting_info['config_customer_group_id'];

		// Tax
		$config['tax'] = (int)$setting_info['config_tax'];
		$config['tax_default'] = $setting_info['config_tax_default'];
		$config['tax_customer'] = $setting_info['config_tax_customer'];

		$base = DIR_CATALOG . 'view/data/';

		$filename = parse_url($store_info['url'], PHP_URL_HOST) . '-' . $language_info['code'] . '.json';

		if (!file_put_contents($base . $filename, json_encode($config))) {
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

		$file = DIR_CATALOG . 'view/data/' . parse_url($store['url'], PHP_URL_HOST) . '-' . $language['code'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
