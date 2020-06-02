<?php
class ControllerExtensionModuleSpecial extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/special');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$data['products'] = array();

		$filter_data = array(
			'sort'  => 'pd.name',
			'order' => 'ASC',
			'start' => 0,
			'limit' => $setting['limit']
		);

		$results = $this->model_catalog_product->getSpecials($filter_data);

		if ($results) {
			foreach ($results as $result) {
				$data['products'][] = $this->load->controller('product/thumb', $result);
			}

			return $this->load->view('extension/module/special', $data);
		}
	}
}