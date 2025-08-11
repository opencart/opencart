<?php
namespace Opencart\Admin\Controller\Task\Report;
/**
 * Class Returns
 *
 * @package Opencart\Admin\Controller\Task\Report
 */
class Returns extends \Opencart\System\Engine\Controller {
	/**
	 * Returns
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/report/returns');

		$this->load->model('sale/returns');

		$return_total = $this->model_sale_returns->getTotalReturns(['filter_return_status_id' => $this->config->get('config_return_status_id')]);

		$this->load->model('report/statistics');

		$this->model_report_statistics->editValue('return', $return_total);

		return ['success' => $this->language->get('text_success')];
	}
}
