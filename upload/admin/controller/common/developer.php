<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Developer
 *
 * Can be loaded using $this->load->controller('common/developer');
 *
 * @package Opencart\Admin\Controller\Common
 */
class Developer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('common/developer');

		// Stores
		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$data['stores'] = array_merge($data['stores'], $this->model_setting_store->getStores());

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('common/developer', $data));
	}

	public function rebuild(): void {
		$this->load->language('common/developer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Store
		$store_info = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url'      => HTTP_CATALOG
		];

		if ($this->request->get['store_id']) {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore((int)$this->request->get['store_id']);

			if (!$store_info) {
				$json['error'] = $this->language->get('error_store');
			}
		}

		if (!$json) {
			/*
			$task_data = [
				'code'   => 'sass.' . $store_info['store_id'],
				'action' => 'task/catalog/sass',
				'args'   => ['store_id'   => $store_info['store_id']]
			];
			*/
			$this->load->model('setting/task');

			//$this->model_setting_task->addTask($task_data);

			// Articles
			$this->load->model('cms/article');

			$articles = $this->model_cms_article->getArticles();

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

			// Banner
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

			// Categories
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

			// Filter Group
			$this->load->model('catalog/filter_group');

			$filter_groups = $this->model_catalog_filter->getFilterGroups();

			foreach ($filter_groups as $filter_group) {
				$task_data = [
					'code'   => 'filter_group.info.' . $store_info['store_id'] . '.' . $filter_group['filter_group_id'],
					'action' => 'task/catalog/filter_group.info',
					'args'   => [
						'filter_group_id' => $filter_group['filter_group_id'],
						'store_id'        => $store_info['store_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}

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

			// Products
			$this->load->model('catalog/product');

			$products = $this->model_catalog_product->getStoresByStoreId($store_info['store_id']);

			foreach ($products as $product) {
				$task_data = [
					'code'   => 'product.' . $store_info['store_id'] . '.' . $product['product_id'],
					'action' => 'task/catalog/product',
					'args'   => [
						'product_id' => $product['product_id'],
						'store_id'   => $store_info['store_id']
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

			// Template

			// Topic
			$task_data = [
				'code'   => 'topic.' . $store_info['store_id'],
				'action' => 'task/catalog/topic',
				'args'   => ['store_id' => $store_info['store_id']]
			];

			$this->model_setting_task->addTask($task_data);

			$topics = $this->model_cms_topic->getStoresByStoreId($store_info['store_id']);

			foreach ($topics as $topic) {
				$task_data = [
					'code'   => 'topic.info.' . $store_info['store_id'] . '.' . $topic['topic_id'],
					'action' => 'task/catalog/topic.info',
					'args'   => [
						'topic_id' => $topic['topic_id'],
						'store_id' => $store_info['store_id']
					]
				];

				$this->model_setting_task->addTask($task_data);
			}

			// Translation






			$json['success'] = $this->language->get('text_rebuild_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Theme
	 *
	 * @return void
	 */
	public function template(): void {
		$this->load->language('common/developer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$directories = oc_directory_read(DIR_CACHE . 'template/');

			if ($directories) {
				foreach ($directories as $directory) {
					if (is_dir($directory)) {
						oc_directory_delete($directory);
					}
				}
			}

			$json['success'] = $this->language->get('text_theme_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * SASS Catalog
	 *
	 * Generate catalog SASS file.
	 *
	 * @return void
	 */
	public function sass(): void {
		$this->load->language('common/developer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_CATALOG . 'view/sass/stylesheet.scss';

		if (!is_file($file)) {
			$json['error'] = sprintf($this->language->get('error_file'), $file);
		}

		if (!$json) {
			$task_data = [
				'code'   => 'sass',
				'action' => 'task/catalog/sass',
				'args'   => []
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);

			$json['success'] = $this->language->get('text_sass_catalog_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	/**
	 * Clear
	 *
	 * @return void
	 */
	public function clear(): void {
		$this->load->language('common/developer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$files = oc_directory_read(DIR_CATALOG . 'view/html/');

			foreach ($files as $file) {
				oc_directory_delete($file);
			}

			$json['success'] = $this->language->get('text_html_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Cache
	 *
	 * @return void
	 */
	public function cache(): void {
		$this->load->language('common/developer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$files = oc_directory_read(DIR_CACHE);

			foreach ($files as $file) {
				if (str_starts_with(basename($file), 'cache.') && is_file($file)) {
					oc_file_delete($file);
				}
			}

			$json['success'] = $this->language->get('text_cache_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Vendor
	 *
	 * Generate new autoloader file
	 *
	 * @return void
	 */
	public function vendor(): void {
		$this->load->language('common/developer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Generate php autoload file
			$code = '<?php' . "\n";

			$files = glob(DIR_STORAGE . 'vendor/*/*/composer.json');

			foreach ($files as $file) {
				$output = json_decode(file_get_contents($file), true);

				$code .= '// ' . $output['name'] . "\n";

				if (isset($output['autoload'])) {
					$directory = substr(dirname($file), strlen(DIR_STORAGE . 'vendor/'));

					// Autoload psr-4 files
					if (isset($output['autoload']['psr-4'])) {
						$autoload = $output['autoload']['psr-4'];

						foreach ($autoload as $namespace => $path) {
							if (!is_array($path)) {
								$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . rtrim($path, '/') . '/' . '\', true);' . "\n";
							} else {
								foreach ($path as $value) {
									$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . rtrim($value, '/') . '/' . '\', true);' . "\n";
								}
							}
						}
					}

					// Autoload psr-0 files
					if (isset($output['autoload']['psr-0'])) {
						$autoload = $output['autoload']['psr-0'];

						foreach ($autoload as $namespace => $path) {
							if (!is_array($path)) {
								$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . rtrim($path, '/') . '/' . '\', true);' . "\n";
							} else {
								foreach ($path as $value) {
									$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . rtrim($value, '/') . '/' . '\', true);' . "\n";
								}
							}
						}
					}

					// Autoload classmap
					if (isset($output['autoload']['classmap'])) {
						$autoload = [];

						$classmaps = $output['autoload']['classmap'];

						foreach ($classmaps as $classmap) {
							$directories = [dirname($file) . '/' . $classmap];

							while (count($directories) != 0) {
								$next = array_shift($directories);

								if (is_dir($next)) {
									foreach (glob(trim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
										if (is_dir($file)) {
											$directories[] = $file . '/';
										}

										if (is_file($file)) {
											$namespace = substr(dirname($file), strlen(DIR_STORAGE . 'vendor/' . $directory . $classmap) + 1);

											if ($namespace) {
												$autoload[$namespace] = substr(dirname($file), strlen(DIR_STORAGE . 'vendor/'));
											}
										}
									}
								}
							}
						}

						foreach ($autoload as $namespace => $path) {
							$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . rtrim($path, '/') . '/' . '\', true);' . "\n";
						}
					}

					// Autoload files
					if (isset($output['autoload']['files'])) {
						$files = $output['autoload']['files'];

						foreach ($files as $file) {
							$code .= 'if (is_file(DIR_STORAGE . \'vendor/' . $directory . '/' . $file . '\')) {' . "\n";
							$code .= '	require_once(DIR_STORAGE . \'vendor/' . $directory . '/' . $file . '\');' . "\n";
							$code .= '}' . "\n";
						}
					}
				}

				$code .= "\n";
			}

			file_put_contents(DIR_SYSTEM . 'vendor.php', trim($code));

			$json['success'] = $this->language->get('text_vendor_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
