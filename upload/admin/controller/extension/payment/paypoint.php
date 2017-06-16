<?php
class ControllerExtensionPaymentPayPoint extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/paypoint');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_paypoint', $this->request->post);

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
			'href' => $this->url->link('extension/payment/paypoint', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/paypoint', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_paypoint_merchant'])) {
			$data['payment_paypoint_merchant'] = $this->request->post['payment_paypoint_merchant'];
		} else {
			$data['payment_paypoint_merchant'] = $this->config->get('payment_paypoint_merchant');
		}

		if (isset($this->request->post['payment_paypoint_password'])) {
			$data['payment_paypoint_password'] = $this->request->post['payment_paypoint_password'];
		} else {
			$data['payment_paypoint_password'] = $this->config->get('payment_paypoint_password');
		}

		if (isset($this->request->post['payment_paypoint_test'])) {
			$data['payment_paypoint_test'] = $this->request->post['payment_paypoint_test'];
		} else {
			$data['payment_paypoint_test'] = $this->config->get('payment_paypoint_test');
		}

		if (isset($this->request->post['payment_paypoint_total'])) {
			$data['payment_paypoint_total'] = $this->request->post['payment_paypoint_total'];
		} else {
			$data['payment_paypoint_total'] = $this->config->get('payment_paypoint_total');
		}

		if (isset($this->request->post['payment_paypoint_order_status_id'])) {
			$data['payment_paypoint_order_status_id'] = $this->request->post['payment_paypoint_order_status_id'];
		} else {
			$data['payment_paypoint_order_status_id'] = $this->config->get('payment_paypoint_order_status_id');
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

		$this->response->setOutput($this->load->view('extension/payment/paypoint', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/paypoint')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_paypoint_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		return !$this->error;
	}
}