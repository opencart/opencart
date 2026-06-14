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
	 * Add Store
	 *
	 * Adds task to generate new store list
	 *
	 * model/setting/store/addStore
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(array &$args): array {
		$this->load->language('task/catalog/banner');

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

		$this->load->model('setting/task');

		$limit = 1000;

		$this->load->model('design/banner');

		$banner_total = $this->model_design_banner->getTotalBanners(['filter_status' => true]);

		for ($i = 1; $i <= ceil($banner_total / $limit); $i++) {
			$task_data = [
				'code'   => 'banner',
				'action' => 'task/catalog/banner.list',
				'args'   => [
					'store_id' => $args['store_id'],
					'start'    => $i * $limit,
					'limit'    => $limit
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'])];
	}

	/*
	 * Article
	 *
	 * Generate Article data files.
	 */
	public function list(array $args = []): array {
		$this->load->model('setting/task');

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

		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true,
			'sort'            => 'date_added',
			'order'           => 'DESC',
			'start'           => $args['start'],
			'limit'           => $args['limit']
		];

		$this->load->model('cms/article');

		$articles = $this->model_cms_article->getArticles($filter_data);

		foreach ($articles as $article) {
			$task_data = [
				'code'   => 'article.' . $store_info['store_id'] . '.' . $article['article_id'],
				'action' => 'task/catalog/article',
				'args'   => [
					'article_id' => $article['article_id'],
					'store_id'   => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_article'), $store_info['name'], $args['start'], $args['limit'])];
	}

	/**
	 * Index
	 *
	 * Generate banner task by banner ID for each store and language.
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

		// Banner
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

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/design/';
		$filename = 'banner-' . $banner_info['banner_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($image_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $banner_info['name'])];
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

		$file = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/design/banner-' . (int)$args['banner_id'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => sprintf($this->language->get('text_delete'), $store_info['name'])];
	}
}
