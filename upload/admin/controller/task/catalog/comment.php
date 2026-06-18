<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Comment
 *
 * Generates comment list data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Comment extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate review list task for each store.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/comment');

		if (!array_key_exists('article_id', $args)) {
			//return ['error' => $this->language->get('error_required')];
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
				//return ['error' => $this->language->get('error_store')];
			}
		}

		// Article
		$this->load->model('cms/article');

		//$article_info = $this->model_cms_article->getArticle((int)$args['article_id']);

		//if (!$article_info || !$article_info['status'] || !in_array($store_info['store_id'], $this->model_cms_article->getStores($article_info['article_id']))) {
			//return ['error' => $this->language->get('error_article')];
		//}

		$limit = 1000;

		$comment_total = $this->model_cms_article->getTotalComments(['filter_status' => true]);

		for ($i = 1; $i <= ceil($comment_total / $limit); $i++) {
			$start = $i * $limit;

			$task_data = [
				'code'   => 'comment.list.' . $store_info['store_id'],
				'action' => 'task/catalog/comment.list',
				'args'   => [
					//'article_id' => $article_info['article_id'],
					'store_id'   => $store_info['store_id'],
					'start'      => $start,
					'limit'      => $limit
				]
			];

			//$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_task'), $store_info['name'])];
	}

	/**
	 * List
	 *
	 * Generate JSON review list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/comment');

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

		$results = $this->model_cms_article->getComments(['filter_status' => true]);

		foreach ($results as $result) {

		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'])];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON review files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/review');

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_OPENCART . 'shop/' . parse_url($store_url, PHP_URL_HOST) . '/catalog/review.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
