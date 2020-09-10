<?php
namespace Opencart\Application\Controller\Extension\Opencart\Report;
class CustomerTransaction extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('extension/opencart/report/customer_transaction');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('report_customer_transaction', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

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
			'href' => $this->url->link('extension/opencart/report/customer_transaction', 'user_token=' . $this->session->data['user_token'])
		];

		$data['action'] = $this->url->link('extension/opencart/report/customer_transaction', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report');

		if (isset($this->request->post['report_customer_transaction_status'])) {
			$data['report_customer_transaction_status'] = $this->request->post['report_customer_transaction_status'];
		} else {
			$data['report_customer_transaction_status'] = $this->config->get('report_customer_transaction_status');
		}

		if (isset($this->request->post['report_customer_transaction_sort_order'])) {
			$data['report_customer_transaction_sort_order'] = $this->request->post['report_customer_transaction_sort_order'];
		} else {
			$data['report_customer_transaction_sort_order'] = $this->config->get('report_customer_transaction_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/report/customer_transaction_form', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/opencart/report/customer_transaction')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
		
	public function report() {
		$this->load->language('extension/opencart/report/customer_transaction');

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

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->load->model('extension/opencart/report/customer_transaction');
		
		$data['customers'] = [];

		$filter_data = [
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'filter_customer'	=> $filter_customer,
			'start'				=> ($page - 1) * $this->config->get('config_pagination'),
			'limit'				=> $this->config->get('config_pagination')
		];

		$customer_total = $this->model_extension_opencart_report_customer_transaction->getTotalTransactions($filter_data);

		$results = $this->model_extension_opencart_report_customer_transaction->getTransactions($filter_data);

		foreach ($results as $result) {
			$data['customers'][] = [
				'customer'       => $result['customer'],
				'email'          => $result['email'],
				'customer_group' => $result['customer_group'],
				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'total'          => $this->currency->format($result['total'], $this->config->get('config_currency')),
				'edit'           => $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'])
			];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $customer_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('extension/opencart/report/customer_transaction/report', 'user_token=' . $this->session->data['user_token'] . '&code=customer_transaction' . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($customer_total - $this->config->get('config_pagination'))) ? $customer_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $customer_total, ceil($customer_total / $this->config->get('config_pagination')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_customer'] = $filter_customer;

		$this->response->setOutput($this->load->view('extension/opencart/report/customer_transaction', $data));
	}
}