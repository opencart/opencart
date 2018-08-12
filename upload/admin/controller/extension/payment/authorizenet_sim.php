<?php
class ControllerExtensionPaymentAuthorizeNetSim extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/authorizenet_sim');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_authorizenet_sim', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment'));
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
			'href' => $this->url->link('extension/payment/authorizenet_sim', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/payment/authorizenet_sim', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		if (isset($this->request->post['payment_authorizenet_sim_merchant'])) {
			$data['payment_authorizenet_sim_merchant'] = $this->request->post['payment_authorizenet_sim_merchant'];
		} else {
			$data['payment_authorizenet_sim_merchant'] = $this->config->get('payment_authorizenet_sim_merchant');
		}

		if (isset($this->request->post['payment_authorizenet_sim_key'])) {
			$data['payment_authorizenet_sim_key'] = $this->request->post['payment_authorizenet_sim_key'];
		} else {
			$data['payment_authorizenet_sim_key'] = $this->config->get('payment_authorizenet_sim_key');
		}

		if (isset($this->request->post['payment_authorizenet_sim_test'])) {
			$data['payment_authorizenet_sim_test'] = $this->request->post['payment_authorizenet_sim_test'];
		} else {
			$data['payment_authorizenet_sim_test'] = $this->config->get('payment_authorizenet_sim_test');
		}

		$data['callback'] = HTTP_CATALOG . 'index.php?route=extension/payment/authorizenet_sim/callback';

		if (isset($this->request->post['payment_authorizenet_sim_md5'])) {
			$data['payment_authorizenet_sim_md5'] = $this->request->post['payment_authorizenet_sim_md5'];
		} else {
			$data['payment_authorizenet_sim_md5'] = $this->config->get('payment_authorizenet_sim_md5');
		}

		if (isset($this->request->post['payment_authorizenet_sim_total'])) {
			$data['payment_authorizenet_sim_total'] = $this->request->post['payment_authorizenet_sim_total'];
		} else {
			$data['payment_authorizenet_sim_total'] = $this->config->get('payment_authorizenet_sim_total');
		}

		if (isset($this->request->post['payment_authorizenet_sim_order_status_id'])) {
			$data['payment_authorizenet_sim_order_status_id'] = $this->request->post['payment_authorizenet_sim_order_status_id'];
		} else {
			$data['payment_authorizenet_sim_order_status_id'] = $this->config->get('payment_authorizenet_sim_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_authorizenet_sim_geo_zone_id'])) {
			$data['payment_authorizenet_sim_geo_zone_id'] = $this->request->post['payment_authorizenet_sim_geo_zone_id'];
		} else {
			$data['payment_authorizenet_sim_geo_zone_id'] = $this->config->get('payment_authorizenet_sim_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_authorizenet_sim_status'])) {
			$data['payment_authorizenet_sim_status'] = $this->request->post['payment_authorizenet_sim_status'];
		} else {
			$data['payment_authorizenet_sim_status'] = $this->config->get('payment_authorizenet_sim_status');
		}

		if (isset($this->request->post['payment_authorizenet_sim_sort_order'])) {
			$data['payment_authorizenet_sim_sort_order'] = $this->request->post['payment_authorizenet_sim_sort_order'];
		} else {
			$data['payment_authorizenet_sim_sort_order'] = $this->config->get('payment_authorizenet_sim_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/authorizenet_sim', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/authorizenet_sim')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_authorizenet_sim_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['payment_authorizenet_sim_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		return !$this->error;
	}
}
