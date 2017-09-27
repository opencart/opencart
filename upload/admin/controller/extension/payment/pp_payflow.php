<?php
class ControllerExtensionPaymentPPPayflow extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/pp_payflow');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_pp_payflow', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['vendor'])) {
			$data['error_vendor'] = $this->error['vendor'];
		} else {
			$data['error_vendor'] = '';
		}

		if (isset($this->error['user'])) {
			$data['error_user'] = $this->error['user'];
		} else {
			$data['error_user'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['partner'])) {
			$data['error_partner'] = $this->error['partner'];
		} else {
			$data['error_partner'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_pp_express'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/pp_payflow', 'user_token=' . $this->session->data['user_token'], true),
		);

		$data['action'] = $this->url->link('extension/payment/pp_payflow', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_pp_payflow_vendor'])) {
			$data['payment_pp_payflow_vendor'] = $this->request->post['payment_pp_payflow_vendor'];
		} else {
			$data['payment_pp_payflow_vendor'] = $this->config->get('payment_pp_payflow_vendor');
		}

		if (isset($this->request->post['payment_pp_payflow_user'])) {
			$data['payment_pp_payflow_user'] = $this->request->post['payment_pp_payflow_user'];
		} else {
			$data['payment_pp_payflow_user'] = $this->config->get('payment_pp_payflow_user');
		}

		if (isset($this->request->post['payment_pp_payflow_password'])) {
			$data['payment_pp_payflow_password'] = $this->request->post['payment_pp_payflow_password'];
		} else {
			$data['payment_pp_payflow_password'] = $this->config->get('payment_pp_payflow_password');
		}

		if (isset($this->request->post['payment_pp_payflow_partner'])) {
			$data['payment_pp_payflow_partner'] = $this->request->post['payment_pp_payflow_partner'];
		} elseif ($this->config->has('payment_pp_payflow_partner')) {
			$data['payment_pp_payflow_partner'] = $this->config->get('payment_pp_payflow_partner');
		} else {
			$data['payment_pp_payflow_partner'] = 'PayPal';
		}

		if (isset($this->request->post['payment_pp_payflow_test'])) {
			$data['payment_pp_payflow_test'] = $this->request->post['payment_pp_payflow_test'];
		} else {
			$data['payment_pp_payflow_test'] = $this->config->get('payment_pp_payflow_test');
		}

		if (isset($this->request->post['payment_pp_payflow_transaction'])) {
			$data['payment_pp_payflow_transaction'] = $this->request->post['payment_pp_payflow_transaction'];
		} else {
			$data['payment_pp_payflow_transaction'] = $this->config->get('payment_pp_payflow_transaction');
		}

		if (isset($this->request->post['payment_pp_payflow_total'])) {
			$data['payment_pp_payflow_total'] = $this->request->post['payment_pp_payflow_total'];
		} else {
			$data['payment_pp_payflow_total'] = $this->config->get('payment_pp_payflow_total');
		}

		if (isset($this->request->post['payment_pp_payflow_order_status_id'])) {
			$data['payment_pp_payflow_order_status_id'] = $this->request->post['payment_pp_payflow_order_status_id'];
		} else {
			$data['payment_pp_payflow_order_status_id'] = $this->config->get('payment_pp_payflow_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_pp_payflow_geo_zone_id'])) {
			$data['payment_pp_payflow_geo_zone_id'] = $this->request->post['payment_pp_payflow_geo_zone_id'];
		} else {
			$data['payment_pp_payflow_geo_zone_id'] = $this->config->get('payment_pp_payflow_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_pp_payflow_status'])) {
			$data['payment_pp_payflow_status'] = $this->request->post['payment_pp_payflow_status'];
		} else {
			$data['payment_pp_payflow_status'] = $this->config->get('payment_pp_payflow_status');
		}

		if (isset($this->request->post['payment_pp_payflow_sort_order'])) {
			$data['payment_pp_payflow_sort_order'] = $this->request->post['payment_pp_payflow_sort_order'];
		} else {
			$data['payment_pp_payflow_sort_order'] = $this->config->get('payment_pp_payflow_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/pp_payflow', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/pp_payflow')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_pp_payflow_vendor']) {
			$this->error['vendor'] = $this->language->get('error_vendor');
		}

		if (!$this->request->post['payment_pp_payflow_user']) {
			$this->error['user'] = $this->language->get('error_user');
		}

		if (!$this->request->post['payment_pp_payflow_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['payment_pp_payflow_partner']) {
			$this->error['partner'] = $this->language->get('error_partner');
		}

		return !$this->error;
	}
}