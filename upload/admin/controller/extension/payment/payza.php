<?php
class ControllerExtensionPaymentPayza extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/payza');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_payza', $this->request->post);

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

		if (isset($this->error['security'])) {
			$data['error_security'] = $this->error['security'];
		} else {
			$data['error_security'] = '';
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
			'href' => $this->url->link('extension/payment/payza', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/payza', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_payza_merchant'])) {
			$data['payment_payza_merchant'] = $this->request->post['payment_payza_merchant'];
		} else {
			$data['payment_payza_merchant'] = $this->config->get('payment_payza_merchant');
		}

		if (isset($this->request->post['payment_payza_security'])) {
			$data['payment_payza_security'] = $this->request->post['payment_payza_security'];
		} else {
			$data['payment_payza_security'] = $this->config->get('payment_payza_security');
		}

		$data['callback'] = HTTP_CATALOG . 'index.php?route=extension/payment/payza/callback';

		if (isset($this->request->post['payment_payza_total'])) {
			$data['payment_payza_total'] = $this->request->post['payment_payza_total'];
		} else {
			$data['payment_payza_total'] = $this->config->get('payment_payza_total');
		}

		if (isset($this->request->post['payment_payza_order_status_id'])) {
			$data['payment_payza_order_status_id'] = $this->request->post['payment_payza_order_status_id'];
		} else {
			$data['payment_payza_order_status_id'] = $this->config->get('payment_payza_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_payza_geo_zone_id'])) {
			$data['payment_payza_geo_zone_id'] = $this->request->post['payment_payza_geo_zone_id'];
		} else {
			$data['payment_payza_geo_zone_id'] = $this->config->get('payment_payza_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_payza_status'])) {
			$data['payment_payza_status'] = $this->request->post['payment_payza_status'];
		} else {
			$data['payment_payza_status'] = $this->config->get('payment_payza_status');
		}

		if (isset($this->request->post['payment_payza_sort_order'])) {
			$data['payment_payza_sort_order'] = $this->request->post['payment_payza_sort_order'];
		} else {
			$data['payment_payza_sort_order'] = $this->config->get('payment_payza_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/payza', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/payza')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_payza_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['payment_payza_security']) {
			$this->error['security'] = $this->language->get('error_security');
		}

		return !$this->error;
	}
}