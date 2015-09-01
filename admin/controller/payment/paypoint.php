<?php
class ControllerPaymentPayPoint extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/paypoint');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paypoint', $this->request->post);

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
		$data['text_live'] = $this->language->get('text_live');
		$data['text_successful'] = $this->language->get('text_successful');
		$data['text_fail'] = $this->language->get('text_fail');

		$data['entry_merchant'] = $this->language->get('entry_merchant');
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

		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
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
			'href' => $this->url->link('payment/paypoint', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/paypoint', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['paypoint_merchant'])) {
			$data['paypoint_merchant'] = $this->request->post['paypoint_merchant'];
		} else {
			$data['paypoint_merchant'] = $this->config->get('paypoint_merchant');
		}

		if (isset($this->request->post['paypoint_password'])) {
			$data['paypoint_password'] = $this->request->post['paypoint_password'];
		} else {
			$data['paypoint_password'] = $this->config->get('paypoint_password');
		}

		if (isset($this->request->post['paypoint_test'])) {
			$data['paypoint_test'] = $this->request->post['paypoint_test'];
		} else {
			$data['paypoint_test'] = $this->config->get('paypoint_test');
		}

		if (isset($this->request->post['paypoint_total'])) {
			$data['paypoint_total'] = $this->request->post['paypoint_total'];
		} else {
			$data['paypoint_total'] = $this->config->get('paypoint_total');
		}

		if (isset($this->request->post['paypoint_order_status_id'])) {
			$data['paypoint_order_status_id'] = $this->request->post['paypoint_order_status_id'];
		} else {
			$data['paypoint_order_status_id'] = $this->config->get('paypoint_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['paypoint_geo_zone_id'])) {
			$data['paypoint_geo_zone_id'] = $this->request->post['paypoint_geo_zone_id'];
		} else {
			$data['paypoint_geo_zone_id'] = $this->config->get('paypoint_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['paypoint_status'])) {
			$data['paypoint_status'] = $this->request->post['paypoint_status'];
		} else {
			$data['paypoint_status'] = $this->config->get('paypoint_status');
		}

		if (isset($this->request->post['paypoint_sort_order'])) {
			$data['paypoint_sort_order'] = $this->request->post['paypoint_sort_order'];
		} else {
			$data['paypoint_sort_order'] = $this->config->get('paypoint_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/paypoint.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paypoint')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['paypoint_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		return !$this->error;
	}
}