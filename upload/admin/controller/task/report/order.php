<?php
namespace Opencart\Admin\Controller\Task\Report;
/**
 * Class Order
 *
 * @package Opencart\Admin\Controller\Task\Report
 */
class Order extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/report/order');

		// Sale
		$task_data = [
			'code'   => 'report',
			'action' => 'task/report/order.sale',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Processing
		$task_data = [
			'code'   => 'report',
			'action' => 'task/report/order.processing',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		// Complete
		$task_data = [
			'code'   => 'report',
			'action' => 'task/report/order.complete',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		// Other
		$task_data = [
			'code'   => 'report',
			'action' => 'task/report/order.other',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * Order Sale
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function sale(array $args = []): array {
		$this->load->language('task/report/order');

		$this->load->model('sale/order');

		$this->load->model('report/statistics');

		$this->model_report_statistics->editValue('order_sale', $this->model_sale_order->getTotalSales(['filter_order_status' => implode(',', (array)$this->config->get('config_complete_status'))]));

		return ['success' => $this->language->get('text_sale')];
	}

	/**
	 * Order Processing
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function processing(array $args = []): array {
		$this->load->language('task/report/order');

		$this->load->model('sale/order');

		$this->load->model('report/statistics');

		$this->model_report_statistics->editValue('order_processing', $this->model_sale_order->getTotalOrders(['filter_order_status' => implode(',', $this->config->get('config_processing_status'))]));

		return ['success' => $this->language->get('text_processing')];
	}

	/**
	 * Order Complete
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function complete(array $args = []): array {
		$this->load->language('task/report/order');

		$this->load->model('sale/order');

		$this->load->model('report/statistics');

		$this->model_report_statistics->editValue('order_complete', $this->model_sale_order->getTotalOrders(['filter_order_status' => implode(',', (array)$this->config->get('config_complete_status'))]));

		return ['success' => $this->language->get('text_complete')];
	}

	/**
	 * Order Other
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function other(array $args = []): array {
		$this->load->language('task/report/order');

		$order_status_data = [];

		$this->load->model('localisation/order_status');

		$results = $this->model_localisation_order_status->getOrderStatuses();

		foreach ($results as $result) {
			if (!in_array($result['order_status_id'], array_merge((array)$this->config->get('config_complete_status'), (array)$this->config->get('config_processing_status')))) {
				$order_status_data[] = $result['order_status_id'];
			}
		}

		$this->load->model('sale/order');

		$other_total = $this->model_sale_order->getTotalOrders(['filter_order_status' => implode(',', $order_status_data)]);

		$this->load->model('report/statistics');

		$this->model_report_statistics->editValue('order_other', $other_total);

		return ['success' => $this->language->get('text_other')];
	}
}
