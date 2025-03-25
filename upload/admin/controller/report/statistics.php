<?php
namespace Opencart\Admin\Controller\Report;
/**
 * Class Statistics
 *
 * @package Opencart\Admin\Controller\Report
 */
class Statistics extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('report/statistics');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/statistics', 'user_token=' . $this->session->data['user_token'])
		];

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/statistics', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('report/statistics');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		// Stats
		$data['statistics'] = [];

		$this->load->model('report/statistics');

		$results = $this->model_report_statistics->getStatistics();

		foreach ($results as $result) {
			$data['statistics'][] = [
				'name'  => $this->language->get('text_' . $result['code']),
				'value' => $result['value'],
				'href'  => $this->url->link('report/statistics.' . str_replace('_', '', $result['code']), 'user_token=' . $this->session->data['user_token'])
			];
		}

		return $this->load->view('report/statistics_list', $data);
	}

	/**
	 * Order Sale
	 *
	 * @return void
	 */
	public function orderSale(): void {
		$this->load->language('report/statistics');

		$json = [];

		if (!$this->user->hasPermission('modify', 'report/statistics')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Stats
			$this->load->model('report/statistics');

			// Order
			$this->load->model('sale/order');

			$this->model_report_statistics->editValue('order_sale', $this->model_sale_order->getTotalSales(['filter_order_status' => implode(',', array_merge((array)$this->config->get('config_complete_status'), (array)$this->config->get('config_processing_status')))]));

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Order Processing
	 *
	 * @return void
	 */
	public function orderProcessing(): void {
		$this->load->language('report/statistics');

		$json = [];

		if (!$this->user->hasPermission('modify', 'report/statistics')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Stats
			$this->load->model('report/statistics');

			// Order
			$this->load->model('sale/order');

			$this->model_report_statistics->editValue('order_processing', $this->model_sale_order->getTotalOrders(['filter_order_status' => implode(',', $this->config->get('config_processing_status'))]));

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Order Complete
	 *
	 * @return void
	 */
	public function orderComplete(): void {
		$this->load->language('report/statistics');

		$json = [];

		if (!$this->user->hasPermission('modify', 'report/statistics')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Stats
			$this->load->model('report/statistics');

			// Order
			$this->load->model('sale/order');

			$this->model_report_statistics->editValue('order_complete', $this->model_sale_order->getTotalOrders(['filter_order_status' => implode(',', (array)$this->config->get('config_complete_status'))]));

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Order Other
	 *
	 * @return void
	 */
	public function orderOther(): void {
		$this->load->language('report/statistics');

		$json = [];

		if (!$this->user->hasPermission('modify', 'report/statistics')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Statistics
			$this->load->model('report/statistics');

			// Order Status
			$this->load->model('localisation/order_status');

			$order_status_data = [];

			$results = $this->model_localisation_order_status->getOrderStatuses();

			foreach ($results as $result) {
				if (!in_array($result['order_status_id'], array_merge((array)$this->config->get('config_complete_status'), (array)$this->config->get('config_processing_status')))) {
					$order_status_data[] = $result['order_status_id'];
				}
			}

			// Total Orders
			$this->load->model('sale/order');

			$this->model_report_statistics->editValue('order_other', $this->model_sale_order->getTotalOrders(['filter_order_status' => implode(',', $order_status_data)]));

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Returns
	 *
	 * @return void
	 */
	public function returns(): void {
		$this->load->language('report/statistics');

		$json = [];

		if (!$this->user->hasPermission('modify', 'report/statistics')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Stats
			$this->load->model('report/statistics');

			// Returns
			$this->load->model('sale/returns');

			$this->model_report_statistics->editValue('return', $this->model_sale_returns->getTotalReturns(['filter_return_status_id' => $this->config->get('config_return_status_id')]));

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Product
	 *
	 * @return void
	 */
	public function product(): void {
		$this->load->language('report/statistics');

		$json = [];

		if (!$this->user->hasPermission('modify', 'report/statistics')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Stats
			$this->load->model('report/statistics');

			// Product
			$this->load->model('catalog/product');

			$this->model_report_statistics->editValue('product', $this->model_catalog_product->getTotalProducts(['filter_quantity_to' => 0]));

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Review
	 *
	 * @return void
	 */
	public function review(): void {
		$this->load->language('report/statistics');

		$json = [];

		if (!$this->user->hasPermission('modify', 'report/statistics')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Stats
			$this->load->model('report/statistics');

			// Review
			$this->load->model('catalog/review');

			$this->model_report_statistics->editValue('review', $this->model_catalog_review->getTotalReviewsAwaitingApproval());

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
