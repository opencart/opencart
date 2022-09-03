<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Report;
class SaleReturn extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/opencart/report/sale_return');

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
			'href' => $this->url->link('extension/opencart/report/sale_return', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/report/sale_return.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report');

		$data['report_sale_return_status'] = $this->config->get('report_sale_return_status');
		$data['report_sale_return_sort_order'] = $this->config->get('report_sale_return_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/report/sale_return_form', $data));
	}

	public function save(): void {
		$this->load->language('extension/opencart/report/sale_coupon');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/report/sale_return')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('report_sale_return', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function report(): void {
		$this->load->language('extension/opencart/report/sale_return');

		$data['list'] = $this->getReport();

		$this->load->model('localisation/return_status');

		$data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

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

		$this->response->setOutput($this->load->view('extension/opencart/report/sale_return', $data));
	}

	public function list(): void {
		$this->load->language('extension/opencart/report/sale_return');

		$this->response->setOutput($this->getReport());
	}

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

		if (isset($this->request->get['filter_return_status_id'])) {
			$filter_return_status_id = (int)$this->request->get['filter_return_status_id'];
		} else {
			$filter_return_status_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['returns'] = [];

		$filter_data = [
			'filter_date_start'	      => $filter_date_start,
			'filter_date_end'	      => $filter_date_end,
			'filter_group'            => $filter_group,
			'filter_return_status_id' => $filter_return_status_id,
			'start'                   => ($page - 1) * $this->config->get('config_pagination'),
			'limit'                   => $this->config->get('config_pagination')
		];

		$this->load->model('extension/opencart/report/returns');

		$return_total = $this->model_extension_opencart_report_returns->getTotalReturns($filter_data);

		$results = $this->model_extension_opencart_report_returns->getReturns($filter_data);

		foreach ($results as $result) {
			$data['returns'][] = [
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'returns'    => $result['returns']
			];
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}

		if (isset($this->request->get['filter_return_status_id'])) {
			$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $return_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('extension/opencart/report/sale_return.report', 'user_token=' . $this->session->data['user_token'] . '&code=sale_return' . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($return_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($return_total - $this->config->get('config_pagination'))) ? $return_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $return_total, ceil($return_total / $this->config->get('config_pagination')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		$data['filter_return_status_id'] = $filter_return_status_id;

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/report/sale_return_list', $data);
	}
}
