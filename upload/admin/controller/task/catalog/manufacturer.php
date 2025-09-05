<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Manufacturer
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Manufacturer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/manufacturer');

		$this->load->model('setting/task');

		// Store
		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		// Language
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($stores as $store) {
			foreach ($languages as $language) {
				$task_data = [
					'code'   => 'manufacturer',
					'action' => 'task/catalog/manufacturer.list',
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

	/*
	 *
	 */
	public function list(array $args = []): array {
		$this->load->language('task/catalog/manufacturer');

		$required = [
			'store_id',
			'language_id'
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

		$filter_data = [
			'filter_store_id'    => $store_info['store_id'],
			'filter_language_id' => $language_info['language_id'],
			'filter_status'      => true
		];

		$this->load->model('catalog/manufacturer');

		$manufacturers = $this->model_catalog_manufacturer->getManufacturers($filter_data);

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

		$this->load->model('setting/task');

		$task_data = [
			'code'   => 'ssr',
			'action' => 'task/catalog/ssr',
			'args'   => [
				'route'        => 'product/manufacturer',
				'store_id'     => $store_info['store_id'],
				'language_id'  => $language_info['language_id']
			]
		];

		$this->model_setting_task->addTask($task_data);

		$this->load->model('catalog/product');

		foreach ($manufacturers as $manufacturer) {
			$filter_data = [
				'filter_manufacturer_id' => $manufacturer['manufacturer_id'],
				'filter_store_id'        => $store_info['store_id'],
				'filter_language_id'     => $language_info['language_id'],
				'filter_status'          => true
			];

			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$page_total = ceil($product_total / (int)$this->config->get('config_pagination'));

			foreach ($sorts as $sort) {
				for ($i = 1; $i <= $page_total; $i++) {
					$task_data = [
						'code'   => 'ssr',
						'action' => 'task/catalog/ssr',
						'args'   => [
							'route'           => 'product/manufacturer',
							'manufacturer_id' => $manufacturer['manufacturer_id'],
							'store_id'        => $store_info['store_id'],
							'language_id'     => $language_info['language_id'],
							'sort'            => $sort['sort'],
							'order'           => $sort['order'],
							'page'            => $i
						]
					];

					$this->model_setting_task->addTask($task_data);
				}
			}
		}

		return ['success' => $this->language->get('text_list')];
	}

	public function clear(array $args = []): array {
		$this->load->language('task/catalog/manufacturer');

		$file = DIR_CATALOG . 'view/data/catalog/manufacturer.json';

		if (is_file($file)) {
			unlink($file);
		}

		$files = oc_directory_read(DIR_CATALOG . 'view/data/catalog/', false, '/manufacturer\..+\.json$/');

		foreach ($files as $file) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
