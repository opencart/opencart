<?php
class ControllerPaymentSagepayUS extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/sagepay_us');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('sagepay_us', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$data['entry_merchant_key'] = $this->language->get('entry_merchant_key');
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

		if (isset($this->error['merchant_id'])) {
			$data['error_merchant_id'] = $this->error['merchant_id'];
		} else {
			$data['error_merchant_id'] = '';
		}

		if (isset($this->error['merchant_key'])) {
			$data['error_merchant_key'] = $this->error['merchant_key'];
		} else {
			$data['error_merchant_key'] = '';
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
			'href' => $this->url->link('payment/sagepay_us', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/sagepay_us', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['sagepay_us_merchant_id'])) {
			$data['sagepay_us_merchant_id'] = $this->request->post['sagepay_us_merchant_id'];
		} else {
			$data['sagepay_us_merchant_id'] = $this->config->get('sagepay_us_merchant_id');
		}

		if (isset($this->request->post['sagepay_us_merchant_key'])) {
			$data['sagepay_us_merchant_key'] = $this->request->post['sagepay_us_merchant_key'];
		} else {
			$data['sagepay_us_merchant_key'] = $this->config->get('sagepay_us_merchant_key');
		}

		if (isset($this->request->post['sagepay_us_total'])) {
			$data['sagepay_us_total'] = $this->request->post['sagepay_us_total'];
		} else {
			$data['sagepay_us_total'] = $this->config->get('sagepay_us_total');
		}

		if (isset($this->request->post['sagepay_us_order_status_id'])) {
			$data['sagepay_us_order_status_id'] = $this->request->post['sagepay_us_order_status_id'];
		} else {
			$data['sagepay_us_order_status_id'] = $this->config->get('sagepay_us_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['sagepay_us_geo_zone_id'])) {
			$data['sagepay_us_geo_zone_id'] = $this->request->post['sagepay_us_geo_zone_id'];
		} else {
			$data['sagepay_us_geo_zone_id'] = $this->config->get('sagepay_us_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['sagepay_us_status'])) {
			$data['sagepay_us_status'] = $this->request->post['sagepay_us_status'];
		} else {
			$data['sagepay_us_status'] = $this->config->get('sagepay_us_status');
		}

		if (isset($this->request->post['sagepay_us_sort_order'])) {
			$data['sagepay_us_sort_order'] = $this->request->post['sagepay_us_sort_order'];
		} else {
			$data['sagepay_us_sort_order'] = $this->config->get('sagepay_us_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/sagepay_us.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/sagepay_us')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['sagepay_us_merchant_id']) {
			$this->error['merchant_id'] = $this->language->get('error_merchant_id');
		}

		if (!$this->request->post['sagepay_us_merchant_key']) {
			$this->error['merchant_key'] = $this->language->get('error_merchant_key');
		}

		return !$this->error;
	}
}