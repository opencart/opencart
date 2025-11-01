<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Report;
/**
 * Class Sale Shipping
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Report
 */
class SaleShipping extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/report/sale_shipping');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/report/sale_shipping', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/report/sale_shipping.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report');

		$data['report_sale_shipping_status'] = $this->config->get('report_sale_shipping_status');
		$data['report_sale_shipping_sort_order'] = $this->config->get('report_sale_shipping_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/report/sale_shipping_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/report/sale_shipping');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/report/sale_shipping')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('report_sale_shipping', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Report
	 *
	 * @return void
	 */
	public function report(): void {
		$this->load->language('extension/opencart/report/sale_shipping');

		$data['list'] = $this->getReport();

		// Order Statuses
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		// Groups
		$data['groups'] = [];

		$data['groups'][] = [
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		];

		$data['groups'][] = [
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		];

		$data['groups'][] = [
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		];

		$data['groups'][] = [
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		];

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('extension/opencart/report/sale_shipping', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('extension/opencart/report/sale_shipping');

		$this->response->setOutput($this->getReport());
	}

	/**
	 * Get Report
	 *
	 * @return string
	 */
	public function getReport(): string {
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = (int)$this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		// Sale
		$data['orders'] = [];

		$filter_data = [
			'filter_date_start'      => $filter_date_start,
			'filter_date_end'        => $filter_date_end,
			'filter_group'           => $filter_group,
			'filter_order_status_id' => $filter_order_status_id,
			'start'                  => ($page - 1) * $this->config->get('config_pagination'),
			'limit'                  => $this->config->get('config_pagination')
		];

		// Extension
		$this->load->model('extension/opencart/report/sale');

		// Total Orders
		$order_total = $this->model_extension_opencart_report_sale->getTotalShipping($filter_data);

		$results = $this->model_extension_opencart_report_sale->getShipping($filter_data);

		foreach ($results as $result) {
			$data['orders'][] = [
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'title'      => $result['title'],
				'orders'     => $result['orders'],
				'total'      => $result['total']
			];
		}

		$remove = [
			'route',
			'user_token',
			'code',
			'page'
		];

		$url = http_build_query(array_diff_key($this->request->get, array_flip($remove)));

		// Pagination
		$data['total'] = $order_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('extension/opencart/report/sale_shipping.list', 'user_token=' . $this->session->data['user_token'] . '&code=sale_shipping' . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($order_total - $this->config->get('config_pagination'))) ? $order_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $order_total, ceil($order_total / $this->config->get('config_pagination')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		$data['filter_order_status_id'] = $filter_order_status_id;

		$data['currency'] = $this->config->get('config_currency');

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/report/sale_shipping_list', $data);
	}
}
