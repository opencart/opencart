<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Report;
/**
 * Class Product Viewed
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Report
 */
class ProductViewed extends \Opencart\System\Engine\Controller {
	/**
	 * Generate
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/report/product_viewed');

		$limit = 10;

		// Extension
		$this->load->model('extension/opencart/report/product_viewed');

		if ($page == 1) {
			$this->model_extension_opencart_report_product_viewed->clear();
		}

		// Products
		$filter_data = [
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		];

		$this->load->model('catalog/product');

		// Total Products
		$product_total = $this->model_catalog_product->getTotalProducts();

		$products = $this->model_catalog_product->getProducts($filter_data);

		foreach ($products as $product) {
			$this->model_extension_opencart_report_product_viewed->addReport($product['product_id'], $this->model_catalog_product->getTotalReports($product['product_id']));
		}

		if (($page * $limit) <= $product_total) {
			$json['text'] = sprintf($this->language->get('text_progress'), ($page - 1) * $limit, $product_total);

			$json['next'] = $this->url->link('extension/opencart/report/product_viewed.generate', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
		} else {
			$json['success'] = $this->language->get('text_success');
		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


}
