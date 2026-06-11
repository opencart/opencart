<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Store
 *
 * @package Opencart\Admin\Controller\Event
 */
class Store extends \Opencart\System\Engine\Controller {
	/**
	 * Add Store
	 *
	 * Adds task to generate new store list
	 *
	 * model/setting/store/addStore
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function index(array &$args): array {
		$this->load->language('task/catalog/store');

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

		$this->load->model('setting/task');

		$limit = 1000;

		// Articles
		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true
		];

		$this->load->model('cms/article');

		$article_total = $this->model_cms_article->getTotalArticles($filter_data);

		for ($i = 1; $i <= ceil($article_total / $limit); $i++) {
			$task_data = [
				'code'   => 'sale',
				'action' => 'task/catalog/store.article',
				'args'   => [
					'store_id' => $store_info['store_id'],
					'start'    => $i * $limit,
					'limit'    => $limit
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Banner
		$this->load->model('design/banner');

		$banner_total = $this->model_design_banner->getTotalBanners(['filter_status' => true]);

		for ($i = 1; $i <= ceil($banner_total / $limit); $i++) {
			$task_data = [
				'code'   => 'banner',
				'action' => 'task/catalog/store.banner',
				'args'   => [
					'store_id' => $store_info['store_id'],
					'start'    => $i * $limit,
					'limit'    => $limit
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Categories
		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true
		];

		$this->load->model('catalog/category');

		$category_total = $this->model_catalog_category->getTotalCategories($filter_data);

		for ($i = 1; $i <= ceil($category_total / $limit); $i++) {
			$task_data = [
				'code'   => 'category',
				'action' => 'task/catalog/store.category',
				'args'   => [
					'store_id' => $store_info['store_id'],
					'start'    => $i * $limit,
					'limit'    => $limit
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Currency
		$task_data = [
			'code'   => 'currency.' . $store_info['store_id'],
			'action' => 'task/catalog/currency',
			'args'   => ['store_id' => $store_info['store_id']]
		];

		$this->model_setting_task->addTask($task_data);

		// Customer Group
		$customer_group_ids = (array)$this->model_setting_setting->getValue('config_customer_group_list', $store_info['store_id']);

		foreach ($customer_group_ids as $customer_group_id) {
			$task_data = [
				'code'   => 'customer_group.info.' . $store_info['store_id'] . '.' . $customer_group_id,
				'action' => 'task/catalog/customer_group.info',
				'args'   => [
					'customer_group_id' => $customer_group_id,
					'store_id'          => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Filter








		// Information
		$task_data = [
			'code'   => 'information.' . $store_info['store_id'],
			'action' => 'task/catalog/information',
			'args'   => ['store_id' => $store_info['store_id']]
		];

		$this->model_setting_task->addTask($task_data);

		$this->load->model('catalog/information');

		$information_ids = $this->model_catalog_information->getStoresByStoreId($store_info['store_id']);

		foreach ($information_ids as $information_id) {
			$task_data = [
				'code'   => 'information.info.' . $store_info['store_id'] . '.' . $information_id,
				'action' => 'task/catalog/information.info',
				'args'   => [
					'information_id' => $information_id,
					'store_id'       => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Language
		$task_data = [
			'code'   => 'language.' . $store_info['store_id'],
			'action' => 'task/catalog/language',
			'args'   => ['store_id' => $store_info['store_id']]
		];

		$this->model_setting_task->addTask($task_data);

		// Location
		$task_data = [
			'code'   => 'location.' . $store_info['store_id'],
			'action' => 'task/catalog/location',
			'args'   => ['store_id' => $store_info['store_id']]
		];

		$this->model_setting_task->addTask($task_data);

		// Manufacturer
		$task_data = [
			'code'   => 'manufacturer.' . $store_info['store_id'],
			'action' => 'task/catalog/manufacturer',
			'args'   => ['store_id' => $store_info['store_id']]
		];

		$this->model_setting_task->addTask($task_data);

		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true
		];

		$this->load->model('catalog/manufacturer');

		$manufacturer_ids = $this->model_catalog_manufacturer->getManufacturers($filter_data);

		foreach ($manufacturer_ids as $manufacturer_id) {
			$task_data = [
				'code'   => 'manufacturer.info.' . $store_info['store_id'] . '.' . $manufacturer_id,
				'action' => 'task/catalog/manufacturer.info',
				'args'   => [
					'manufacturer_id' => $manufacturer_id,
					'store_id'        => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Return Reason
		$task_data = [
			'code'   => 'return_reason.' . $store_info['store_id'],
			'action' => 'task/catalog/return_reason',
			'args'   => ['store_id' => $store_info['store_id']]
		];

		$this->model_setting_task->addTask($task_data);



		// Setting

		// Store

		// tax_rate

		// Template





		// Topic
		$task_data = [
			'code'   => 'topic.' . $store_info['store_id'],
			'action' => 'task/catalog/topic',
			'args'   => ['store_id' => $store_info['store_id']]
		];

		$this->model_setting_task->addTask($task_data);

		$this->load->model('cms/topic');

		$topic_ids = $this->model_cms_topic->getStoresByStoreId($store_info['store_id']);

		foreach ($topic_ids as $topic_id) {
			$task_data = [
				'code'   => 'topic.info.' . $store_info['store_id'] . '.' . $topic_id,
				'action' => 'task/catalog/topic.info',
				'args'   => [
					'topic_id' => $topic_id,
					'store_id' => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);


		}

		
		// Translation




		$this->model_setting_task->addTask($task_data);


	}



	public function article(array $args = []): array {
		$this->load->model('setting/task');

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

		$filter_data = [
			'filter_store_id' => $store_info['store_id'],
			'filter_status'   => true,
			'sort'            => 'date_added',
			'order'           => 'DESC',
			'start'           => ($page - 1) * $this->config->get('config_pagination'),
			'limit'           => $this->config->get('config_pagination')
		];

		$this->load->model('cms/article');

		$articles = $this->model_cms_article->getArticles($filter_data);

		foreach ($articles as $article) {
			$task_data = [
				'code'   => 'article.' . $store_info['store_id'] . '.' . $article['article_id'],
				'action' => 'task/catalog/article',
				'args'   => [
					'article_id' => $article['article_id'],
					'store_id'   => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_article'), $store_info['name'])];
	}

	public function banner(array $args = []): array {
		$this->load->model('setting/task');

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

		$this->load->model('design/banner');

		$banners = $this->model_design_banner->getBanners();

		foreach ($banners as $banner) {
			$task_data = [
				'code'   => 'banner.' . $store_info['store_id'] . '.' . $banner['banner_id'],
				'action' => 'task/catalog/banner',
				'args'   => [
					'banner_id' => $banner['banner_id'],
					'store_id'  => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_banner'), $store_info['name'])];
	}

	public function category(array $args = []): array {
		$this->load->model('setting/task');


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
			'code'   => 'category.' . $store_info['store_id'],
			'action' => 'task/catalog/category',
			'args'   => ['store_id' => $store_info['store_id']]
		];

		$this->model_setting_task->addTask($task_data);

		$this->load->model('catalog/category');

		$category_ids = $this->model_catalog_category->getStoresByStoreId($store_info['store_id']);

		foreach ($category_ids as $category_id) {
			$task_data = [
				'code'   => 'category.info.' . $store_info['store_id'] . '.' . $category_id,
				'action' => 'task/catalog/category.info',
				'args'   => [
					'category_id' => $category_id,
					'store_id'    => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_category'), $store_info['name'])];
	}

	public function comment(array $args = []): array {





		return ['success' => sprintf($this->language->get('text_comment'), $store_info['name'])];
	}

	public function country(array $args = []): array {
		$this->load->model('setting/task');

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

		// Country
		$task_data = [
			'code'   => 'country.' . $store_info['store_id'],
			'action' => 'task/catalog/country',
			'args'   => ['store_id' => $store_info['store_id']]
		];

		$this->model_setting_task->addTask($task_data);

		$this->load->model('setting/setting');

		$country_ids = (array)$this->model_setting_setting->getValue('config_country_list', $store_info['store_id']);

		foreach ($country_ids as $country_id) {
			$task_data = [
				'code'   => 'country.info.' . $store_info['store_id'] . '.' . $country_id,
				'action' => 'task/catalog/country.info',
				'args'   => [
					'country_id' => $country_id,
					'store_id'   => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_country'), $store_info['name'])];
	}

	public function filter(array $args = []): array {
		$this->load->model('setting/task');

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

		$this->load->model('catalog/filter');

		$filter_groups = $this->model_catalog_filter->getFilterGroups();

		foreach ($filter_groups as $filter_group) {
			$task_data = [
				'code'   => 'filter.info.' . $store_info['store_id'] . '.' . $filter_group['filter_group_id'],
				'action' => 'task/catalog/filter.info',
				'args'   => [
					'filter_group_id' => $filter_group['filter_group_id'],
					'store_id'        => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_filter'), $store_info['name'])];
	}

	public function information(array $args = []): array {
		$this->load->model('setting/task');

		// Store

		return ['success' => sprintf($this->language->get('text_information'), $store_info['name'])];
	}

	public function manufacturer(array $args = []): array {
		$this->load->model('setting/task');

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
			'code'   => 'manufacturer.' . $store_info['store_id'],
			'action' => 'task/catalog/manufacturer',
			'args'   => ['store_id' => $store_info['store_id']]
		];

		$this->model_setting_task->addTask($task_data);

		$this->load->model('catalog/manufacturer');

		$manufacturer_ids = $this->model_catalog_manufacturer->getStoresByStoreId($store_info['store_id']);

		foreach ($manufacturer_ids as $manufacturer_id) {
			$task_data = [
				'code'   => 'manufacturer.info.' . $store_info['store_id'] . '.' . $manufacturer_id,
				'action' => 'task/catalog/manufacturer.info',
				'args'   => [
					'manufacturer_id' => $manufacturer_id,
					'store_id'        => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_manufacturer'), $store_info['name'])];
	}

	public function product(array $args = []): array {
		$this->load->model('setting/task');

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

		// Products
		$this->load->model('catalog/product');

		$product_ids = $this->model_catalog_product->getStoresByStoreId($store_info['store_id']);

		foreach ($product_ids as $product_id) {
			$task_data = [
				'code'   => 'product.' . $store_info['store_id'] . '.' . $product_id,
				'action' => 'task/catalog/product',
				'args'   => [
					'product_id' => $product_id,
					'store_id'   => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_product'), $store_info['name'])];
	}

	public function review(array $args = []): array {
		$this->load->model('setting/task');

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


		return ['success' => sprintf($this->language->get('text_review'), $store_info['name'])];
	}

	public function sass(array $args = []): array {
		$this->load->model('setting/task');

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

		/*
		$task_data = [
			'code'   => 'sass.' . $store_info['store_id'],
			'action' => 'task/catalog/sass',
			'args'   => ['store_id'   => $store_info['store_id']]
		];
			*/

		$this->load->model('setting/task');

		//$this->model_setting_task->addTask($task_data);

		return ['success' => sprintf($this->language->get('text_sass'), $store_info['name'])];
	}

	public function setting(array $args = []): array {
		$this->load->model('setting/task');

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

		return ['success' => sprintf($this->language->get('text_setting'), $store_info['name'])];
	}

	public function tag(array $args = []): array {
		$this->load->model('setting/task');

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

		return ['success' => sprintf($this->language->get('text_tag'), $store_info['name'])];
	}

	public function taxRate(array $args = []): array {
		$this->load->model('setting/task');

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

		$this->load->model('localisation/geo_zone');

		$geo_zones = $this->model_localisation_geo_zone->getGeoZones();

		foreach ($geo_zones as $geo_zone) {
			$task_data = [
				'code'   => 'tax_rate.' . $store_info['store_id'] . '.' . $geo_zone['geo_zone_id'],
				'action' => 'task/catalog/tax_rate',
				'args'   => [
					'geo_zone_id' => $geo_zone['geo_zone_id'],
					'store_id'    => $store_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => sprintf($this->language->get('text_tax_rate'), $store_info['name'])];
	}

	public function template(array $args = []): array {
		$this->load->model('setting/task');

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

		return ['success' => sprintf($this->language->get('text_template'), $store_info['name'])];
	}


	public function translation(array $args = []): array {
		$this->load->model('setting/task');

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

		return ['success' => sprintf($this->language->get('text_translation'), $store_info['name'])];
	}

	/**
	 * Delete Store
	 *
	 * Adds task to generate new store list
	 *
	 * model/setting/store/deleteStore
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function deleteStore(string &$route, array &$args): array {
		// Language
		$task_data = [
			'code'   => 'language',
			'action' => 'task/catalog/language',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Currency
		if ($this->config->get('config_currency_auto')) {
			$task_data = [
				'code'   => 'currency',
				'action' => 'task/catalog/currency',
				'args'   => []
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Location
		$task_data = [
			'code'   => 'location',
			'action' => 'task/catalog/location',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'store',
			'action' => 'task/admin/store',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_translation')];
	}
}
