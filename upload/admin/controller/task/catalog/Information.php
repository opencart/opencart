<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Information
 *
 * Generates information.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Information extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate customer group data based on store.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/information');

		if (!array_key_exists('store_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		$task_data = [
			'code'   => 'information.list.' . $store_info['store_id'],
			'action' => 'task/catalog/information.list',
			'args'   => ['store_id' => $store_info['store_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true,
			'sort'            => 'sort_order',
			'order'           => 'ASC',
		];

		$this->load->model('catalog/information');

		$results = $this->model_catalog_information->getInformations($filter_data);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'information.info.' . $store_info['store_id'] . '.' . $result['information_id'],
				'action' => 'task/catalog/information.info',
				'args'   => [
					'information_id' => $result['information_id'],
					'store_id'       => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_task'), $store_info['name'])];
	}

	/**
	 * List
	 *
	 * Generate information list task for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/information');

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		// Information
		$information_data = [];

		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true,
			'sort'            => 'sort_order',
			'order'           => 'ASC',
		];

		$this->load->model('catalog/information');

		$results = $this->model_catalog_information->getInformations($filter_data);

		foreach ($results as $result) {
			$description_data = [];

			$descriptions = $this->model_catalog_information->getDescriptions($result['information_id']);

			foreach ($descriptions as $code => $description) {
				$description_data[$code] = ['title' => $description['title']];
			}

			$information_data[] = [
				'information_id' => $result['information_id'],
				'description'    => $description_data
			];
		}

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'information.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($information_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'])];
	}

	/**
	 * Info
	 *
	 * Generate information data by information ID.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/information');

		if (!array_key_exists('information_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		// Information
		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation((int)$args['information_id']);

		if (!$information_info || !$information_info['status'] || !in_array($store_info['store_id'], $this->model_catalog_information->getStores($information_info['information_id']))) {
			return ['error' => $this->language->get('error_information')];
		}

		// Description
		$description_data = [];

		$descriptions = $this->model_catalog_information->getDescriptions($information_info['information_id']);

		foreach ($descriptions as $code => $description) {
			$description_data[$code] = [
				'title'            => $description['title'],
				'description'      => $description['description'],
				'meta_title'       => $description['meta_title'],
				'meta_description' => $description['meta_description'],
				'meta_keyword'     => $description['meta_keyword']
			];
		}

		$information_data = [
			'information_id' => $information_info['information_id'],
			'description'    => $description_data
		];

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'information-' . $information_info['information_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($information_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $information_info['title'])];
	}

	/**
	 * Delete
	 *
	 * Delete generated JSON information files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/information');

		if (!array_key_exists('information_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		$file = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/information/information-' . (int)$args['information_id'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => sprintf($this->language->get('text_delete'), $store_info['name'])];
	}
}