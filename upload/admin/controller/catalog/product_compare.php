<?php
class ControllerCatalogProductCompare extends Controller {
	public function index() {
		$this->load->language('catalog/product_compare');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$product_id = 58;

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			// Description
			$data['product_description'] = array();

			$product_descriptions = $this->model_catalog_product->getProductDescriptions($product_id);

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

			$this->response->setOutput($this->load->view('catalog/product_compare', $data));
		}
	}
}