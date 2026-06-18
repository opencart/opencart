<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Article
 *
 * Generates article information.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Article extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate article task list.
	 *
	 * @param array<int, mixed> $args
	 *
	 * @return array
	 */
	public function index(array &$args): array {
		$this->load->language('task/catalog/article');

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

		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true
		];

		$this->load->model('cms/article');

		$article_total = $this->model_cms_article->getTotalArticles($filter_data);

		for ($i = 1; $i <= ceil($article_total / $limit); $i++) {
			$task_data = [
				'code'   => 'article.list.' . $store_info['store_id'],
				'action' => 'task/catalog/article.list',
				'args'   => [
					'store_id' => $store_info['store_id'],
					'start'    => $i * $limit,
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
	 * Generate All Article files.
	 *
	 * @param array<int, mixed> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/article');

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
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true,
			'sort'            => 'date_added',
			'order'           => 'DESC',
			'start'           => $args['start'],
			'limit'           => $args['limit']
		];

		$this->load->model('cms/article');

		$results = $this->model_cms_article->getArticles($filter_data);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'article.info.' . $store_info['store_id'] . '.' . $result['article_id'],
				'action' => 'task/catalog/article.info',
				'args'   => [
					'article_id' => $result['article_id'],
					'store_id'   => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'], $args['start'], $args['limit'])];
	}

	/**
	 * Info
	 *
	 * Generate article task by article ID for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/article');

		if (!array_key_exists('article_id', $args)) {
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

		// Article
		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle((int)$args['article_id']);

		if (!$article_info || !$article_info['status'] || !in_array($store_info['store_id'], $this->model_cms_article->getStores($article_info['article_id']))) {
			return ['error' => $this->language->get('error_article')];
		}

		// Description
		$description_data = [];

		$descriptions = $this->model_cms_article->getDescriptions($article_info['article_id']);

		foreach ($descriptions as $code => $description) {
			$description_data[$code] = [
				'name'             => $description['name'],
				'description'      => $description['description'],
				'image'            => $description['image'],
				'tag'              => $description['tag'],
				'meta_title'       => $description['meta_title'],
				'meta_description' => $description['meta_description'],
				'meta_keyword'     => $description['meta_keyword']
			];
		}

		$article_data = [
			'article_id'    => $article_info['article_id'],
			'description'   => $description_data,
			'topic_id'      => $article_info['topic_id'],
			'author'        => $article_info['author'],
			'rating'        => $article_info['rating'],
			'status'        => $article_info['status'],
			'date_added'    => $article_info['date_added'],
			'date_modified' => $article_info['date_modified']
		];

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/cms/article/';
		$filename = 'article-' . $article_info['article_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($article_data))) {
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

		if (!array_key_exists('article_id', $args)) {
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

		$file = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/cms/article-' . (int)$args['article_id'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => sprintf($this->language->get('text_delete'), $store_info['name'])];
	}
}
