<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Article
 *
 * Generates article information for all stores.
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

		$store_ids = $this->model_cms_article->getStores($article_info['article_id']);

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

			$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/cms/';
			$filename = 'article-' . $article_info['article_id'] . '.yaml';

			if (!oc_directory_create($directory, 0777)) {
				return ['error' => sprintf($this->language->get('error_directory'), $directory)];
			}

			if (!file_put_contents($directory . $filename, oc_yaml_encode($article_data))) {
				return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
			}
		}

		return ['success' => sprintf($this->language->get('text_info'), $article_info['name'])];
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

		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle((int)$args['article_id']);

		if (!$article_info) {
			return ['error' => $this->language->get('error_article')];
		}

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/cms/article-' . $article_info['article_id'] . '.yaml';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => sprintf($this->language->get('text_delete'), $article_info['name'])];
	}
}
