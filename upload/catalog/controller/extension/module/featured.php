<?php
class ControllerExtensionModuleFeatured extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/featured');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['product'])) {
			$product_data = array();

			foreach ($setting['product'] as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					$product_data[] = $product_info;
				}
			}

			$results = array_slice($product_data, 0, (int)$setting['limit']);

			foreach ($results as $result) {
				$data['products'][] = $this->load->controller('product/thumb', $result, '', $setting);
			}
		}

		if ($data['products']) {
			return $this->load->view('extension/module/featured', $data);
		}
	}
}