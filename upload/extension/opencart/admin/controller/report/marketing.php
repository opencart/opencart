<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Report;
class Marketing extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/opencart/report/marketing');

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
			'href' => $this->url->link('extension/opencart/report/marketing', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/report/marketing|save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report');

		$data['report_marketing_status'] = $this->config->get('report_marketing_status');
		$data['report_marketing_sort_order'] = $this->config->get('report_marketing_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/report/marketing_form', $data));
	}

	public function save(): void {
		$this->load->language('extension/opencart/report/marketing');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/report/marketing')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('report_marketing', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function report(): void {
		$this->load->language('extension/opencart/report/marketing');

		$data['list'] = $this->getReport();

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('extension/opencart/report/marketing', $data));
	}

	public function list(): void {
		$this->load->language('extension/opencart/report/marketing');

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

		$data['marketings'] = [];

		$filter_data = [
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end,
			'filter_order_status_id' => $filter_order_status_id,
			'start'                  => ($page - 1) * $this->config->get('config_pagination'),
			'limit'                  => $this->config->get('config_pagination')
		];

		$this->load->model('extension/opencart/report/marketing');

		$marketing_total = $this->model_extension_opencart_report_marketing->getTotalMarketing($filter_data);

		$results = $this->model_extension_opencart_report_marketing->getMarketing($filter_data);

		foreach ($results as $result) {
			$data['marketings'][] = [
				'campaign' => $result['campaign'],
				'code'     => $result['code'],
				'clicks'   => $result['clicks'],
				'orders'   => $result['orders'],
				'total'    => $this->currency->format((float)$result['total'], $this->config->get('config_currency')),
				'save'     => $this->url->link('marketing/marketing/edit', 'user_token=' . $this->session->data['user_token'] . '&marketing_id=' . $result['marketing_id'])
			];
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $marketing_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('extension/opencart/report/marketing|report', 'user_token=' . $this->session->data['user_token'] . '&code=marketing' . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($marketing_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($marketing_total - $this->config->get('config_pagination'))) ? $marketing_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $marketing_total, ceil($marketing_total / $this->config->get('config_pagination')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_order_status_id'] = $filter_order_status_id;

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/report/marketing_list', $data);
	}
}
