<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Banner
 *
 * Generates banner data files.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Banner extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate banner task list.
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

		$this->load->model('setting/task');

		// Banner
		$this->load->model('design/banner');

		$banner_info = $this->model_design_banner->getBanner((int)$args['banner_id']);

		if (!$banner_info || !$banner_info['status']) {
			return ['error' => $this->language->get('error_banner')];
		}

		// Stores
		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$banner_info = $this->model_design_banner->getBanner((int)$args['banner_id']);

			$language_ids = $this->model_setting_setting->getValue('config_language_list', $store_id);

			foreach ($language_ids as $language_id) {
				$task_data = [
					'code'   => 'banner.info.' . $store_id . '.' . $language_id . '.' . $banner_info['banner_id'],
					'action' => 'task/catalog/banner.info',
					'args'   => [
						'banner_id'   => $banner_info['banner_id'],
						'store_id'    => $store_id,
						'language_id' => $language_id
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
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

		$this->load->model('design/banner');

		$banner_info = $this->model_design_banner->getBanner((int)$args['banner_id']);

		if (!$banner_info || !$banner_info['status']) {
			return ['error' => $this->language->get('error_banner')];
		}

		$banners = $this->model_design_banner->getImages($banner_info['banner_id'], $language_info['language_id']);

		$sort_order = [];

		foreach ($banners as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $banners);

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/design/';
		$filename = 'banner-' . $args['banner_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($banners))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $language_info['name'], $banner_info['name'])];
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
		$this->load->language('task/catalog/language');

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
				$files = oc_directory_read(DIR_CATALOG . 'view/data/' . parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/design/', false, '/banner\-.+\.json$/');

				foreach ($files as $file) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
