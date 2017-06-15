<?php
class ControllerExtensionPaymentAuthorizenetAim extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/authorizenet_aim');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_authorizenet_aim', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/authorizenet_aim', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/authorizenet_aim', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_authorizenet_aim_login'])) {
			$data['payment_authorizenet_aim_login'] = $this->request->post['payment_authorizenet_aim_login'];
		} else {
			$data['payment_authorizenet_aim_login'] = $this->config->get('payment_authorizenet_aim_login');
		}

		if (isset($this->request->post['payment_authorizenet_aim_key'])) {
			$data['payment_authorizenet_aim_key'] = $this->request->post['payment_authorizenet_aim_key'];
		} else {
			$data['payment_authorizenet_aim_key'] = $this->config->get('payment_authorizenet_aim_key');
		}

		if (isset($this->request->post['payment_authorizenet_aim_hash'])) {
			$data['payment_authorizenet_aim_hash'] = $this->request->post['payment_authorizenet_aim_hash'];
		} else {
			$data['payment_authorizenet_aim_hash'] = $this->config->get('payment_authorizenet_aim_hash');
		}

		if (isset($this->request->post['payment_authorizenet_aim_server'])) {
			$data['payment_authorizenet_aim_server'] = $this->request->post['payment_authorizenet_aim_server'];
		} else {
			$data['payment_authorizenet_aim_server'] = $this->config->get('payment_authorizenet_aim_server');
		}

		if (isset($this->request->post['payment_authorizenet_aim_mode'])) {
			$data['payment_authorizenet_aim_mode'] = $this->request->post['payment_authorizenet_aim_mode'];
		} else {
			$data['payment_authorizenet_aim_mode'] = $this->config->get('payment_authorizenet_aim_mode');
		}

		if (isset($this->request->post['payment_authorizenet_aim_method'])) {
			$data['payment_authorizenet_aim_method'] = $this->request->post['payment_authorizenet_aim_method'];
		} else {
			$data['payment_authorizenet_aim_method'] = $this->config->get('payment_authorizenet_aim_method');
		}

		if (isset($this->request->post['payment_authorizenet_aim_total'])) {
			$data['payment_authorizenet_aim_total'] = $this->request->post['payment_authorizenet_aim_total'];
		} else {
			$data['payment_authorizenet_aim_total'] = $this->config->get('payment_authorizenet_aim_total');
		}

		if (isset($this->request->post['payment_authorizenet_aim_order_status_id'])) {
			$data['payment_authorizenet_aim_order_status_id'] = $this->request->post['payment_authorizenet_aim_order_status_id'];
		} else {
			$data['payment_authorizenet_aim_order_status_id'] = $this->config->get('payment_authorizenet_aim_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_authorizenet_aim_geo_zone_id'])) {
			$data['payment_authorizenet_aim_geo_zone_id'] = $this->request->post['payment_authorizenet_aim_geo_zone_id'];
		} else {
			$data['payment_authorizenet_aim_geo_zone_id'] = $this->config->get('payment_authorizenet_aim_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_authorizenet_aim_status'])) {
			$data['payment_authorizenet_aim_status'] = $this->request->post['payment_authorizenet_aim_status'];
		} else {
			$data['payment_authorizenet_aim_status'] = $this->config->get('payment_authorizenet_aim_status');
		}

		if (isset($this->request->post['payment_authorizenet_aim_sort_order'])) {
			$data['payment_authorizenet_aim_sort_order'] = $this->request->post['payment_authorizenet_aim_sort_order'];
		} else {
			$data['payment_authorizenet_aim_sort_order'] = $this->config->get('payment_authorizenet_aim_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/authorizenet_aim', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/authorizenet_aim')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_authorizenet_aim_login']) {
			$this->error['login'] = $this->language->get('error_login');
		}

		if (!$this->request->post['payment_authorizenet_aim_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		return !$this->error;
	}
}
