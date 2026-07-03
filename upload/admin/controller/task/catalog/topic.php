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
	 * Index
	 *
	 * Generate topic task list.
	 *
	 * @param array<int, mixed> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/topic');

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

		$task_data = [
			'code'   => 'topic.list.' . $store_info['store_id'],
			'action' => 'task/catalog/topic.list',
			'args'   => ['store_id' => $store_info['store_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true,
			'sort'            => 'sort_order',
			'order'           => 'ASC',
		];

		$this->load->model('cms/topic');

		$results = $this->model_cms_topic->getTopics($filter_data);

		foreach ($results as $result) {
			$task_data = [
				'code'   => 'topic.info.' . $store_info['store_id'] . '.' . $result['topic_id'],
				'action' => 'task/catalog/topic.info',
				'args'   => [
					'topic_id' => $result['topic_id'],
					'store_id' => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'topic.article.' . $store_info['store_id'] . '.' . $result['topic_id'],
				'action' => 'task/catalog/topic.article',
				'args'   => [
					'topic_id' => $result['topic_id'],
					'store_id' => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_task'), $store_info['name'])];
	}

	/**
	 * List
	 *
	 * Generate information task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/topic');

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

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/cms/';
		$filename = 'topic.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($topic_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $store_info['name'])];
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

		// Topic
		$this->load->model('cms/topic');

		$topic_info = $this->model_cms_topic->getTopic((int)$args['topic_id']);

		if (!$topic_info || !$topic_info['status'] || !in_array($store_info['store_id'], $this->model_cms_topic->getStores($topic_info['topic_id']))) {
			return ['error' => $this->language->get('error_topic')];
		}

		// Description
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

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/cms/';
		$filename = 'topic-' . $topic_info['topic_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($topic_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $topic_info['name'])];
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

		if (!array_key_exists('topic_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

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

		$this->load->model('cms/topic');

		$topic_info = $this->model_cms_topic->getTopic((int)$args['topic_id']);

		if (!$topic_info || !$topic_info['status'] || !in_array($store_info['store_id'], $this->model_cms_topic->getStores($topic_info['topic_id']))) {
			return ['success' => $this->language->get('error_topic')];
		}

		$directory = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/cms/';
		$filename = 'article.topic-' . $topic_info['topic_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		$filter_data = [
			'filter_store_id'  => $store_info['store_id'],
			'filter_topic_id'  => $topic_info['topic_id'],
			'filter_status'    => true,
			'sort'             => 'sort_order',
			'order'            => 'ASC',
		];

		$this->load->model('cms/article');

		if (!file_put_contents($directory . $filename, json_encode($this->model_cms_article->getArticles($filter_data)))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_article'), $store_info['name'], $topic_info['name'])];
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

		$file = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/cms/topic-' . (int)$args['topic_id'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		$file = DIR_OPENCART . 'shop/' . parse_url($store_info['url'], PHP_URL_HOST) . '/data/cms/topic.article-' . (int)$args['topic_id'] . '.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => sprintf($this->language->get('text_delete'), $store_info['name'])];
	}
}