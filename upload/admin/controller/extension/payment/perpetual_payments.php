<?php
class ControllerExtensionPaymentPerpetualPayments extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/perpetual_payments');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('perpetual_payments', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_auth_id'] = $this->language->get('entry_auth_id');
		$data['entry_auth_pass'] = $this->language->get('entry_auth_pass');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_test'] = $this->language->get('help_test');
		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['auth_id'])) {
			$data['error_auth_id'] = $this->error['auth_id'];
		} else {
			$data['error_auth_id'] = '';
		}

		if (isset($this->error['auth_pass'])) {
			$data['error_auth_pass'] = $this->error['auth_pass'];
		} else {
			$data['error_auth_pass'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/perpetual_payments', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/perpetual_payments', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		if (isset($this->request->post['perpetual_payments_auth_id'])) {
			$data['perpetual_payments_auth_id'] = $this->request->post['perpetual_payments_auth_id'];
		} else {
			$data['perpetual_payments_auth_id'] = $this->config->get('perpetual_payments_auth_id');
		}

		if (isset($this->request->post['perpetual_payments_auth_pass'])) {
			$data['perpetual_payments_auth_pass'] = $this->request->post['perpetual_payments_auth_pass'];
		} else {
			$data['perpetual_payments_auth_pass'] = $this->config->get('perpetual_payments_auth_pass');
		}

		if (isset($this->request->post['perpetual_payments_test'])) {
			$data['perpetual_payments_test'] = $this->request->post['perpetual_payments_test'];
		} else {
			$data['perpetual_payments_test'] = $this->config->get('perpetual_payments_test');
		}

		if (isset($this->request->post['perpetual_payments_total'])) {
			$data['perpetual_payments_total'] = $this->request->post['perpetual_payments_total'];
		} else {
			$data['perpetual_payments_total'] = $this->config->get('perpetual_payments_total');
		}

		if (isset($this->request->post['perpetual_payments_order_status_id'])) {
			$data['perpetual_payments_order_status_id'] = $this->request->post['perpetual_payments_order_status_id'];
		} else {
			$data['perpetual_payments_order_status_id'] = $this->config->get('perpetual_payments_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['perpetual_payments_geo_zone_id'])) {
			$data['perpetual_payments_geo_zone_id'] = $this->request->post['perpetual_payments_geo_zone_id'];
		} else {
			$data['perpetual_payments_geo_zone_id'] = $this->config->get('perpetual_payments_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['perpetual_payments_status'])) {
			$data['perpetual_payments_status'] = $this->request->post['perpetual_payments_status'];
		} else {
			$data['perpetual_payments_status'] = $this->config->get('perpetual_payments_status');
		}

		if (isset($this->request->post['perpetual_payments_sort_order'])) {
			$data['perpetual_payments_sort_order'] = $this->request->post['perpetual_payments_sort_order'];
		} else {
			$data['perpetual_payments_sort_order'] = $this->config->get('perpetual_payments_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/perpetual_payments', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/perpetual_payments')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['perpetual_payments_auth_id']) {
			$this->error['auth_id'] = $this->language->get('error_auth_id');
		}

		if (!$this->request->post['perpetual_payments_auth_pass']) {
			$this->error['auth_pass'] = $this->language->get('error_auth_pass');
		}

		return !$this->error;
	}
}