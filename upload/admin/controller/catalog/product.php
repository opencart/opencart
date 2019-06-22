<?php
class ControllerCatalogProduct extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product->addProduct($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/product'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			// We need to update variant products with the new mast product data now we have added the product variant feature.
			$products = $this->model_catalog_product->getProducts(array('filter_master_id' => $this->request->get['product_id']));

			foreach ($products as $product) {
				$product_data = array();

				$ignore = array(
					'quantity',
					'variant',
					'override',
					'product_seo_url'
				);

				foreach ($product as $key => $value) {
					if ($this->request->hasPost($key) && !array_key_exists($key, $product_data['override']) && !in_array($key, $ignore)) {
						$product_data[$key] = $this->request->post[$key];
					} else {
						$product_data[$key] = $value;
					}
				}

				// Make sure these are arrays not strings
				$product_data['variant'] = (array)json_decode($product['variant'], true);
				$product_data['override'] = (array)json_decode($product['override'], true);

				// Description
				if (array_key_exists('product_description', $product_data['override'])) {
					$product_data['product_description'] = $this->model_catalog_product->getProductDescriptions($product['product_id']);

				} elseif ($this->request->hasPost('product_description')) {
					foreach ($this->request->post['product_description'] as $language_id => $product_description) {
						foreach ($product_description as $key => $value) {
							if (!isset($product_data['override']['product_description'][$language_id][$key])) {
								$product_data['product_description'][$language_id][$key] = $value;
							}
						}
					}
				}

				// Category
				if (array_key_exists('product_category', $product_data['override'])) {
					$product_data['product_category'] = $this->model_catalog_product->getProductCategories($product['product_id']);
				} elseif ($this->request->hasPost('product_category')) {
					$product_data['product_category'] = $this->request->post['product_category'];
				} else {
					$product_data['product_category'] = array();
				}

				// Filter
				if (array_key_exists('product_filter', $product_data['override'])) {
					$product_data['product_filter'] = $this->model_catalog_product->getProductFilters($product['product_id']);
				} elseif ($this->request->hasPost('product_filter')) {
					$product_data['product_filter'] = $this->request->post['product_filter'];
				} else {
					$product_data['product_filter'] = array();
				}

				// Stores
				if (array_key_exists('product_store', $product_data['override'])) {
					$product_data['product_store'] = $this->model_catalog_product->getProductStores($product['product_id']);
				} elseif ($this->request->hasPost('product_store')) {
					$product_data['product_store'] = $this->request->post['product_store'];
				} else {
					$product_data['product_store'] = array();
				}

				// Downloads
				if (array_key_exists('product_download', $product_data['override'])) {
					$product_data['product_download'] = $this->model_catalog_product->getProductDownloads($product['product_id']);
				} elseif ($this->request->hasPost('product_download')) {
					$product_data['product_download'] = $this->request->post['product_download'];
				} else {
					$product_data['product_download'] = array();
				}

				// Related
				if (array_key_exists('product_related', $product_data['override'])) {
					$product_data['product_related'] = $this->model_catalog_product->getProductRelated($product['product_id']);
				} elseif ($this->request->hasPost('product_related')) {
					$product_data['product_related'] = $this->request->post['product_related'];
				} else {
					$product_data['product_related'] = array();
				}

				// Attributes
				if (array_key_exists('product_attribute', $product_data['override'])) {
					$product_data['product_attribute'] = $this->model_catalog_product->getProductAttributes($product['product_id']);
				} elseif ($this->request->hasPost('product_attribute')) {
					$product_data['product_attribute'] = $this->request->post['product_attribute'];
				} else {
					$product_data['product_attribute'] = array();
				}

				$product_data['product_option'] = $this->model_catalog_product->getProductOptions($product['product_id']);

				// Recurring
				if (array_key_exists('product_recurring', $product_data['override'])) {
					$product_data['product_recurring'] = $this->model_catalog_product->getProductRecurrings($product['product_id']);
				} elseif ($this->request->hasPost('product_recurring')) {
					$product_data['product_recurring'] = $this->request->post['product_recurring'];
				} else {
					$product_data['product_recurring'] = array();
				}

				// Discount
				if (array_key_exists('product_discount', $product_data['override'])) {
					$product_data['product_discount'] = $this->model_catalog_product->getProductDiscounts($product['product_id']);
				} elseif ($this->request->hasPost('product_discount')) {
					$product_data['product_discount'] = $this->request->post['product_discount'];
				} else {
					$product_data['product_discount'] = array();
				}

				// Special
				if (array_key_exists('product_special', $product_data['override'])) {
					$product_data['product_special'] = $this->model_catalog_product->getProductSpecials($product['product_id']);
				} elseif ($this->request->hasPost('product_special')) {
					$product_data['product_special'] = $this->request->post['product_special'];
				} else {
					$product_data['product_special'] = array();
				}

				// Images
				if (array_key_exists('product_image', $product_data['override'])) {
					$product_data['product_image'] = $this->model_catalog_product->getProductImages($product['product_id']);
				} elseif ($this->request->hasPost('product_image')) {
					$product_data['product_image'] = $this->request->post['product_image'];
				} else {
					$product_data['product_image'] = array();
				}

				// Reward
				if (array_key_exists('product_reward', $product_data['override'])) {
					$product_data['product_reward'] = $this->model_catalog_product->getProductRewards($product['product_id']);
				} elseif ($this->request->hasPost('product_reward')) {
					$product_data['product_reward'] = $this->request->post['product_reward'];
				} else {
					$product_data['product_reward'] = array();
				}

				// SEO URL
				$product_data['product_seo_url'] = $this->model_catalog_product->getProductSeoUrls($product['product_id']);

				// Layout
				if (array_key_exists('product_layout', $product_data['override'])) {
					$product_data['product_layout'] = $this->model_catalog_product->getProductLayouts($product['product_id']);
				} elseif ($this->request->hasPost('product_layout')) {
					$product_data['product_layout'] = $this->request->post['product_layout'];
				} else {
					$product_data['product_layout'] = array();
				}

				$this->model_catalog_product->editProduct($product['product_id'], $product_data);
			}

			$this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->provider->detach('product_id');

			$this->response->redirect($this->provider->link('catalog/product'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->deleteProduct($product_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/product'));
		}

		$this->getList();
	}

	public function copy() {
		$this->load->language('catalog/product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product');

		if ($this->request->hasPost('selected') && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $product_id) {
				$this->model_catalog_product->copyProduct($product_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/product'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->provider->parser(array('filter_name' => '', 'filter_model' => '', 'filter_price' => '', 'filter_quantity' => '', 'filter_status' => '', 'sort' => 'pd.name'));

		$filter_data = array_merge($this->provider->getParams(), $this->provider->default_filter);

		$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

		$results = $this->model_catalog_product->getProducts($filter_data);

		$this->load->model('tool/image');
		
		$this->breadcrumbs->setDefaults();

		$data['add'] = $this->provider->link('catalog/product/add');
		$data['copy'] = $this->provider->link('catalog/product/copy');
		$data['delete'] = $this->provider->link('catalog/product/delete');

		$data['products'] = array();

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 40, 40);
			}

			$special = false;

			$product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

			foreach ($product_specials as $product_special) {
				if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
					$special = $this->currency->format($product_special['price'], $this->config->get('config_currency'));

					break;
				}
			}

			$data['products'][] = array(
				'product_id' => $result['product_id'],
				'image'      => $image,
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $this->currency->format($result['price'], $this->config->get('config_currency')),
				'special'    => $special,
				'quantity'   => $result['quantity'],
				'status'     => $result['status'],
				'edit'       => $this->provider->link('catalog/product/edit', array('product_id' => $result['product_id'], 'master_id' => ($result['master_id'] ? $result['master_id'] : ''))),
				'variant'    => ($result['master_id'] ? '' : $this->provider->link('catalog/product/add', array('master_id' => $result['product_id'])))
			);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if ($this->request->hasPost('selected')) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$sorts = array('sort_name' => 'pd.name', 'sort_model' => 'p.model', 'sort_price' => 'p.price', 'sort_quantity' => 'p.quantity', 'sort_order' => 'p.sort_order');

		foreach ($sorts as $key => $sort) {
			$data[$key] = $this->provider->link('catalog/product', array('sort' => $sort, 'order' => $this->provider->order));
		}

		$data['pagination'] = $this->load->controller('common/pagination', $product_total);

		$data['results'] = $this->load->controller('common/pagination/results', $product_total);

		foreach ($this->provider->getParams() as $key => $value) {
			$data[$key] = $value;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['breadcrumbs'] = $this->breadcrumbs->render();
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/product_list', $data));
	}

	protected function getForm() {
		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');

		$this->provider->detach('product_id');

		$data['text_form'] = $this->request->hasGet('product_id') ? $this->language->get('text_edit') : $this->language->get('text_add');

		$this->breadcrumbs->setDefaults();

		$this->breadcrumbs->set((string)$data['text_form']);

		$data['user_token'] = $this->session->data['user_token'];

		$errors = array('warning' => '', 'name' => array(), 'meta_title' => array(), 'model' => '', 'variant' => array(), 'keyword' => '');

		foreach ($errors as $key => $value) {
			$data['error_' . $key] = isset($this->error[$key]) ? $this->error[$key] : $value;
		}

		if ($this->request->hasGet('master_id')) {
			$master = $this->provider->link('catalog/product/edit', array('product_id' => $this->request->get['master_id']));

			$data['text_variant'] = sprintf($this->language->get('text_variant'), $master, $master);
		} else {
			$data['text_variant'] = '';
		}

		if ($this->request->hasGet('product_id')) {
			$data['action'] = $this->provider->link('catalog/product/edit', array('product_id' => $this->request->get['product_id']));
		} else {
			$data['action'] = $this->provider->link('catalog/product/add');
		}

		$data['cancel'] = $this->provider->link('catalog/product');

		// If master_id then we need to get the variant info
		if ($this->request->hasGet('product_id')) {
			$product_id = (int)$this->request->get['product_id'];
		} elseif ($this->request->hasGet('master_id')) {
			$product_id = (int)$this->request->get['master_id'];
		} else {
			$product_id = 0;
		}

		if ($product_id && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
		}

		$variables = array(
			'model' => '',
			'sku' => '',
			'upc' => '',
			'ean' => '',
			'jan' => '',
			'isbn' => '',
			'mpn' => '',
			'location' => '',
			'price' => '',
			'tax_class_id' => 0,
			'quantity' => 1,
			'minimum' => 1,
			'subtract' => 1,
			'stock_status_id' => 0,
			'shipping' => 1,
			'length' => '',
			'width' => '',
			'height' => '',
			'length_class_id' => $this->config->get('config_length_class_id'),
			'weight' => '',
			'weight_class_id' => $this->config->get('config_weight_class_id'),
			'status' => true,
			'sort_order' => 1,
			'manufacturer_id' => 0,
			'image' => '',
			'points' => ''
		);

		foreach ($variables as $key => $value) {
			if ($this->request->hasPost($key)) {
				$data[$key] = $this->request->post[$key];
			} elseif (!empty($product_info)) {
				$data[$key] = $product_info[$key];
			} else {
				$data[$key] = $value;
			}
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if ($this->request->hasPost('product_description')) {
			$data['product_description'] = $this->request->post['product_description'];
		} elseif (!empty($product_info)) {
			$data['product_description'] = $this->model_catalog_product->getProductDescriptions($product_id);
		} else {
			$data['product_description'] = array();
		}

		if ($this->request->hasGet('master_id')) {
			$data['master_id'] = (int)$this->request->get['master_id'];
		} elseif (!empty($product_info)) {
			$data['master_id'] = $product_info['master_id'];
		} else {
			$data['master_id'] = 0;
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->load->model('localisation/stock_status');

		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		if ($this->request->hasPost('date_available')) {
			$data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($product_info)) {
			$data['date_available'] = ($product_info['date_available'] != '0000-00-00') ? $product_info['date_available'] : '';
		} else {
			$data['date_available'] = date('Y-m-d');
		}

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		$this->load->model('catalog/manufacturer');

		if ($this->request->hasPost('manufacturer')) {
			$data['manufacturer'] = $this->request->post['manufacturer'];
		} elseif (!empty($product_info)) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

			if ($manufacturer_info) {
				$data['manufacturer'] = $manufacturer_info['name'];
			} else {
				$data['manufacturer'] = '';
			}
		} else {
			$data['manufacturer'] = '';
		}

		// Categories
		$this->load->model('catalog/category');

		if ($this->request->hasPost('product_category')) {
			$categories = $this->request->post['product_category'];
		} elseif (!empty($product_info)) {
			$categories = $this->model_catalog_product->getProductCategories($product_id);
		} else {
			$categories = array();
		}

		$data['product_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		// Filters
		$this->load->model('catalog/filter');

		if ($this->request->hasPost('product_filter')) {
			$filters = $this->request->post['product_filter'];
		} elseif (!empty($product_info)) {
			$filters = $this->model_catalog_product->getProductFilters($product_id);
		} else {
			$filters = array();
		}

		$data['product_filters'] = array();

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$data['product_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}

		// Stores
		$this->load->model('setting/store');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}

		if ($this->request->hasPost('product_store')) {
			$data['product_store'] = $this->request->post['product_store'];
		} elseif (!empty($product_info)) {
			$data['product_store'] = $this->model_catalog_product->getProductStores($product_id);
		} else {
			$data['product_store'] = array(0);
		}

		// Downloads
		$this->load->model('catalog/download');

		if ($this->request->hasPost('product_download')) {
			$product_downloads = $this->request->post['product_download'];
		} elseif (!empty($product_info)) {
			$product_downloads = $this->model_catalog_product->getProductDownloads($product_id);
		} else {
			$product_downloads = array();
		}

		$data['product_downloads'] = array();

		foreach ($product_downloads as $download_id) {
			$download_info = $this->model_catalog_download->getDownload($download_id);

			if ($download_info) {
				$data['product_downloads'][] = array(
					'download_id' => $download_info['download_id'],
					'name'        => $download_info['name']
				);
			}
		}

		// Related
		if ($this->request->hasPost('product_related')) {
			$product_relateds = $this->request->post['product_related'];
		} elseif (!empty($product_info)) {
			$product_relateds = $this->model_catalog_product->getProductRelated($product_id);
		} else {
			$product_relateds = array();
		}

		$data['product_relateds'] = array();

		foreach ($product_relateds as $related_id) {
			$related_info = $this->model_catalog_product->getProduct($related_id);

			if ($related_info) {
				$data['product_relateds'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}

		// Attributes
		$this->load->model('catalog/attribute');

		if ($this->request->hasPost('product_attribute')) {
			$product_attributes = $this->request->post['product_attribute'];
		} elseif (!empty($product_info)) {
			$product_attributes = $this->model_catalog_product->getProductAttributes($product_id);
		} else {
			$product_attributes = array();
		}

		$data['product_attributes'] = array();

		foreach ($product_attributes as $product_attribute) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);

			if ($attribute_info) {
				$data['product_attributes'][] = array(
					'attribute_id'                  => $product_attribute['attribute_id'],
					'name'                          => $attribute_info['name'],
					'product_attribute_description' => $product_attribute['product_attribute_description']
				);
			}
		}

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		// Options
		$this->load->model('catalog/option');

		if ($this->request->hasPost('product_option')) {
			$product_options = $this->request->post['product_option'];
		} elseif (!empty($product_info)) {
			$product_options = $this->model_catalog_product->getProductOptions($product_id);
		} else {
			$product_options = array();
		}

		$data['product_options'] = array();

		foreach ($product_options as $product_option) {
			$product_option_value_data = array();

			if (isset($product_option['product_option_value'])) {
				foreach ($product_option['product_option_value'] as $product_option_value) {
					$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

					if ($option_value_info) {
						$product_option_value_data[] = array(
							'product_option_value_id' => $product_option_value['product_option_value_id'],
							'option_value_id'         => $product_option_value['option_value_id'],
							'name'                    => $option_value_info['name'],
							'quantity'                => $product_option_value['quantity'],
							'subtract'                => $product_option_value['subtract'],
							'price'                   => round($product_option_value['price']),
							'price_prefix'            => $product_option_value['price_prefix'],
							'points'                  => round($product_option_value['points']),
							'points_prefix'           => $product_option_value['points_prefix'],
							'weight'                  => round($product_option_value['weight']),
							'weight_prefix'           => $product_option_value['weight_prefix']
						);
					}
				}
			}

			$data['product_options'][] = array(
				'product_option_id'    => $product_option['product_option_id'],
				'product_option_value' => $product_option_value_data,
				'option_id'            => $product_option['option_id'],
				'name'                 => $product_option['name'],
				'type'                 => $product_option['type'],
				'value'                => isset($product_option['value']) ? $product_option['value'] : '',
				'required'             => $product_option['required']
			);
		}

		$data['option_values'] = array();

		foreach ($data['product_options'] as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				if (!isset($data['option_values'][$product_option['option_id']])) {
					$data['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
				}
			}
		}

		// Variants
		if ($this->request->hasPost('variant')) {
			$data['variant'] = $this->request->post['variant'];
		} elseif (!empty($product_info)) {
			$data['variant'] = json_decode($product_info['variant'], true);
		} else {
			$data['variant'] = array();
		}

		// Overrides
		if ($this->request->hasPost('override')) {
			$data['override'] = $this->request->post['override'];
		} elseif (!empty($product_info)) {
			$data['override'] = json_decode($product_info['override'], true);
		} else {
			$data['override'] = array();
		}

		$data['options'] = array();

		if ($this->request->hasGet('master_id')) {
			$product_options = $this->model_catalog_product->getProductOptions($this->request->get['master_id']);

			foreach ($product_options as $product_option) {
				$product_option_value_data = array();

				foreach ($product_option['product_option_value'] as $product_option_value) {
					$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

					if ($option_value_info) {
						$product_option_value_data[] = array(
							'product_option_value_id' => $product_option_value['product_option_value_id'],
							'option_value_id'         => $product_option_value['option_value_id'],
							'name'                    => $option_value_info['name'],
							'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
							'price_prefix'            => $product_option_value['price_prefix']
						);
					}
				}

				$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

				$data['options'][] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $product_option['option_id'],
					'name'                 => $option_info['name'],
					'type'                 => $option_info['type'],
					'value'                => isset($data['variant'][$product_option['product_option_id']]) ? $data['variant'][$product_option['product_option_id']] : $product_option['value'],
					'required'             => $product_option['required']
				);
			}
		}

		// Recurring
		$this->load->model('catalog/recurring');

		$data['recurrings'] = $this->model_catalog_recurring->getRecurrings();

		if ($this->request->hasPost('product_recurring')) {
			$data['product_recurrings'] = $this->request->post['product_recurring'];
		} elseif (!empty($product_info)) {
			$data['product_recurrings'] = $this->model_catalog_product->getProductRecurrings($product_id);
		} else {
			$data['product_recurrings'] = array();
		}

		// Discount
		if ($this->request->hasPost('product_discount')) {
			$product_discounts = $this->request->post['product_discount'];
		} elseif (!empty($product_info)) {
			$product_discounts = $this->model_catalog_product->getProductDiscounts($product_id);
		} else {
			$product_discounts = array();
		}

		$data['product_discounts'] = array();

		foreach ($product_discounts as $product_discount) {
			$data['product_discounts'][] = array(
				'customer_group_id' => $product_discount['customer_group_id'],
				'quantity'          => $product_discount['quantity'],
				'priority'          => $product_discount['priority'],
				'price'             => $product_discount['price'],
				'date_start'        => ($product_discount['date_start'] != '0000-00-00') ? $product_discount['date_start'] : '',
				'date_end'          => ($product_discount['date_end'] != '0000-00-00') ? $product_discount['date_end'] : ''
			);
		}

		// Special
		if ($this->request->hasPost('product_special')) {
			$product_specials = $this->request->post['product_special'];
		} elseif (!empty($product_info)) {
			$product_specials = $this->model_catalog_product->getProductSpecials($product_id);
		} else {
			$product_specials = array();
		}

		$data['product_specials'] = array();

		foreach ($product_specials as $product_special) {
			$data['product_specials'][] = array(
				'customer_group_id' => $product_special['customer_group_id'],
				'priority'          => $product_special['priority'],
				'price'             => $product_special['price'],
				'date_start'        => ($product_special['date_start'] != '0000-00-00') ? $product_special['date_start'] : '',
				'date_end'          => ($product_special['date_end'] != '0000-00-00') ? $product_special['date_end'] : ''
			);
		}

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (is_file(DIR_IMAGE . html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'))) {
			$data['thumb'] = $this->model_tool_image->resize(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'), 100, 100);
		} else {
			$data['thumb'] = $data['placeholder'];
		}

		// Images
		if ($this->request->hasPost('product_image')) {
			$product_images = $this->request->post['product_image'];
		} elseif (!empty($product_info)) {
			$product_images = $this->model_catalog_product->getProductImages($product_id);
		} else {
			$product_images = array();
		}

		$data['product_images'] = array();

		foreach ($product_images as $product_image) {
			if (is_file(DIR_IMAGE . html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $product_image['image'];
				$thumb = $product_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize(html_entity_decode($thumb, ENT_QUOTES, 'UTF-8'), 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}

		if ($this->request->hasPost('product_reward')) {
			$data['product_reward'] = $this->request->post['product_reward'];
		} elseif (!empty($product_info)) {
			$data['product_reward'] = $this->model_catalog_product->getProductRewards($product_id);
		} else {
			$data['product_reward'] = array();
		}

		// SEO
		if ($this->request->hasPost('product_seo_url')) {
			$data['product_seo_url'] = $this->request->post['product_seo_url'];
		} elseif (!empty($product_info)) {
			$data['product_seo_url'] = $this->model_catalog_product->getProductSeoUrls($product_id);
		} else {
			$data['product_seo_url'] = array();
		}

		// Layout
		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		if ($this->request->hasPost('product_layout')) {
			$data['product_layout'] = $this->request->post['product_layout'];
		} elseif (!empty($product_info)) {
			$data['product_layout'] = $this->model_catalog_product->getProductLayouts($product_id);
		} else {
			$data['product_layout'] = array();
		}

		// For variant products we need to load the master product default values.
		if ($this->request->hasGet('master_id')) {
			$master_id = $this->request->get['master_id'];
		} else {
			$master_id = 0;
		}

		$data['master']['product_description'] = $this->model_catalog_product->getProductDescriptions($master_id);

		$master_info = $this->model_catalog_product->getProduct($master_id);

		if ($master_info) {
			$variables = array('model', 'sku', 'upc', 'ean', 'jan', 'isbn', 'mpn', 'location', 'price', 'tax_class_id', 'quantity', 'minimum', 'shipping', 'subtract', 'stock_status_id', 'length', 'width', 'height', 'length_class_id', 'weight', 'weight_class_id', 'status', 'manufacturer_id', 'image', 'points');

			foreach ($variables as $index) {
				$data['master'][$index] = $master_info[$index];
			}

			$data['master']['date_available'] = ($master_info['date_available'] != '0000-00-00') ? $master_info['date_available'] : '';

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($master_info['manufacturer_id']);

			if ($manufacturer_info) {
				$data['master']['manufacturer'] = $manufacturer_info['name'];
			} else {
				$data['master']['manufacturer'] = '';
			}

			if (is_file(DIR_IMAGE . html_entity_decode($data['master']['image'], ENT_QUOTES, 'UTF-8'))) {
				$data['master']['thumb'] = $this->model_tool_image->resize(html_entity_decode($data['master']['image'], ENT_QUOTES, 'UTF-8'), 100, 100);
			} else {
				$data['master']['thumb'] = $data['placeholder'];
			}
		} else {
			$data['master']['product_description'] = array();
			$data['master']['model'] = '';
			$data['master']['sku'] = '';
			$data['master']['upc'] = '';
			$data['master']['ean'] = '';
			$data['master']['jan'] = '';
			$data['master']['isbn'] = '';
			$data['master']['mpn'] = '';
			$data['master']['location'] = '';
			$data['master']['price'] = '';
			$data['master']['tax_class_id'] = '';
			$data['master']['minimum'] = 1;
			$data['master']['shipping'] = 0;
			$data['master']['subtract'] = 0;
			$data['master']['stock_status_id'] = 0;
			$data['master']['date_available'] = '';
			$data['master']['length'] = '';
			$data['master']['width'] = '';
			$data['master']['height'] = '';
			$data['master']['length_class_id'] = '';
			$data['master']['weight'] = '';
			$data['master']['weight_class_id'] = '';
			$data['master']['status'] = '';
			$data['master']['sort_order'] = 0;
			$data['master']['manufacturer_id'] = 0;
			$data['master']['manufacturer'] = '';
			$data['master']['image'] = '';
			$data['master']['thumb'] = $data['placeholder'];
			$data['master']['points'] = '';
		}

		// Categories
		$data['master']['product_categories'] = array();

		$categories = $this->model_catalog_product->getProductCategories($master_id);

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['master']['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		// Filters
		$data['master']['product_filters'] = array();

		$filters = $this->model_catalog_product->getProductFilters($master_id);

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$data['master']['product_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}

		// Stores
		$data['master']['product_store'] = $this->model_catalog_product->getProductStores($master_id);

		// Downloads
		$data['master']['product_downloads'] = array();

		$product_downloads = $this->model_catalog_product->getProductDownloads($master_id);

		foreach ($product_downloads as $download_id) {
			$download_info = $this->model_catalog_download->getDownload($download_id);

			if ($download_info) {
				$data['master']['product_downloads'][] = array(
					'download_id' => $download_info['download_id'],
					'name'        => $download_info['name']
				);
			}
		}

		// Related Products
		$data['master']['product_relateds'] = array();

		$product_relateds = $this->model_catalog_product->getProductRelated($master_id);

		foreach ($product_relateds as $related_id) {
			$related_info = $this->model_catalog_product->getProduct($related_id);

			if ($related_info) {
				$data['master']['product_relateds'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}

		// Attributes
		$data['master']['product_attributes'] = array();

		$product_attributes = $this->model_catalog_product->getProductAttributes($master_id);

		foreach ($product_attributes as $product_attribute) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);

			if ($attribute_info) {
				$data['master']['product_attributes'][] = array(
					'attribute_id'                  => $product_attribute['attribute_id'],
					'name'                          => $attribute_info['name'],
					'product_attribute_description' => $product_attribute['product_attribute_description']
				);
			}
		}

		// Recurring
		$data['master']['product_recurrings'] = $this->model_catalog_product->getProductRecurrings($master_id);

		// Discounts
		$data['master']['product_discounts'] = array();

		$product_discounts = $this->model_catalog_product->getProductDiscounts($master_id);

		foreach ($product_discounts as $product_discount) {
			$data['master']['product_discounts'][] = array(
				'customer_group_id' => $product_discount['customer_group_id'],
				'quantity'          => $product_discount['quantity'],
				'priority'          => $product_discount['priority'],
				'price'             => $product_discount['price'],
				'date_start'        => ($product_discount['date_start'] != '0000-00-00') ? $product_discount['date_start'] : '',
				'date_end'          => ($product_discount['date_end'] != '0000-00-00') ? $product_discount['date_end'] : ''
			);
		}

		// Specials
		$data['master']['product_specials'] = array();

		$product_specials = $this->model_catalog_product->getProductSpecials($master_id);

		foreach ($product_specials as $product_special) {
			$data['master']['product_specials'][] = array(
				'customer_group_id' => $product_special['customer_group_id'],
				'priority'          => $product_special['priority'],
				'price'             => $product_special['price'],
				'date_start'        => ($product_special['date_start'] != '0000-00-00') ? $product_special['date_start'] : '',
				'date_end'          => ($product_special['date_end'] != '0000-00-00') ? $product_special['date_end'] : ''
			);
		}

		// Images
		$data['master']['product_images'] = array();

		$product_images = $this->model_catalog_product->getProductImages($master_id);

		foreach ($product_images as $product_image) {
			if (is_file(DIR_IMAGE . html_entity_decode($product_image['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $product_image['image'];
				$thumb = $product_image['image'];
			} else {
				$image = '';
				$thumb = 'no_image.png';
			}

			$data['master']['product_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize(html_entity_decode($thumb, ENT_QUOTES, 'UTF-8'), 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}

		// Rewards
		$data['master']['product_reward'] = $this->model_catalog_product->getProductRewards($master_id);

		// SEO
		$data['master']['product_seo_url'] = $this->model_catalog_product->getProductSeoUrls($master_id);

		// Layout
		$data['master']['product_layout'] = $this->model_catalog_product->getProductLayouts($master_id);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['breadcrumbs'] = $this->breadcrumbs->render();
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/product_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['product_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

			if ((utf8_strlen($value['meta_title']) < 1) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}

		if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
			$this->error['model'] = $this->language->get('error_model');
		}

		if ($this->request->post['master_id']) {
			$this->load->model('catalog/product');

			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['master_id']);

			foreach ($product_options as $product_option) {
				if (isset($this->request->post['override']['variant'][$product_option['product_option_id']]) && $product_option['required'] && empty($this->request->post['variant'][$product_option['product_option_id']])) {
					$this->error['variant'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}
		}

		if ($this->request->post['product_seo_url']) {
			$this->load->model('design/seo_url');

			foreach ($this->request->post['product_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if ($keyword) {
						$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);

						foreach ($seo_urls as $seo_url) {
							if (($seo_url['store_id'] == $store_id) && ($seo_url['language_id'] == $language_id) && (!$this->request->hasGet('product_id') || (($seo_url['query'] != 'product_id=' . $this->request->get['product_id'])))) {
								$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_keyword');

								break;
							}
						}
					} else {
						$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_seo');
					}
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		$this->provider->parser(array('filter_name' => '', 'filter_model' => '', 'limit' => 5));

		$filter_data = array_merge($this->provider->getParams(), array('start' => 0));

		if ($filter_data['filter_name'] || $filter_data['filter_model']) {
			$this->load->model('catalog/product');
			$this->load->model('catalog/option');

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				$option_data = array();

				$product_options = $this->model_catalog_product->getProductOptions($result['product_id']);

				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'model'      => $result['model'],
					'option'     => $option_data,
					'price'      => $result['price']
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}