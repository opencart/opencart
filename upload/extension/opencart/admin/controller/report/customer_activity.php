<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Report;
/**
 * Class Customer Activity
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Report
 */
class CustomerActivity extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/report/customer_activity');

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
			'href' => $this->url->link('extension/opencart/report/customer_activity', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/report/customer_activity.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report');

		$data['report_customer_activity_status'] = $this->config->get('report_customer_activity_status');
		$data['report_customer_activity_sort_order'] = $this->config->get('report_customer_activity_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/report/customer_activity_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/report/customer_activity');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/report/customer_activity')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('report_customer_activity', $this->request->post);

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
		$this->load->language('extension/opencart/report/customer_activity');

		$data['list'] = $this->getReport();

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('extension/opencart/report/customer_activity', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('extension/opencart/report/customer_activity');

		$this->response->setOutput($this->getReport());
	}

	/**
	 * Get Report
	 *
	 * @return string
	 */
	public function getReport(): string {
		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}

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

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		// Activity
		$data['activities'] = [];

		$filter_data = [
			'filter_customer'   => $filter_customer,
			'filter_ip'         => $filter_ip,
			'filter_date_start' => $filter_date_start,
			'filter_date_end'   => $filter_date_end,
			'start'             => ($page - 1) * 20,
			'limit'             => 20
		];

		$this->load->model('extension/opencart/report/customer');

		// Total Activities
		$activity_total = $this->model_extension_opencart_report_customer->getTotalCustomerActivities($filter_data);

		$results = $this->model_extension_opencart_report_customer->getCustomerActivities($filter_data);

		foreach ($results as $result) {
			$comment = vsprintf($this->language->get('text_activity_' . $result['key']), json_decode($result['data'], true));

			$find = [
				'customer_id=',
				'order_id='
			];

			$replace = [
				$this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id='),
				$this->url->link('sale/order.info', 'user_token=' . $this->session->data['user_token'] . '&order_id=')
			];

			$data['activities'][] = [
				'comment'    => str_replace($find, $replace, $comment),
				'ip'         => $result['ip'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			];
		}

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $activity_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('extension/opencart/report/customer_activity.report', 'user_token=' . $this->session->data['user_token'] . '&code=customer_activity' . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($activity_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($activity_total - $this->config->get('config_pagination'))) ? $activity_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $activity_total, ceil($activity_total / $this->config->get('config_pagination')));

		$data['filter_customer'] = $filter_customer;
		$data['filter_ip'] = $filter_ip;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/report/customer_activity_list', $data);
	}
}
