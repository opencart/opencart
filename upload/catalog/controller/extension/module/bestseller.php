<?php
class ControllerExtensionModuleBestSeller extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/bestseller');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		$results = $this->model_catalog_product->getBestSeller($setting['limit']);

		if ($results) {
			foreach ($results as $result) {
				$data['products'][] = $this->load->controller('product/thumb', $result);
			}

			return $this->load->view('extension/module/bestseller', $data);
		}
	}
}
