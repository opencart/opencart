<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Banner
 *
 * Generates banner data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Banner extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate banner task by banner ID for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/banner');

		if (!array_key_exists('banner_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('design/banner');

		$banner_info = $this->model_design_banner->getBanner((int)$args['banner_id']);

		if (!$banner_info || !$banner_info['status']) {
			return ['error' => $this->language->get('error_banner')];
		}

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'banner.info.' . $store_id . '.' . $banner_info['banner_id'],
				'action' => 'task/catalog/banner.info',
				'args'   => [
					'banner_id' => $banner_info['banner_id'],
					'store_id'  => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * Info
	 *
	 * Generate banner information.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/banner');

		if (!array_key_exists('banner_id', $args)) {
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

		$this->load->model('design/banner');

		$banner_info = $this->model_design_banner->getBanner((int)$args['banner_id']);

		if (!$banner_info || !$banner_info['status']) {
			return ['error' => $this->language->get('error_banner')];
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/design/';
		$filename = 'banner-' . $banner_info['banner_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($banner_info + ['image' => $this->model_design_banner->getImages($banner_info['banner_id'])]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $banner_info['name'])];
	}

	/**
	 * Delete
	 *
	 * Delete generated JSON banner files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/banner');

		$this->load->model('design/banner');

		$banner_info = $this->model_design_banner->getBanner((int)$args['banner_id']);

		if (!$banner_info || !$banner_info['status']) {
			return ['error' => $this->language->get('error_banner')];
		}

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/design/banner-' . $banner_info['banner_id'] . '.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => sprintf($this->language->get('text_delete'), $banner_info['name'])];
	}
}
