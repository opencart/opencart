<?php
class ControllerPaymentWebPaymentSoftware extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/web_payment_software');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('web_payment_software', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_test'] = $this->language->get('text_test');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_authorization'] = $this->language->get('text_authorization');
		$data['text_capture'] = $this->language->get('text_capture');

		$data['entry_login'] = $this->language->get('entry_login');
		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_mode'] = $this->language->get('entry_mode');
		$data['entry_method'] = $this->language->get('entry_method');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['login'])) {
			$data['error_login'] = $this->error['login'];
		} else {
			$data['error_login'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
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
			'href' => $this->url->link('payment/web_payment_software', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = HTTPS_SERVER . 'index.php?route=payment/web_payment_software&token=' . $this->session->data['token'];

		$data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];

		if (isset($this->request->post['web_payment_software_login'])) {
			$data['web_payment_software_merchant_name'] = $this->request->post['web_payment_software_merchant_name'];
		} else {
			$data['web_payment_software_merchant_name'] = $this->config->get('web_payment_software_merchant_name');
		}

		if (isset($this->request->post['web_payment_software_merchant_key'])) {
			$data['web_payment_software_merchant_key'] = $this->request->post['web_payment_software_merchant_key'];
		} else {
			$data['web_payment_software_merchant_key'] = $this->config->get('web_payment_software_merchant_key');
		}

		if (isset($this->request->post['web_payment_software_mode'])) {
			$data['web_payment_software_mode'] = $this->request->post['web_payment_software_mode'];
		} else {
			$data['web_payment_software_mode'] = $this->config->get('web_payment_software_mode');
		}

		if (isset($this->request->post['web_payment_software_method'])) {
			$data['web_payment_software_method'] = $this->request->post['web_payment_software_method'];
		} else {
			$data['web_payment_software_method'] = $this->config->get('web_payment_software_method');
		}

		if (isset($this->request->post['web_payment_software_order_status_id'])) {
			$data['web_payment_software_order_status_id'] = $this->request->post['web_payment_software_order_status_id'];
		} else {
			$data['web_payment_software_order_status_id'] = $this->config->get('web_payment_software_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['web_payment_software_geo_zone_id'])) {
			$data['web_payment_software_geo_zone_id'] = $this->request->post['web_payment_software_geo_zone_id'];
		} else {
			$data['web_payment_software_geo_zone_id'] = $this->config->get('web_payment_software_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['web_payment_software_status'])) {
			$data['web_payment_software_status'] = $this->request->post['web_payment_software_status'];
		} else {
			$data['web_payment_software_status'] = $this->config->get('web_payment_software_status');
		}

		if (isset($this->request->post['web_payment_software_total'])) {
			$data['web_payment_software_total'] = $this->request->post['web_payment_software_total'];
		} else {
			$data['web_payment_software_total'] = $this->config->get('web_payment_software_total');
		}

		if (isset($this->request->post['web_payment_software_sort_order'])) {
			$data['web_payment_software_sort_order'] = $this->request->post['web_payment_software_sort_order'];
		} else {
			$data['web_payment_software_sort_order'] = $this->config->get('web_payment_software_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/web_payment_software.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/web_payment_software')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['web_payment_software_merchant_name']) {
			$this->error['login'] = $this->language->get('error_login');
		}

		if (!$this->request->post['web_payment_software_merchant_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		return !$this->error;
	}
}