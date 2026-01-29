<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Currency
 *
 * Generates currency data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Currency extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate currency list task for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/currency');

		$this->load->model('setting/task');

		// Stores
		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		/*
		foreach ($store_ids as $store_id) {
			$language_ids = $this->model_setting_setting->getValue('config_language_list', $store_id);

			print_r($language_ids);

			foreach ($language_ids as $language_id) {
				$task_data = [
					'code'   => 'currency.' . $store_id . '.' . $language_id,
					'action' => 'task/catalog/currency.list',
					'args'   => [
						'store_id'    => $store_id,
						'language_id' => $language_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}
		`*/

		$task_data = [
			'code'   => 'currency.0.1',
			'action' => 'task/catalog/currency.list',
			'args'   => [
				'store_id'    => 0,
				'language_id' => 1
			]
		];

		$this->model_setting_task->addTask($task_data);


		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate currency list by store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/currency');

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

		// Currency
		$currency_data = [];

		$this->load->model('setting/setting');
		$this->load->model('localisation/currency');

		//$currency_ids = $this->model_setting_setting->getValue('config_currency_list', $args['store_id']);

		$currency_ids = [1, 2, 3, 4, 5, 6, 7, 8];

		foreach ($currency_ids as $currency_id) {
			$currency_info = $this->model_localisation_currency->getCurrency($currency_id);

			print_r($currency_info);

			if (!$currency_info || !$currency_info['status']) {
				$currency_data[$currency_info['code']] = $currency_info;
			}
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) .  '/' . $language_info['code'] . '/localisation/';
		$filename = 'currency.yaml';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, oc_yaml_encode($currency_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $language_info['name'])];
	}

	/**
	 * Delete
	 *
	 * Delete files based on country ID
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/currency');

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
				$file = DIR_CATALOG . 'view/data/' . parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/localisation/currency.json';

				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
