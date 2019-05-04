<?php
class ControllerCatalogProductCompare extends Controller {
	public function index() {
		$this->load->language('catalog/product_compare');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['master_id'])) {
			$master_id = $this->request->get['master_id'];
		} else {
			$master_id = 0;
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$master_id = 42;
		$product_id = 58;

		$this->load->model('catalog/product');

		// Description
		$data['product_description'] = array();

		$product_descriptions = $this->model_catalog_product->getProductDescriptions($master_id);

		foreach ($product_descriptions as $product_description) {
			$data['product_description'][] = array(
				'name'             => $product_description['name'],
				'description'      => $product_description['description'],
				'meta_title'       => $product_description['meta_title'],
				'meta_description' => $product_description['meta_description'],
				'meta_keyword'     => $product_description['meta_keyword'],
				'tag'              => $product_description['tag']
			);
		}

		//unset($product_data['name']);
		//unset($product_data['description']);
		//unset($product_data['meta_title']);
		//unset($product_data['meta_description']);
		//unset($product_data['meta_keyword']);
		//unset($product_data['meta_tag']);
		//unset($product_data['tag']);
		//unset($product_data['option']);

		// Product Info
		$ignore = array(
			'product_id',
			'master_id',
			'name',
			'description',
			'tag',
			'meta_title',
			'meta_description',
			'meta_keyword',
			'meta_tag',
			'variant',
			'viewed',
			'date_added',
			'date_modified'
		);

		$product_info = $this->model_catalog_product->getProduct($master_id);

		$data['model'] = $product_info['model'];
		$data['sku'] = $product_info['sku'];
		$data['upc'] = $product_info['upc'];
		$data['ean'] = $product_info['ean'];
		$data['jan'] = $product_info['jan'];
		$data['isbn'] = $product_info['isbn'];
		$data['mpn'] = $product_info['mpn'];
		$data['location'] = $product_info['location'];

		$data['price'] = $product_info['price'];
		$data['tax_class_id'] = $product_info['tax_class_id'];
		$data['quantity'] = $product_info['quantity'];
		$data['minimum'] = $product_info['minimum'];
		$data['shipping'] = $product_info['shipping'];
		$data['subtract'] = $product_info['subtract'];
		$data['date_available'] = $product_info['date_available'];
		$data['length'] = $product_info['length'];
		$data['width'] = $product_info['width'];
		$data['height'] = $product_info['height'];
		$data['weight'] = $product_info['weight'];
		$data['status'] = $product_info['status'];
		$data['sort_order'] = $product_info['sort_order'];

		$data['products'] = array();

		//product_categories
		//product_filters

		// Attributes
		$product_1_data['product_attribute'] = array();

		$product_attributes	= $this->model_catalog_product->getProductAttributes($master_id);

		foreach ($product_attributes as $product_attribute) {


			$data['product_attributes'][] = array(

			);
		}

		// Category
		$product_1_data['product_category'] = $this->model_catalog_product->getProductCategories($master_id);

		// Download
		$product_1_data['product_download'] = $this->model_catalog_product->getProductDownloads($master_id);

		// Discount
		$product_1_data['product_discount'] = $this->model_catalog_product->getProductDiscounts($master_id);

		// Filter
		$product_1_data['product_filter'] = $this->model_catalog_product->getProductFilters($master_id);

		// Image
		$keys = array(
			'image',
			'sort_order'
		);

		$product_1_data['product_images'] = array();

		$product_images	= $this->model_catalog_product->getProductImages($master_id);

		foreach ($product_images as $key => $value) {
			$product_1_data['product_images'][] = $value;
		}


		// Layout
		$product_1_data['product_layouts'] = $this->model_catalog_product->getProductLayouts($master_id);

		// Option
		$product_1_data['product_option'] = $this->model_catalog_product->getProductOptions($master_id);

		// Recurring
		$product_1_data['product_recurring'] = $this->model_catalog_product->getProductRecurrings($master_id);

		// Related
		$product_1_data['product_related'] = $this->model_catalog_product->getProductRelated($master_id);

		// Reward
		$product_1_data['product_reward'] = $this->model_catalog_product->getProductRewards($master_id);

		// Special
		$product_1_data['product_special'] = $this->model_catalog_product->getProductSpecials($master_id);

		// Store
		$product_1_data['product_store'] = $this->model_catalog_product->getProductStores($master_id);

		/*
		$diff = array();

		foreach ($product_1_data as $key => $value) {
			if ($value != $product_2_data[$key]) {
				$diff[] = $key;
			}
		}

		print_r($diff);
		print_r($product_1_data['product_image']);
		print_r($product_2_data['product_image']);

		/*
		$ignore = array(
			'product_id',
			'master_id'
		);

		// Product Description
		$master_description = $this->model_catalog_product->getProductDescriptions($master_id);

		// Product Description
		$variant_description = $this->model_catalog_product->getProductDescriptions($product_id);

		foreach ($master_description as $key => $value) {
			$data['compare'][$key] = array_merge(array_diff($master_description[$key], $variant_description[$key]), $data['compare']);
		}
		*/

		$this->response->setOutput($this->load->view('catalog/product_compare', $data));
	}
}