<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Language
 *
 * Generates language list data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate language list task for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/language');

		// Stores
		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'language.' . $store_id,
				'action' => 'task/catalog/language.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/***
	 * List
	 *
	 *
	 * @param $args
	 *
	 * @return array|void
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/language');

		// Store
		$store_info = [
			'name' => $this->config->get('config_name'),
			'url'  => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		$language_data = [];

		$this->load->model('setting/setting');
		$this->load->model('localisation/language');

		$language_ids = $this->model_setting_setting->getValue('config_language_list', (int)$args['store_id']);

		foreach ($language_ids as $language_id) {
			$language_info = $this->model_localisation_language->getLanguage((int)$language_id);

			if ($language_info && $language_info['status']) {
				$language_data[$language_info['code']] = $language_info;
			}
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/localisation/';
		$filename = 'language.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($language_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'])];
	}

	/**
	 * Delete
	 *
	 * Delete generated JSON language files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/language');

		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$stores = array_merge($stores, $this->model_setting_store->getStores());

		foreach ($stores as $store) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store['url'], PHP_URL_HOST) . '/localisation/language.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}