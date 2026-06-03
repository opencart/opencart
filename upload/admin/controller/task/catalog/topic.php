<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Topic
 *
 *  Generates topic data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Topic extends \Opencart\System\Engine\Controller {
	/**
	 * List
	 *
	 * Generate information task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/topic');

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

			$topic_data = [];

			$filter_data = [
				'filter_store_id' => $store_info['store_id'],
				'filter_status'   => true,
				'sort'            => 'sort_order',
				'order'           => 'ASC',
			];

			$this->load->model('cms/topic');

			$results = $this->model_cms_topic->getTopics($filter_data);

			foreach ($results as $result) {
				$description_data = [];

				$descriptions = $this->model_cms_topic->getDescriptions($result['topic_id']);

				foreach ($descriptions as $code => $description) {
					$description_data[$code] = [
						'name'        => $description['name'],
						'description' => $description['description'],
						'image'       => $description['image']
					];
				}

				$topic_data[] = [
					'topic_id'    => $result['topic_id'],
					'description' => $description_data
				];
			}

			$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/cms/';
			$filename = 'topic.yaml';

			if (!oc_directory_create($directory, 0777)) {
				return ['error' => sprintf($this->language->get('error_directory'), $directory)];
			}

			if (!file_put_contents($directory . $filename, oc_yaml_encode($topic_data))) {
				return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
			}
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Info
	 *
	 * Generate all topic data.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/topic');

		if (!array_key_exists('topic_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('cms/topic');

		$topic_info = $this->model_cms_topic->getTopic((int)$args['topic_id']);

		if (!$topic_info || !$topic_info['status']) {
			return ['error' => $this->language->get('error_topic')];
		}

		$description_data = [];

		$descriptions = $this->model_cms_topic->getDescriptions($topic_info['topic_id']);

		foreach ($descriptions as $code => $description) {
			$description_data[$code] = [
				'name'             => $description['name'],
				'description'      => $description['description'],
				'image'            => $description['image'],
				'meta_title'       => $description['meta_title'],
				'meta_description' => $description['meta_description'],
				'meta_keyword'     => $description['meta_keyword']
			];
		}

		$topic_data = [
			'topic_id'    => $topic_info['topic_id'],
			'description' => $description_data
		];

		$store_ids = $this->model_cms_topic->getStores($topic_info['topic_id']);

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
			$filename = 'topic-' . $topic_info['topic_id'] . '.yaml';

			if (!oc_directory_create($directory, 0777)) {
				return ['error' => sprintf($this->language->get('error_directory'), $directory)];
			}

			if (!file_put_contents($directory . $filename, oc_yaml_encode($topic_data))) {
				return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
			}
		}

		return ['success' => sprintf($this->language->get('text_info'), $topic_info['title'])];
	}

	/**
	 * Article
	 *
	 * Generate article list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function article(array $args = []): array {
		$this->load->language('task/catalog/topic');

		$this->load->model('cms/topic');

		$topic_info = $this->model_cms_topic->getTopic((int)$args['topic_id']);

		if (!$topic_info || !$topic_info['status']) {
			return ['success' => $this->language->get('error_topic')];
		}

		$this->load->model('cms/article');

		$this->load->model('setting/store');

		$store_ids = $this->model_cms_topic->getStores($topic_info['topic_id']);

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

			$filter_data = [
				'filter_store_id'  => $store_info['store_id'],
				'filter_topic_id'  => $topic_info['topic_id'],
				'filter_status'    => true,
				'sort'             => 'sort_order',
				'order'            => 'ASC',
			];

			$directory = DIR_CATALOG . 'view/data/' . parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
			$filename = 'article_topic-' . $topic_info['topic_id'] . '.csv';

			if (!oc_directory_create($directory, 0777)) {
				return ['error' => sprintf($this->language->get('error_directory'), $directory)];
			}

			if (!file_put_contents($directory . $filename, implode(',', array_column($this->model_cms_article->getArticles($filter_data), 'article_id')))) {
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
		$this->load->language('task/catalog/topic');

		if (!array_key_exists('topic_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/cms/topic-' . (int)$args['topic_id'] . '.yaml';

			if (is_file($file)) {
				unlink($file);
			}

			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/cms/topic-article-' . (int)$args['topic_id'] . '.csv';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_delete')];
	}
}