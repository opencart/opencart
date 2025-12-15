<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Review
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Review extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate review task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/review');

		$this->load->model('setting/task');

		$limit = 10;

		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$stores = array_merge($stores, $this->model_setting_store->getStores());

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$product_total = $this->model_catalog_product->getTotalProducts();

				$page_total = ceil($product_total / $limit);

				for ($i = 1; $i <= $page_total; $i++) {
					$start = $i * $limit;

					$task_data = [
						'code'   => 'review',
						'action' => 'task/catalog/review.list',
						'args'   => [
							'store_id'    => $store['store_id'],
							'language_id' => $language['language_id'],
							'start'       => $start,
							'limit'       => $limit
						]
					];

					$this->model_setting_task->addTask($task_data);
				}
			}
		}

		return ['success' => $this->language->get('text_task')];
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
		$this->load->language('task/catalog/review');

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$return_reason_data = [];

		$this->load->model('catalog/review');

		$return_reasons = $this->model_catalog_review->getReviews();

		foreach ($return_reasons as $return_reason) {

		}

		$base = DIR_APPLICATION . 'view/data/';
		$directory = parse_url($store_info['url'], PHP_URL_HOST) . '/' . $language_info['code'] . '/localisation/';
		$filename = 'return_reason.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($return_reason_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
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
		$this->load->language('task/catalog/return_reason');

		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$stores = array_merge($stores, $this->model_setting_store->getStores());

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$file = DIR_CATALOG . 'view/data/' . parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/localisation/review.json';

				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
