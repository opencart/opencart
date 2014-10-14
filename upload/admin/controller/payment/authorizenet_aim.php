<?php
class ControllerPaymentAuthorizenetAim extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/authorizenet_aim');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('authorizenet_aim', $this->request->post);

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
		$data['entry_hash'] = $this->language->get('entry_hash');
		$data['entry_server'] = $this->language->get('entry_server');
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
			'href' => $this->url->link('payment/authorizenet_aim', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/authorizenet_aim', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['authorizenet_aim_login'])) {
			$data['authorizenet_aim_login'] = $this->request->post['authorizenet_aim_login'];
		} else {
			$data['authorizenet_aim_login'] = $this->config->get('authorizenet_aim_login');
		}

		if (isset($this->request->post['authorizenet_aim_key'])) {
			$data['authorizenet_aim_key'] = $this->request->post['authorizenet_aim_key'];
		} else {
			$data['authorizenet_aim_key'] = $this->config->get('authorizenet_aim_key');
		}

		if (isset($this->request->post['authorizenet_aim_hash'])) {
			$data['authorizenet_aim_hash'] = $this->request->post['authorizenet_aim_hash'];
		} else {
			$data['authorizenet_aim_hash'] = $this->config->get('authorizenet_aim_hash');
		}

		if (isset($this->request->post['authorizenet_aim_server'])) {
			$data['authorizenet_aim_server'] = $this->request->post['authorizenet_aim_server'];
		} else {
			$data['authorizenet_aim_server'] = $this->config->get('authorizenet_aim_server');
		}

		if (isset($this->request->post['authorizenet_aim_mode'])) {
			$data['authorizenet_aim_mode'] = $this->request->post['authorizenet_aim_mode'];
		} else {
			$data['authorizenet_aim_mode'] = $this->config->get('authorizenet_aim_mode');
		}

		if (isset($this->request->post['authorizenet_aim_method'])) {
			$data['authorizenet_aim_method'] = $this->request->post['authorizenet_aim_method'];
		} else {
			$data['authorizenet_aim_method'] = $this->config->get('authorizenet_aim_method');
		}

		if (isset($this->request->post['authorizenet_aim_total'])) {
			$data['authorizenet_aim_total'] = $this->request->post['authorizenet_aim_total'];
		} else {
			$data['authorizenet_aim_total'] = $this->config->get('authorizenet_aim_total');
		}

		if (isset($this->request->post['authorizenet_aim_order_status_id'])) {
			$data['authorizenet_aim_order_status_id'] = $this->request->post['authorizenet_aim_order_status_id'];
		} else {
			$data['authorizenet_aim_order_status_id'] = $this->config->get('authorizenet_aim_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['authorizenet_aim_geo_zone_id'])) {
			$data['authorizenet_aim_geo_zone_id'] = $this->request->post['authorizenet_aim_geo_zone_id'];
		} else {
			$data['authorizenet_aim_geo_zone_id'] = $this->config->get('authorizenet_aim_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['authorizenet_aim_status'])) {
			$data['authorizenet_aim_status'] = $this->request->post['authorizenet_aim_status'];
		} else {
			$data['authorizenet_aim_status'] = $this->config->get('authorizenet_aim_status');
		}

		if (isset($this->request->post['authorizenet_aim_sort_order'])) {
			$data['authorizenet_aim_sort_order'] = $this->request->post['authorizenet_aim_sort_order'];
		} else {
			$data['authorizenet_aim_sort_order'] = $this->config->get('authorizenet_aim_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/authorizenet_aim.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/authorizenet_aim')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['authorizenet_aim_login']) {
			$this->error['login'] = $this->language->get('error_login');
		}

		if (!$this->request->post['authorizenet_aim_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		return !$this->error;
	}
}