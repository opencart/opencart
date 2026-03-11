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
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'topic.list.' . $store_id,
				'action' => 'task/catalog/topic.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * _list
	 *
	 * Generate country list by store and language.
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

		$this->load->model('cms/topic');

		$topic_ids = $this->model_cms_topic->getStores($store_info['store_id']);

		foreach ($topic_ids as $topic_id) {
			$topic_info = $this->model_cms_topic->getTopic($topic_id);

			if ($topic_info && $topic_info['status']) {
				$topic_data[] = $topic_info + ['description' => $this->model_cms_topic->getDescriptions($topic_info['topic_id'])];
			}
		}

		$sort_order = [];

		foreach ($topic_data as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $topic_data);

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/cms/';
		$filename = 'topic.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($topic_data))) {
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

		$this->load->model('cms/topic');

		$topic_info = $this->model_cms_topic->getTopic((int)$args['topic_id']);

		if (!$topic_info || !$topic_info['status']) {
			return ['error' => $this->language->get('error_topic')];
		}

		$this->load->model('setting/task');

		$store_ids = $this->model_cms_topic->getStores($topic_info['topic_id']);

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'topic._info.' . $store_id,
				'action' => 'task/catalog/topic._info',
				'args'   => [
					'topic_id' => $topic_info['topic_id'],
					'store_id' => $store_id
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
	public function _info(array $args = []): array {
		$this->load->language('task/catalog/topic');

		if (!array_key_exists('topic_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

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

		$this->load->model('cms/topic');

		$topic_info = $this->model_cms_topic->getTopic((int)$args['topic_id']);

		if (!$topic_info || !$topic_info['status']) {
			return ['error' => $this->language->get('error_topic')];
		}

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/cms/';
		$filename = 'topic-' . $topic_info['topic_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($topic_info + ['description' => $this->model_cms_topic->getDescriptions($topic_info['topic_id'])]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $store_info['name'], $topic_info['name'])];
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

		$this->load->model('cms/topic');

		$topic_info = $this->model_cms_topic->getTopic((int)$args['topic_id']);

		if (!$topic_info) {
			return ['error' => $this->language->get('error_topic')];
		}

		$this->load->model('setting/store');

		$store_urls = [HTTP_CATALOG, ...array_column($this->model_setting_store->getStores(), 'url')];

		foreach ($store_urls as $store_url) {
			$file = DIR_CATALOG . 'view/data/' . parse_url($store_url, PHP_URL_HOST) . '/cms/topic-' . $topic_info['topic_id'] . '.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => sprintf($this->language->get('text_delete'), $topic_info['title'])];
	}
}