<?php
class ControllerExtensionPaymentSagepayUS extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/sagepay_us');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_sagepay_us', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment'));
		}

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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/sagepay_us', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/payment/sagepay_us', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		if (isset($this->request->post['payment_sagepay_us_merchant_id'])) {
			$data['payment_sagepay_us_merchant_id'] = $this->request->post['payment_sagepay_us_merchant_id'];
		} else {
			$data['payment_sagepay_us_merchant_id'] = $this->config->get('payment_sagepay_us_merchant_id');
		}

		if (isset($this->request->post['payment_sagepay_us_merchant_key'])) {
			$data['payment_sagepay_us_merchant_key'] = $this->request->post['payment_sagepay_us_merchant_key'];
		} else {
			$data['payment_sagepay_us_merchant_key'] = $this->config->get('payment_sagepay_us_merchant_key');
		}

		if (isset($this->request->post['payment_sagepay_us_total'])) {
			$data['payment_sagepay_us_total'] = $this->request->post['payment_sagepay_us_total'];
		} else {
			$data['payment_sagepay_us_total'] = $this->config->get('payment_sagepay_us_total');
		}

		if (isset($this->request->post['payment_sagepay_us_order_status_id'])) {
			$data['payment_sagepay_us_order_status_id'] = $this->request->post['payment_sagepay_us_order_status_id'];
		} else {
			$data['payment_sagepay_us_order_status_id'] = $this->config->get('payment_sagepay_us_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_sagepay_us_geo_zone_id'])) {
			$data['payment_sagepay_us_geo_zone_id'] = $this->request->post['payment_sagepay_us_geo_zone_id'];
		} else {
			$data['payment_sagepay_us_geo_zone_id'] = $this->config->get('payment_sagepay_us_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_sagepay_us_status'])) {
			$data['payment_sagepay_us_status'] = $this->request->post['payment_sagepay_us_status'];
		} else {
			$data['payment_sagepay_us_status'] = $this->config->get('payment_sagepay_us_status');
		}

		if (isset($this->request->post['payment_sagepay_us_sort_order'])) {
			$data['payment_sagepay_us_sort_order'] = $this->request->post['payment_sagepay_us_sort_order'];
		} else {
			$data['payment_sagepay_us_sort_order'] = $this->config->get('payment_sagepay_us_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/sagepay_us', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/sagepay_us')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_sagepay_us_merchant_id']) {
			$this->error['merchant_id'] = $this->language->get('error_merchant_id');
		}

		if (!$this->request->post['payment_sagepay_us_merchant_key']) {
			$this->error['merchant_key'] = $this->language->get('error_merchant_key');
		}

		return !$this->error;
	}
}