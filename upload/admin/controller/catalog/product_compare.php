<?php

class ControllerCatalogProductCompare extends Controller {
	public function index() {
		$this->load->language('catalog/product');

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

		$this->load->model('catalog/product');

		$master_id = 42;
		$product_id = 58;

		$this->model_catalog_product->compare($master_id, $product_id);

		$data['compare'] = array();


		/*
		$ignore = array(
			'product_id',
			'master_id'
		);

		print_r($this->model_catalog_product->compare($master_id, $product_id));

		// Product Description
		$master_description = $this->model_catalog_product->getProductDescriptions($master_id);

		// Product Description
		$variant_description = $this->model_catalog_product->getProductDescriptions($product_id);

		foreach ($master_description as $key => $value) {
			$data['compare'][$key] = array_merge(array_diff($master_description[$key], $variant_description[$key]), $data['compare']);
		}

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

		$master_info = $this->model_catalog_product->getProduct($master_id);

		$variant_info = $this->model_catalog_product->getProduct($product_id);

		//$data['compare'][] = array_diff($master_info, $variant_info);

		//	print_r($data['compare']);

		/*
		foreach ($master_info as $key => $value) {
			if (!in_array($key, $ignore) && ($master_info[$key] != $variant_info[$key])) {
				$data['compare'][$key] = array(
					'key'     => $key,
					'text'    => $this->language->get('entry_' . $key),
					'master'  => $master_info[$key],
					'variant' => $variant_info[$key],
				);
			}
		}


		// Attribute
		$master_attribute_data = array();

		$results = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

		foreach ($results as $result) {
			$master_attribute_data[] = $result;
		}

		print_r($master_attribute_data);


		$variant_attribute_data = array();

		$results = $this->model_catalog_product->getProductAttributes($product_id);

		foreach ($results as $result) {
			$variant_attribute_data[] = $result;
		}

		if (isset($this->request->post['product_attribute'])) {
			foreach ($this->request->post['product_attribute'] as $product_attribute) {
				$original_data['product_attribute'][] = array_intersect_key($keys, $product_attribute);
			}
		}


		// Discount
		$product_discount_data = array();

		$results = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

		foreach ($results as $result) {
			$product_discount_data[] = $result;
		}

		if (isset($this->request->post['product_discount'])) {
			$product_data['product_discount'][] = array_combine($keys, $this->request->post['product_discount']);
		}

		// Filter
		if (isset($this->request->post['product_filter'])) {
			$product_data['product_filter'] = $this->model_catalog_product->getProductFilters($this->request->get['product_id']);
		}

		// Image
		$product_image_data = array();

		$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

		foreach ($results as $result) {
			$product_image_data[] = $result;
		}

		if (isset($this->request->post['product_image'])) {
			foreach ($this->request->post['product_image'] as $product_image) {
				$product_data['product_image'][] = array_combine($keys, $product_image);
			}
		}

		$product_data['product_option'] = array();

		//$product_data['product_option'] = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);

		$product_data['product_related'] = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

		$product_reward_data = array();

		$results = $this->model_catalog_product->getProductRewards($this->request->get['product_id']);

		foreach ($results as $result) {
			$product_reward_data[] = $result;
		}

		// Special
		$product_data['product_special'] = array();

		$results = $this->model_catalog_product->getProductSpecials($this->request->get['product_id']);

		foreach ($results as $result) {
			$product_data['product_special'][] = $result;
		}

		$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);

		foreach ($product_info as $key => $value) {
			if (isset($this->request->post[$key])) {
				$product_data[$key] = $this->request->post[$key];
			}
		}

		$product_data['product_category'] = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);

		$product_data['product_download'] = $this->model_catalog_product->getProductDownloads($this->request->get['product_id']);

		$product_data['product_layout'] = $this->model_catalog_product->getProductLayouts($this->request->get['product_id']);

		$product_data['product_store'] = $this->model_catalog_product->getProductStores($this->request->get['product_id']);

		$product_data['product_recurring'] = $this->model_catalog_product->getProductRecurrings($this->request->get['product_id']);

		//unset($product_data['name']);
		//unset($product_data['description']);
		//unset($product_data['meta_title']);
		//unset($product_data['meta_description']);
		unset($product_data['meta_keyword']);
		unset($product_data['meta_title']);
		unset($product_data['tag']);
		unset($product_data['option']);

		$data['sku'] = '';
		$data['upc'] = '';
		$data['viewed'] = '0';
		$data['keyword'] = '';
		$data['status'] = '0';
		*/

		$this->response->setOutput($this->load->view('catalog/product_compare', $data));
	}
}