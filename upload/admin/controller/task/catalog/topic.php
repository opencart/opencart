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
		$this->load->language('task/catalog/information');

		$this->load->model('setting/store');
		$this->load->model('setting/task');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'information.list.' . $store_id,
				'action' => 'task/catalog/information.list',
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
		$this->load->language('task/catalog/information');

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

		$information_data = [];

		$this->load->model('catalog/information');

		$information_ids = $this->model_catalog_information->getStoresByStoreId($store_info['store_id']);

		foreach ($information_ids as $information_id) {
			$information_info = $this->model_catalog_information->getInformation($information_id);

			if ($information_info && $information_info['status']) {
				$information_data[] = $information_info + ['description' => $this->model_localisation_country->getDesciptions($information_info['information_id'])];
			}
		}

		$sort_order = [];

		foreach ($information_data as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $information_data);

		$base = DIR_CATALOG . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/catalog/';
		$filename = 'information.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($information_data))) {
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
				'code'   => 'topic.info.' . $store_id,
				'action' => 'task/catalog/topic.info',
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
}