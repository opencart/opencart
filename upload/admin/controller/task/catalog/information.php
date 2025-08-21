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
				'code'   => 'information',
				'action' => 'task/catalog/information.info',
				'args'   => [
					'information_id'  => $information['information_id'],
					'store_id'        => $store_info['store_id'],
					'language_id'     => $language_info['language_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		$sort_order = [];

		foreach ($informations as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $informations);

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/catalog/';
		$filename = 'information.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($informations))) {
			return ['error' => $this->language->get('error_file')];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $language_info['name'])];
	}

	public function info(array $args = []): array {
		$this->load->language('task/catalog/information');

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

		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation($args['information_id']);

		if (!$information_info) {
			return ['error' => $this->language->get('error_information')];
		}

		// 1. Create a store instance using loader class to call controllers, models, views, libraries.
		$this->load->model('setting/store');

		$store = $this->model_setting_store->createStoreInstance($store_info['store_id'], $language_info['code']);

		// Make sure the SEO URL's work
		$store->load->controller('startup/rewrite');

		$args['route'] = 'cms/topic';

		$keys = [
			'route',
			'topic_id',
			'language_id',
			'sort',
			'order',
			'page'
		];

		foreach ($keys as $key) {
			if (!empty($args[$key])) {
				$store->request->get[$key] = $args[$key];
			}
		}

		// 2. Call the required API controller.
		$store->load->controller('cms/topic');

		// 3. Call the required API controller and get the output.
		$output = $store->response->getOutput();

		// 4. Clean up data by clearing cart.
		$store->cart->clear();

		// 5. Deleting the current session, so we are not creating infinite sessions.
		$store->session->destroy();

		// Create the directory and file names.
		$this->load->model('design/seo_url');

		//$base = DIR_STORE;

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . $this->model_design_seo_url->convert($args['store_id'], $args['language_id'], $args) . '/';
		$filename = 'index.html';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, $output)) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}


		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $language_info['name'])];
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
