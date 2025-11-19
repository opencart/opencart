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

		foreach ($stores as $store) {
			$task_data = [
				'code'   => 'setting',
				'action' => 'task/catalog/setting.store',
				'args'   => ['store_id' => $store['store_id']]
			];

			$this->model_setting_task->addTask($task_data);
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

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		$capture = [
			'store_id',
			'config_name',
			'config_country_id',
			'config_zone_id',
			'config_timezone',
			'config_open'
		];

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$this->load->model('setting/setting');

		$setting_info = $this->model_setting_setting->getSettings('config', $store_info['store_id']);

		$base = DIR_CATALOG . 'view/data/';

		foreach ($languages as $language) {
			$setting_data = $setting_info;

			unset($setting_data['config_description']);

			if ([$language['language_id']]) {
				$setting_data = array_merge($setting_info['config_description'][$language['language_id']]);
			}

			$filename = parse_url($store_info['url'], PHP_URL_HOST) . '-' . $language['code'] . '.json';

			if (!file_put_contents($base . $filename, json_encode($setting_data))) {
				return ['error' => sprintf($this->language->get('error_file'), $filename)];
			}
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
