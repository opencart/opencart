<?php
class ControllerPaymentPayMate extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/paymate');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paymate', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_password'] = $this->language->get('help_password');
		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/paymate', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/paymate', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['paymate_username'])) {
			$data['paymate_username'] = $this->request->post['paymate_username'];
		} else {
			$data['paymate_username'] = $this->config->get('paymate_username');
		}

		if (isset($this->request->post['paymate_password'])) {
			$data['paymate_username'] = $this->request->post['paymate_username'];
		} elseif ($this->config->get('paymate_password')) {
			$data['paymate_password'] = $this->config->get('paymate_password');
		} else {
			$data['paymate_password'] = token(32);
		}

		if (isset($this->request->post['paymate_test'])) {
			$data['paymate_test'] = $this->request->post['paymate_test'];
		} else {
			$data['paymate_test'] = $this->config->get('paymate_test');
		}

		if (isset($this->request->post['paymate_total'])) {
			$data['paymate_total'] = $this->request->post['paymate_total'];
		} else {
			$data['paymate_total'] = $this->config->get('paymate_total');
		}

		if (isset($this->request->post['paymate_order_status_id'])) {
			$data['paymate_order_status_id'] = $this->request->post['paymate_order_status_id'];
		} else {
			$data['paymate_order_status_id'] = $this->config->get('paymate_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['paymate_geo_zone_id'])) {
			$data['paymate_geo_zone_id'] = $this->request->post['paymate_geo_zone_id'];
		} else {
			$data['paymate_geo_zone_id'] = $this->config->get('paymate_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['paymate_status'])) {
			$data['paymate_status'] = $this->request->post['paymate_status'];
		} else {
			$data['paymate_status'] = $this->config->get('paymate_status');
		}

		if (isset($this->request->post['paymate_sort_order'])) {
			$data['paymate_sort_order'] = $this->request->post['paymate_sort_order'];
		} else {
			$data['paymate_sort_order'] = $this->config->get('paymate_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/paymate.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paymate')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['paymate_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if (!$this->request->post['paymate_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		return !$this->error;
	}
}
