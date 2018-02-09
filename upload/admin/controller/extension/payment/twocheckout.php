<?php
class ControllerExtensionPaymentTwoCheckout extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/twocheckout');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_twocheckout', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['account'])) {
			$data['error_account'] = $this->error['account'];
		} else {
			$data['error_account'] = '';
		}

		if (isset($this->error['secret'])) {
			$data['error_secret'] = $this->error['secret'];
		} else {
			$data['error_secret'] = '';
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
			'href' => $this->url->link('extension/payment/twocheckout', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/twocheckout', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_twocheckout_account'])) {
			$data['payment_twocheckout_account'] = $this->request->post['payment_twocheckout_account'];
		} else {
			$data['payment_twocheckout_account'] = $this->config->get('payment_twocheckout_account');
		}

		if (isset($this->request->post['payment_twocheckout_secret'])) {
			$data['payment_twocheckout_secret'] = $this->request->post['payment_twocheckout_secret'];
		} else {
			$data['payment_twocheckout_secret'] = $this->config->get('payment_twocheckout_secret');
		}

		if (isset($this->request->post['payment_twocheckout_display'])) {
			$data['payment_twocheckout_display'] = $this->request->post['payment_twocheckout_display'];
		} else {
			$data['payment_twocheckout_display'] = $this->config->get('payment_twocheckout_display');
		}

		if (isset($this->request->post['payment_twocheckout_test'])) {
			$data['payment_twocheckout_test'] = $this->request->post['payment_twocheckout_test'];
		} else {
			$data['payment_twocheckout_test'] = $this->config->get('payment_twocheckout_test');
		}

		if (isset($this->request->post['payment_twocheckout_total'])) {
			$data['payment_twocheckout_total'] = $this->request->post['payment_twocheckout_total'];
		} else {
			$data['payment_twocheckout_total'] = $this->config->get('payment_twocheckout_total');
		}

		if (isset($this->request->post['payment_twocheckout_order_status_id'])) {
			$data['payment_twocheckout_order_status_id'] = $this->request->post['payment_twocheckout_order_status_id'];
		} else {
			$data['payment_twocheckout_order_status_id'] = $this->config->get('payment_twocheckout_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_twocheckout_geo_zone_id'])) {
			$data['payment_twocheckout_geo_zone_id'] = $this->request->post['payment_twocheckout_geo_zone_id'];
		} else {
			$data['payment_twocheckout_geo_zone_id'] = $this->config->get('payment_twocheckout_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_twocheckout_status'])) {
			$data['payment_twocheckout_status'] = $this->request->post['payment_twocheckout_status'];
		} else {
			$data['payment_twocheckout_status'] = $this->config->get('payment_twocheckout_status');
		}

		if (isset($this->request->post['payment_twocheckout_sort_order'])) {
			$data['payment_twocheckout_sort_order'] = $this->request->post['payment_twocheckout_sort_order'];
		} else {
			$data['payment_twocheckout_sort_order'] = $this->config->get('payment_twocheckout_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/twocheckout', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/twocheckout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_twocheckout_account']) {
			$this->error['account'] = $this->language->get('error_account');
		}

		if (!$this->request->post['payment_twocheckout_secret']) {
			$this->error['secret'] = $this->language->get('error_secret');
		}

		return !$this->error;
	}
}