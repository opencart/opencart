<?php
class ControllerExtensionPaymentLiqPay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/liqpay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_liqpay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

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

		if (isset($this->error['signature'])) {
			$data['error_signature'] = $this->error['signature'];
		} else {
			$data['error_signature'] = '';
		}

		if (isset($this->error['type'])) {
			$data['error_type'] = $this->error['type'];
		} else {
			$data['error_type'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/liqpay', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/liqpay', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_liqpay_merchant'])) {
			$data['payment_liqpay_merchant'] = $this->request->post['payment_liqpay_merchant'];
		} else {
			$data['payment_liqpay_merchant'] = $this->config->get('payment_liqpay_merchant');
		}

		if (isset($this->request->post['payment_liqpay_signature'])) {
			$data['payment_liqpay_signature'] = $this->request->post['payment_liqpay_signature'];
		} else {
			$data['payment_liqpay_signature'] = $this->config->get('payment_liqpay_signature');
		}

		if (isset($this->request->post['payment_liqpay_type'])) {
			$data['payment_liqpay_type'] = $this->request->post['payment_liqpay_type'];
		} else {
			$data['payment_liqpay_type'] = $this->config->get('payment_liqpay_type');
		}

		if (isset($this->request->post['payment_liqpay_total'])) {
			$data['payment_liqpay_total'] = $this->request->post['payment_liqpay_total'];
		} else {
			$data['payment_liqpay_total'] = $this->config->get('payment_liqpay_total');
		}

		if (isset($this->request->post['payment_liqpay_order_status_id'])) {
			$data['payment_liqpay_order_status_id'] = $this->request->post['payment_liqpay_order_status_id'];
		} else {
			$data['payment_liqpay_order_status_id'] = $this->config->get('payment_liqpay_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_liqpay_geo_zone_id'])) {
			$data['payment_liqpay_geo_zone_id'] = $this->request->post['payment_liqpay_geo_zone_id'];
		} else {
			$data['payment_liqpay_geo_zone_id'] = $this->config->get('payment_liqpay_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_liqpay_status'])) {
			$data['payment_liqpay_status'] = $this->request->post['payment_liqpay_status'];
		} else {
			$data['payment_liqpay_status'] = $this->config->get('payment_liqpay_status');
		}

		if (isset($this->request->post['payment_liqpay_sort_order'])) {
			$data['payment_liqpay_sort_order'] = $this->request->post['payment_liqpay_sort_order'];
		} else {
			$data['payment_liqpay_sort_order'] = $this->config->get('payment_liqpay_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/liqpay', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/liqpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_liqpay_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['payment_liqpay_signature']) {
			$this->error['signature'] = $this->language->get('error_signature');
		}

		return !$this->error;
	}
}