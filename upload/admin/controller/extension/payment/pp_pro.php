<?php
class ControllerExtensionPaymentPPPro extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/pp_pro');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_pp_pro', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['signature'])) {
			$data['error_signature'] = $this->error['signature'];
		} else {
			$data['error_signature'] = '';
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
			'href' => $this->url->link('extension/payment/pp_pro', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/payment/pp_pro', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		if (isset($this->request->post['payment_pp_pro_username'])) {
			$data['payment_pp_pro_username'] = $this->request->post['payment_pp_pro_username'];
		} else {
			$data['payment_pp_pro_username'] = $this->config->get('payment_pp_pro_username');
		}

		if (isset($this->request->post['payment_pp_pro_password'])) {
			$data['payment_pp_pro_password'] = $this->request->post['payment_pp_pro_password'];
		} else {
			$data['payment_pp_pro_password'] = $this->config->get('payment_pp_pro_password');
		}

		if (isset($this->request->post['payment_pp_pro_signature'])) {
			$data['payment_pp_pro_signature'] = $this->request->post['payment_pp_pro_signature'];
		} else {
			$data['payment_pp_pro_signature'] = $this->config->get('payment_pp_pro_signature');
		}

		if (isset($this->request->post['payment_pp_pro_test'])) {
			$data['payment_pp_pro_test'] = $this->request->post['payment_pp_pro_test'];
		} else {
			$data['payment_pp_pro_test'] = $this->config->get('payment_pp_pro_test');
		}

		if (isset($this->request->post['payment_pp_pro_transaction'])) {
			$data['payment_pp_pro_transaction'] = $this->request->post['payment_pp_pro_transaction'];
		} else {
			$data['payment_pp_pro_transaction'] = $this->config->get('payment_pp_pro_transaction');
		}

		if (isset($this->request->post['payment_pp_pro_total'])) {
			$data['payment_pp_pro_total'] = $this->request->post['payment_pp_pro_total'];
		} else {
			$data['payment_pp_pro_total'] = $this->config->get('payment_pp_pro_total');
		}

		if (isset($this->request->post['payment_pp_pro_order_status_id'])) {
			$data['payment_pp_pro_order_status_id'] = $this->request->post['payment_pp_pro_order_status_id'];
		} else {
			$data['payment_pp_pro_order_status_id'] = $this->config->get('payment_pp_pro_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_pp_pro_geo_zone_id'])) {
			$data['payment_pp_pro_geo_zone_id'] = $this->request->post['payment_pp_pro_geo_zone_id'];
		} else {
			$data['payment_pp_pro_geo_zone_id'] = $this->config->get('payment_pp_pro_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_pp_pro_status'])) {
			$data['payment_pp_pro_status'] = $this->request->post['payment_pp_pro_status'];
		} else {
			$data['payment_pp_pro_status'] = $this->config->get('payment_pp_pro_status');
		}

		if (isset($this->request->post['payment_pp_pro_sort_order'])) {
			$data['payment_pp_pro_sort_order'] = $this->request->post['payment_pp_pro_sort_order'];
		} else {
			$data['payment_pp_pro_sort_order'] = $this->config->get('payment_pp_pro_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/pp_pro', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/pp_pro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_pp_pro_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if (!$this->request->post['payment_pp_pro_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['payment_pp_pro_signature']) {
			$this->error['signature'] = $this->language->get('error_signature');
		}

		return !$this->error;
	}
}