<?php
namespace Opencart\Admin\Controller\Task\Report;
/**
 * Class Stock
 *
 * @package Opencart\Admin\Controller\Report
 */
class Stock extends \Opencart\System\Engine\Controller {
	/**
	 * Product
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/report/stock');

		$this->load->model('catalog/product');

		$stock_total = $this->model_catalog_product->getTotalProducts(['filter_quantity_to' => 0]);

		$this->load->model('report/statistics');

		$this->model_report_statistics->editValue('product', $stock_total);

		return ['success' => $this->language->get('text_success')];
	}
}
