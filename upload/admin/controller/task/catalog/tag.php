<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Tag
 *
 * Generates tag information for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Tag extends \Opencart\System\Engine\Controller {
	/**
	 * List
	 *
	 * Generate csv file with products ID's related to tags.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/tag');

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

		$this->load->model('catalog/tag');

		$tag_total = $this->model_catalog_tag->getTotalTags();

		for ($i = 0; $i < ceil($tag_total / $limit); $i++) {
			$start = $i * $limit;

			$task_data = [
				'code'   => 'tag.list.' . $store_info['store_id'] . '.' . $start . '.' . $limit,
				'action' => 'task/catalog/tag.list',
				'args'   => [
					'store_id' => $store_info['store_id'],
					'start'    => $start,
					'limit'    => $limit
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_task'), $store_info['name'])];
	}

	/*
	 * List
	 *
	 * Generate all tag files.
	 *
	 * @param array<int, mixed> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/tag');

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

		$filter_data = [
			'start' => $args['start'],
			'limit' => $args['limit']
		];

		$this->load->model('catalog/tag');

		$results = $this->model_catalog_product->getTags($filter_data);

		foreach ($results as $result) {
			// Add task to generate all article tag files
			$task_data = [
				'code'   => 'tag.article.' . $store_info['store_id'] . '.' . $result['tag_id'],
				'action' => 'task/catalog/tag.article',
				'args'   => [
					'tag_id'   => $result['tag_id'],
					'store_id' => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);

			// Add task to generate all product tag files
			$task_data = [
				'code'   => 'tag.product.' . $store_info['store_id'] . '.' . $result['tag_id'],
				'action' => 'task/catalog/tag.product',
				'args'   => [
					'tag_id'   => $result['tag_id'],
					'store_id' => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $args['start'], $args['limit'])];
	}

	public function article(array $args = []): array {
		$this->load->language('task/catalog/tag');

		if (!array_key_exists('tag_id', $args)) {
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

		// Tag
		$this->load->model('catalog/tag');

		$tag_info = $this->model_catalog_tag->getTag((int)$args['tag_id']);

		if (!$tag_info) {
			return ['error' => $this->language->get('error_tag')];
		}

		$filter_data = [
			'filter_tag_id'    => $tag_info['tag_id'],
			'filter_store_id'  => $store_info['store_id'],
			'filter_status'    => true,
			'sort'             => 'date_added',
			'order'            => 'ASC',
		];

		$this->load->model('catalog/product');

		$article_data = array_column($this->model_catalog_product->getArticles($filter_data), 'article_id');

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/cms/';
		$filename = 'tag-article-' . $tag_info['tag'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, implode(',', $article_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_article'), $store_info['name'], $tag_info['tag'])];
	}

	public function product(array $args = []): array {
		$this->load->language('task/catalog/tag');

		if (!array_key_exists('tag_id', $args)) {
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

		// Tag
		$this->load->model('catalog/tag');

		$tag_info = $this->model_catalog_tag->getTag((int)$args['tag_id']);

		if (!$tag_info) {
			return ['error' => $this->language->get('error_tag')];
		}

		$filter_data = [
			'filter_tag_id'   => $tag_info['tag_id'],
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true,
			'sort'            => 'name',
			'order'           => 'ASC',
		];

		$this->load->model('catalog/product');

		$product_data = array_column($this->model_catalog_product->getProducts($filter_data), 'product_id');

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/catalog/';
		$filename = 'tag-product-' . $tag_info['tag'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, implode(',', $product_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_product'), $store_info['name'], $tag_info['tag'])];

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
		$this->load->language('task/catalog/tag');

		if (!array_key_exists('tag_id', $args)) {
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

		$file = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/tag/tag-' . (int)$args['tag_id'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		$file = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/tag/tag-' . (int)$args['tag_id'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => sprintf($this->language->get('text_delete'), $store_info['name'])];
	}

	public function autocomplete() {

	}
}

