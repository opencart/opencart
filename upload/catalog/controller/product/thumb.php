<?php
namespace Opencart\Catalog\Controller\Product;
class Thumb extends \Opencart\System\Engine\Controller {
	public function index(array $data): string {
		$this->load->language('product/thumb');

		$data['review_status'] = $this->config->get('config_review_status');

		return $this->load->view('product/thumb', $data);
	}
}