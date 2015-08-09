<?php
class ControllerFraudIp extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('fraud/ip');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ip', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/fraud', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_ip_add'] = $this->language->get('text_ip_add');
		$data['text_ip_list'] = $this->language->get('text_ip_list');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_ip'] = $this->language->get('entry_ip');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_ip'] = $this->language->get('help_ip');
		$data['help_order_status'] = $this->language->get('help_order_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_ip_add'] = $this->language->get('button_ip_add');

		$data['tab_general'] = $this->language->get('tab_general');
        $data['tab_ip'] = $this->language->get('tab_ip');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_fraud'),
			'href' => $this->url->link('extension/fraud', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('fraud/ip', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('fraud/ip', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/fraud', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['ip_order_status_id'])) {
			$data['ip_order_status_id'] = $this->request->post['ip_order_status_id'];
		} else {
			$data['ip_order_status_id'] = $this->config->get('ip_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['ip_status'])) {
			$data['ip_status'] = $this->request->post['ip_status'];
		} else {
			$data['ip_status'] = $this->config->get('ip_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('fraud/ip.tpl', $data));
	}

	public function install() {
		$this->load->model('fraud/ip');

		$this->model_fraud_ip->install();
	}

	public function uninstall() {
		$this->load->model('fraud/ip');

		$this->model_fraud_ip->uninstall();
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'fraud/ip')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

    public function ip() {
		$this->load->language('fraud/ip');

		$this->load->model('fraud/ip');
        $this->load->model('customer/customer');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['column_ip'] = $this->language->get('column_ip');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

        $data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['ips'] = array();

		$results = $this->model_fraud_ip->getIps(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['ips'][] = array(
				'ip'         => $result['ip'],
				'total'      => $this->model_customer_customer->getTotalCustomersByIp($result['ip']),
				'date_added' => date('d/m/y', strtotime($result['date_added'])),
				'filter_ip'  => $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&filter_ip=' . $result['ip'], 'SSL')
			);
		}

		$ip_total = $this->model_fraud_ip->getTotalIps();

		$pagination = new Pagination();
		$pagination->total = $ip_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('fraud/ip/ip', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($ip_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($ip_total - 10)) ? $ip_total : ((($page - 1) * 10) + 10), $ip_total, ceil($ip_total / 10));

		$this->response->setOutput($this->load->view('fraud/ip_ip.tpl', $data));
	}

	public function addIp() {
		$this->load->language('fraud/ip');

		$json = array();

		if (!$this->user->hasPermission('modify', 'fraud/ip')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('fraud/ip');

			if (!$this->model_fraud_ip->getTotalIpsByIp($this->request->post['ip'])) {
				$this->model_fraud_ip->addIp($this->request->post['ip']);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeIp() {
		$this->load->language('fraud/ip');

		$json = array();

		if (!$this->user->hasPermission('modify', 'fraud/ip')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('fraud/ip');

			$this->model_fraud_ip->removeIp($this->request->post['ip']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
