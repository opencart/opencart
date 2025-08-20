<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Information
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Information extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates information task list.
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

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * List
	 *
	 * Generates information list file.
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/information');

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




		$code = preg_replace('/[^A-Z0-9\._-]/i', '', $language_info['code']);

		$file = DIR_CATALOG . 'view/data/catalog/information.' . (int)$information['information_id'] . '.' . $code . '.json';

		if (!file_put_contents($file, json_encode($informations))) {
			return ['error' => $this->language->get('error_file')];
		}

		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
	}





	public function clear(array $args = []): array {
		$this->load->language('task/catalog/information');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$file = DIR_CATALOG . 'view/data/' . parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/catalog/information.json';

				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
