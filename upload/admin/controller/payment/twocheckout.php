<?php
class ControllerPaymentTwoCheckout extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/twocheckout');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('twocheckout', $this->request->post);

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

		$data['entry_account'] = $this->language->get('entry_account');
		$data['entry_secret'] = $this->language->get('entry_secret');
		$data['entry_display'] = $this->language->get('entry_display');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_secret'] = $this->language->get('help_secret');
		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['account'])) {
			$data['error_account'] = $this->error['account'];
		} else {
			$data['error_account'] = '';
		}

		if (isset($this->error['secret'])) {
			$data['error_secret'] = $this->error['secret'];
		} else {
			$data['error_secret'] = '';
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
			'href' => $this->url->link('payment/twocheckout', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/twocheckout', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['twocheckout_account'])) {
			$data['twocheckout_account'] = $this->request->post['twocheckout_account'];
		} else {
			$data['twocheckout_account'] = $this->config->get('twocheckout_account');
		}

		if (isset($this->request->post['twocheckout_secret'])) {
			$data['twocheckout_secret'] = $this->request->post['twocheckout_secret'];
		} else {
			$data['twocheckout_secret'] = $this->config->get('twocheckout_secret');
		}

		if (isset($this->request->post['twocheckout_display'])) {
			$data['twocheckout_display'] = $this->request->post['twocheckout_display'];
		} else {
			$data['twocheckout_display'] = $this->config->get('twocheckout_display');
		}

		if (isset($this->request->post['twocheckout_test'])) {
			$data['twocheckout_test'] = $this->request->post['twocheckout_test'];
		} else {
			$data['twocheckout_test'] = $this->config->get('twocheckout_test');
		}

		if (isset($this->request->post['twocheckout_total'])) {
			$data['twocheckout_total'] = $this->request->post['twocheckout_total'];
		} else {
			$data['twocheckout_total'] = $this->config->get('twocheckout_total');
		}

		if (isset($this->request->post['twocheckout_order_status_id'])) {
			$data['twocheckout_order_status_id'] = $this->request->post['twocheckout_order_status_id'];
		} else {
			$data['twocheckout_order_status_id'] = $this->config->get('twocheckout_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['twocheckout_geo_zone_id'])) {
			$data['twocheckout_geo_zone_id'] = $this->request->post['twocheckout_geo_zone_id'];
		} else {
			$data['twocheckout_geo_zone_id'] = $this->config->get('twocheckout_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['twocheckout_status'])) {
			$data['twocheckout_status'] = $this->request->post['twocheckout_status'];
		} else {
			$data['twocheckout_status'] = $this->config->get('twocheckout_status');
		}

		if (isset($this->request->post['twocheckout_sort_order'])) {
			$data['twocheckout_sort_order'] = $this->request->post['twocheckout_sort_order'];
		} else {
			$data['twocheckout_sort_order'] = $this->config->get('twocheckout_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/twocheckout.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/twocheckout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['twocheckout_account']) {
			$this->error['account'] = $this->language->get('error_account');
		}

		if (!$this->request->post['twocheckout_secret']) {
			$this->error['secret'] = $this->language->get('error_secret');
		}

		return !$this->error;
	}
}