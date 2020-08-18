<?php
namespace Catalog\Controller\Product;
class Thumb extends Controller {
	public function index($data) {
		$this->load->language('product/thumb');

		$data['review_status'] = $this->config->get('config_review_status');

		return $this->load->view('product/thumb', $data);
	}
}