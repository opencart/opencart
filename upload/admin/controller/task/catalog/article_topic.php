<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Article Topic
 *
 * Generates category information for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class ArticleTopic extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate product category task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/article_topic');

		$this->load->model('cms/topic');

		$topic_info = $this->model_cms_topic->getTopic((int)$args['topic_id']);

		if (!$topic_info || !$topic_info['status']) {
			return ['success' => $this->language->get('error_topic')];
		}

		$article_data = [];

		$this->load->model('cms/article');

		$article_ids = $this->model_cms_article->getArticlesBy($topic_info['topic_id']);

		foreach ($article_ids as $article_id) {
			$store_ids = $this->model_cms_article->getStores($article_id);

			if (in_array($store_info['store_id'], $store_ids)) {
				$article_data[] = $article_id;
			}
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










			$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
			$filename = 'article_topic-' . $topic_info['category_id'] . '.csv';

			if (!oc_directory_create($directory, 0777)) {
				return ['error' => sprintf($this->language->get('error_directory'), $directory)];
			}

			if (!file_put_contents($directory . $filename, implode(',', $article_data))) {
				return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
			}
		}

		return ['success' => $this->language->get('text_task')];
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
		$this->load->language('task/catalog/article_topic');

		if (!array_key_exists('topic_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/cms/article_topic-' . (int)$args['topic_id'] . '.csv';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_delete')];
	}
}