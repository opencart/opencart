<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Review
 *
 * Generates review list data for all stores.
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Review extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate review list task for each store and language.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/review');

		$this->load->model('setting/task');

		// Review
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct((int)$args['product_id']);

		if (!$product_info || !$product_info['status']) {
			return ['error' => $this->language->get('error_review')];
		}

		// Stores
		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$store_ids = [0, ...array_column($this->model_setting_store->getStores(), 'store_id')];

		foreach ($store_ids as $store_id) {
			$language_ids = $this->model_setting_setting->getValue('config_language_list', $store_id);

			foreach ($language_ids as $language_id) {
				$task_data = [
					'code'   => 'review',
					'action' => 'task/catalog/review.list',
					'args'   => [
						'store_id'    => $store_id,
						'language_id' => $language_id,
						'product_id'  => $product_id,
					]
				];

				$this->model_setting_task->addTask($task_data);
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

		$limit = 10;
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
