<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Article
 *
 * Generates article data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Article extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate article task by article ID for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/article');

		if (!array_key_exists('article_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle((int)$args['article_id']);

		if (!$article_info || !$article_info['status']) {
			return ['error' => $this->language->get('error_article')];
		}

		$this->load->model('setting/task');

		$store_ids = $this->model_cms_article->getStores($article_info['article_id']);

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'article.info.' . $store_id . '.' . $article_info['article_id'],
				'action' => 'task/catalog/article.info',
				'args'   => [
					'article_id' => $article_info['article_id'],
					'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * Info
	 *
	 * Generate country information.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/article');

		// Validate
		$required = [
			'article_id',
			'store_id'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		// Store
		$store_info = [
			'name' => $this->config->get('config_name'),
			'url'  => HTTP_CATALOG
		];

		if ($args['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

			if (!$store_info) {
				return ['error' => $this->language->get('error_store')];
			}
		}

		// Article
		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle((int)$args['article_id']);

		if (!$article_info || !$article_info['status']) {
			return ['error' => $this->language->get('error_article')];
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/cms/';
		$filename = 'article-' . $article_info['article_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($article_info + ['description' => $this->model_cms_article->getDescriptions($article_info['article_id'])]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $article_info['name'])];
	}

	/**
	 * Delete
	 *
	 * Delete generated JSON country files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/catalog/article');

		$this->load->model('setting/store');

		$stores = array_merge(['url' => HTTP_CATALOG], $this->model_setting_store->getStores());

		foreach ($stores as $store) {
			$base = DIR_CATALOG . 'view/data/';
			$directory = parse_url($store['url'], PHP_URL_HOST) . '/cms/';

			$file = $base . $directory . 'country.json';

			if (is_file($file)) {
				unlink($file);
			}

			$files = oc_directory_read($base . $directory, false, '/country\-.+\.json$/');

			foreach ($files as $file) {
				unlink($file);
			}

		}

		return ['success' => $this->language->get('text_clear')];
	}
}
