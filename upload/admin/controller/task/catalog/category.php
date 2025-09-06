<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Category
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Category extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates information task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/category');

		$this->load->model('setting/task');

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		$limit = 200;

		$category_total = $this->model_catalog_category->getTotalCategories(['filter_status' => true]);

		$page_total = ceil($category_total / $limit);

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				for ($i = 1; $i <= $page_total; $i++) {
					$task_data = [
						'code'   => 'category',
						'action' => 'task/catalog/category.list',
						'args'   => [
							'store_id'    => $store['store_id'],
							'language_id' => $language['language_id'],
							'page'        => $i
						]
					];

					$this->model_setting_task->addTask($task_data);
				}
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	public function list(array $args = []): array {
		$this->load->language('task/catalog/category');

		$required = [
			'store_id',
			'language_id',
			'page'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore((int)$args['store_id']);

		if (!$store_info) {
			return ['error' => $this->language->get('error_store')];
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		// Sort Order
		$limit = 200;

		$filter_data = [
			'filter_store_id'    => $store_info['store_id'],
			'filter_language_id' => $language_info['language_id'],
			'filter_status'      => true,
			'start'              => (int)$args['page'] * $limit,
			'limit'              => $limit
		];

		$this->load->model('catalog/category');

		$categories = $this->model_catalog_category->getCategories($filter_data);

		$this->load->model('catalog/product');

		foreach ($categories as $category) {
			$category_id = $category['category_id'];

			$filter_data = [
				'filter_category_id' => $category['category_id'],
				'filter_store_id'    => $store_info['store_id'],
				'filter_language_id' => $language_info['language_id'],
				'filter_status'      => true
			];

			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$page_total = ceil($product_total / (int)$this->config->get('config_pagination'));

			foreach ($sorts as $sort) {
				for ($i = 1; $i <= $page_total; $i++) {

				}
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	public function page() {
		// Sort Order
		$sorts = [];

		$sorts[] = [
			'sort'  => 'p.sort_order',
			'order' => 'ASC'
		];

		$sorts[] = [
			'sort'  => 'pd.name',
			'order' => 'ASC'
		];

		$sorts[] = [
			'sort'  => 'pd.name',
			'order' => 'DESC'
		];

		$sorts[] = [
			'sort'  => 'p.price',
			'order' => 'ASC'
		];

		$sorts[] = [
			'sort'  => 'p.price',
			'order' => 'DESC'
		];

		if ($this->config->get('config_review_status')) {
			$sorts[] = [
				'sort'  => 'rating',
				'order' => 'ASC'
			];

			$sorts[] = [
				'sort'  => 'rating',
				'order' => 'DESC'
			];
		}

		$sorts[] = [
			'sort'  => 'p.model',
			'order' => 'ASC'
		];

		$sorts[] = [
			'sort'  => 'p.model',
			'order' => 'DESC'
		];







	}

	public function clear(array $args = []): array {
		$this->load->language('task/catalog/category');

		return ['success' => $this->language->get('text_clear')];
	}
}
