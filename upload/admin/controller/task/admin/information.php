<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Information
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Information extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate information task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/information');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'information',
					'action' => 'task/catalog/information.list',
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
	 * Generate JSON information list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/information');

		$required = [
			'store_id',
			'language_id'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => $this->language->get('error_required', $value)];
			}
		}

		$this->load->model('setting/task');

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

		$filter_data = [
			'filter_store_id'    => $store_info['store_id'],
			'filter_language_id' => $language_info['language_id'],
			'status'             => 1
		];

		$this->load->model('catalog/information');

		$informations = $this->model_catalog_information->getInformations($filter_data);

		foreach ($informations as $information) {
			$task_data = [
				'code'   => 'ssr',
				'action' => 'task/catalog/ssr',
				'args'   => [
					'route'          => 'product/manufacturer',
					'information_id' => $information['information_id'],
					'store_id'       => $store_info['store_id'],
					'language_id'    => $language_info['language_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		$sort_order = [];

		foreach ($informations as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $informations);

		$base = DIR_OPENCART . 'shop/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/data/catalog/';
		$filename = 'information.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => $this->language->get('error_directory', $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($informations))) {
			return ['error' => $this->language->get('error_file', $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list', $store_info['name'], $language_info['name'])];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON information files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/information');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$file = DIR_OPENCART . 'shop/' . parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/data/catalog/information.json';

				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
