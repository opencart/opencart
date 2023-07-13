<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Report;
class Affiliate extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/opencart/report/affiliate');

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
			'href' => $this->url->link('extension/opencart/report/affiliate', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/report/affiliate.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report');

		$data['report_affiliate_status'] = $this->config->get('report_affiliate_status');
		$data['report_affiliate_sort_order'] = $this->config->get('report_affiliate_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/report/affiliate_form', $data));
	}

	public function save(): void {
		$this->load->language('extension/opencart/report/affiliate');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/report/affiliate')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('report_affiliate', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function report(): void {
		$this->load->language('extension/opencart/report/affiliate');

		$data['list'] = $this->getReport();

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('extension/opencart/report/affiliate', $data));
	}

	public function list(): void {
		$this->load->language('extension/opencart/report/affiliate');

		$this->response->setOutput($this->getReport());
	}

	public function getReport(): string {
		if (isset($this->request->get['filter_payment'])) {
			$filter_payment = (string)$this->request->get['filter_payment'];
		} else {
			$filter_payment = '';
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_pagination');
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['affiliates'] = [];

		$filter_data = [
			'filter_payment' => $filter_payment,
			'start'          => ($page - 1) * $limit,
			'limit'          => $limit
		];

		$this->load->model('extension/opencart/report/affiliate');

		$affiliate_total = $this->model_extension_opencart_report_affiliate->getTotalAffiliates($filter_data);

		$results = $this->model_extension_opencart_report_affiliate->getAffiliates($filter_data);

		foreach ($results as $result) {
			$data['affiliates'][] = [
				'name'       => $result['customer'],
				'email'      => $result['email'],
				'amount'     => $result['amount'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			];
		}

		$url = '';

		if (isset($this->request->get['filter_payment'])) {
			$url .= '&filter_payment=' . urlencode($this->request->get['filter_payment']);
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $affiliate_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('extension/opencart/report/affiliate.report', 'user_token=' . $this->session->data['user_token'] . '&code=affiliate' . $url . '&page={page}&limit=' . $limit)
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($affiliate_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($affiliate_total - $limit)) ? $affiliate_total : ((($page - 1) * $limit) + $limit), $affiliate_total, ceil($affiliate_total / $limit));

		$data['filter_payment'] = $filter_payment;

		$data['limit'] = $limit;

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/report/affiliate_list', $data);
	}
}
