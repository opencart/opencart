<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Information
 *
 * Generates information for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Information extends \Opencart\System\Engine\Controller {
	/**
	 * List
	 *
	 * Generate information list task for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/information');

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'information.list.' . $store_id,
				'action' => 'task/catalog/information.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * _list
	 *
	 * Generate country list by store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/information');

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

		$information_data = [];

		$this->load->model('catalog/information');

		$information_ids = $this->model_catalog_information->getStoresByStoreId($store_info['store_id']);

		foreach ($information_ids as $information_id) {
			$information_info = $this->model_catalog_information->getInformation((int)$information_id);

			if ($information_info && $information_info['status']) {
				$information_data[] = array_merge($information_info, ['description' => $this->model_catalog_information->getDescriptions($information_info['information_id'])]);
			}
		}

		$sort_order = [];

		foreach ($information_data as $key => $value) {
			$sort_order[$key] = $value['title'];
		}

		array_multisort($sort_order, SORT_ASC, $information_data);

		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
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

		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation((int)$args['information_id']);

		if (!$information_info || !$information_info['status']) {
			return ['error' => $this->language->get('error_information')];
		}

		$this->load->model('setting/task');

		$store_ids = $this->model_catalog_information->getStores($information_info['information_id']);

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'information._info.' . $store_id . '.' . $information_info['information_id'],
				'action' => 'task/catalog/information._info',
				'args'   => [
					'information_id' => $information_info['information_id'],
					'store_id'       => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_info'), $information_info['title'])];
	}

	public function _info(array $args = []): array {
		$this->load->language('task/catalog/information');

		if (!array_key_exists('information_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

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

		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation((int)$args['information_id']);

		if (!$information_info || !$information_info['status']) {
			return ['error' => $this->language->get('error_information')];
		}

		$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'information-' . $information_info['information_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode(array_merge($information_info, ['description' => $this->model_catalog_information->getDescriptions($information_info['information_id'])])))) {
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

		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation((int)$args['information_id']);

		if (!$information_info) {
			return ['error' => $this->language->get('error_information')];
		}

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/information/information-' . $information_info['information_id'] . '.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => sprintf($this->language->get('text_delete'), $information_info['title'])];
	}
}