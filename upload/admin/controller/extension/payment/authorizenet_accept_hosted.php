<?php
class ControllerExtensionPaymentAuthorizeNetAcceptHosted extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/authorizenet_accept_hosted');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_authorizenet_accept_hosted', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
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
			'href' => $this->url->link('extension/payment/authorizenet_accept_hosted', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/payment/authorizenet_accept_hosted', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		if (isset($this->request->post['payment_authorizenet_accept_hosted_name'])) {
			$data['payment_authorizenet_accept_hosted_name'] = $this->request->post['payment_authorizenet_accept_hosted_name'];
		} else {
			$data['payment_authorizenet_accept_hosted_name'] = $this->config->get('payment_authorizenet_accept_hosted_name');
		}

		if (isset($this->request->post['payment_authorizenet_accept_hosted_transaction_key'])) {
			$data['payment_authorizenet_accept_hosted_transaction_key'] = $this->request->post['payment_authorizenet_accept_hosted_transaction_key'];
		} else {
			$data['payment_authorizenet_accept_hosted_transaction_key'] = $this->config->get('payment_authorizenet_accept_hosted_transaction_key');
		}

		if (isset($this->request->post['payment_authorizenet_accept_hosted_credit_enabled'])) {
			$data['payment_authorizenet_accept_hosted_credit_enabled'] = $this->request->post['payment_authorizenet_accept_hosted_credit_enabled'];
		} else {
			$data['payment_authorizenet_accept_hosted_credit_enabled'] = $this->config->get('payment_authorizenet_accept_hosted_credit_enabled');
		}

		if (isset($this->request->post['payment_authorizenet_accept_hosted_ach_enabled'])) {
			$data['payment_authorizenet_accept_hosted_ach_enabled'] = $this->request->post['payment_authorizenet_accept_hosted_ach_enabled'];
		} else {
			$data['payment_authorizenet_accept_hosted_ach_enabled'] = $this->config->get('payment_authorizenet_accept_hosted_ach_enabled');
		}

		if (isset($this->request->post['payment_authorizenet_accept_hosted_testmode'])) {
			$data['payment_authorizenet_accept_hosted_testmode'] = $this->request->post['payment_authorizenet_accept_hosted_testmode'];
		} else {
			$data['payment_authorizenet_accept_hosted_testmode'] = $this->config->get('payment_authorizenet_accept_hosted_testmode');
		}

		if (isset($this->request->post['payment_authorizenet_accept_hosted_debug'])) {
			$data['payment_authorizenet_accept_hosted_debug'] = $this->request->post['payment_authorizenet_accept_hosted_debug'];
		} else {
			$data['payment_authorizenet_accept_hosted_debug'] = $this->config->get('payment_authorizenet_accept_hosted_debug');
		}

		$data['callback'] = HTTP_CATALOG . 'index.php?route=extension/payment/authorizenet_accept_hosted/callback';

		if (isset($this->request->post['payment_authorizenet_accept_hosted_total'])) {
			$data['payment_authorizenet_accept_hosted_total'] = $this->request->post['payment_authorizenet_accept_hosted_total'];
		} else {
			$data['payment_authorizenet_accept_hosted_total'] = $this->config->get('payment_authorizenet_accept_hosted_total');
		}

		if (isset($this->request->post['payment_authorizenet_accept_hosted_order_status_id'])) {
			$data['payment_authorizenet_accept_hosted_order_status_id'] = $this->request->post['payment_authorizenet_accept_hosted_order_status_id'];
		} else {
			$data['payment_authorizenet_accept_hosted_order_status_id'] = $this->config->get('payment_authorizenet_accept_hosted_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_authorizenet_accept_hosted_geo_zone_id'])) {
			$data['payment_authorizenet_accept_hosted_geo_zone_id'] = $this->request->post['payment_authorizenet_accept_hosted_geo_zone_id'];
		} else {
			$data['payment_authorizenet_accept_hosted_geo_zone_id'] = $this->config->get('payment_authorizenet_accept_hosted_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_authorizenet_accept_hosted_status'])) {
			$data['payment_authorizenet_accept_hosted_status'] = $this->request->post['payment_authorizenet_accept_hosted_status'];
		} else {
			$data['payment_authorizenet_accept_hosted_status'] = $this->config->get('payment_authorizenet_accept_hosted_status');
		}

		if (isset($this->request->post['payment_authorizenet_accept_hosted_sort_order'])) {
			$data['payment_authorizenet_accept_hosted_sort_order'] = $this->request->post['payment_authorizenet_accept_hosted_sort_order'];
		} else {
			$data['payment_authorizenet_accept_hosted_sort_order'] = $this->config->get('payment_authorizenet_accept_hosted_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/authorizenet_accept_hosted', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/authorizenet_accept_hosted')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_authorizenet_accept_hosted_name']) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['payment_authorizenet_accept_hosted_transaction_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		return !$this->error;
	}
}
