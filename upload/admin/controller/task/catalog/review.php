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
	 * Generates return reason task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/review');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'review',
					'action' => 'task/catalog/review.list',
					'args'   => [
						'store_id'    => $store['store_id'],
						'language_id' => $language['language_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * List
	 *
	 * Generates return reason list file.
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/return_reason');

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

		$this->load->model('localisation/return_reason');

		$return_reasons = $this->model_localisation_return_reason->getReturnReasons();

		foreach ($return_reasons as $return_reason) {
			$description_info = $this->model_localisation_return_reason->getDescription($return_reason['return_reason_id'], $language_info['language_id']);

			if ($description_info) {
				$return_reason_data[$return_reason['return_reason_id']] = $description_info + $return_reason;
			}
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

	public function clear(array $args = []): array {
		$this->load->language('task/catalog/return_reason');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$file = DIR_CATALOG . 'view/data/' . parse_url($store['url'], PHP_URL_HOST) . '/' . $language['code'] . '/localisation/return_reason.json';

				if (is_file($file)) {
					unlink($file);
				}
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}

	public function rating(array $args = []): array {

		$this->load->language('catalog/review');

		$json = [];

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'catalog/review')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Product
			$this->load->model('catalog/product');

			// Review
			$this->load->model('catalog/review');

			$limit = 10;

			$product_data = [
				'start' => ($page - 1) * $limit,
				'limit' => $limit
			];

			$results = $this->model_catalog_product->getProducts($product_data);

			foreach ($results as $result) {
				$this->model_catalog_product->editRating($result['product_id'], $this->model_catalog_review->getRating($result['product_id']));
			}

			// Total Products
			$product_total = $this->model_catalog_product->getTotalProducts();

			$start = ($page - 1) * $limit;
			$end = $start > ($product_total - $limit) ? $product_total : ($start + $limit);

			if ($end < $product_total) {
				$json['text'] = sprintf($this->language->get('text_next'), $start, $end, $product_total);

				$json['next'] = $this->url->link('catalog/review.sync', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');

				$json['next'] = '';
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
