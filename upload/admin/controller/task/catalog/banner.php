<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Banner
 *
 * Generates banner information for all stores.
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

		// Image
		$image_data = [];

		$images = $this->model_design_banner->getImages($banner_info['banner_id']);

		foreach ($images as $code => $image) {
			$image_data[$code] = [
				'title'      => $image['title'],
				'image'      => $image['image'],
				'link'       => $image['link'],
				'sort_order' => $image['sort_order']
			];
		}

		$this->load->model('setting/store');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$store_info = [
				'store_id' => 0,
				'name'     => $this->config->get('config_name'),
				'url'      => HTTP_CATALOG
			];

			if ($store_id) {
				$this->load->model('setting/store');

				$store_info = $this->model_setting_store->getStore((int)$store_id);

				if (!$store_info) {
					return ['error' => $this->language->get('error_store')];
				}
			}

			$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/design/';
			$filename = 'banner-' . $banner_info['banner_id'] . '.yaml';

			if (!oc_directory_create($directory, 0777)) {
				return ['error' => sprintf($this->language->get('error_directory'), $directory)];
			}

			if (!file_put_contents($directory . $filename, oc_yaml_encode($image_data))) {
				return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
			}
		}

		return ['success' => $this->language->get('text_task')];
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

		if (!array_key_exists('banner_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('design/banner');

		$banner_info = $this->model_design_banner->getBanner((int)$args['banner_id']);

		if (!$banner_info) {
			return ['error' => $this->language->get('error_banner')];
		}

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/design/banner-' . $banner_info['banner_id'] . '.yaml';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => sprintf($this->language->get('text_delete'), $banner_info['name'])];
	}
}
