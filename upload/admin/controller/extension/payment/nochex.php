<?php
class ControllerExtensionPaymentNOCHEX extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/nochex');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_nochex', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
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
			'href' => $this->url->link('extension/payment/nochex', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/payment/nochex', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		if (isset($this->request->post['payment_nochex_email'])) {
			$data['payment_nochex_email'] = $this->request->post['payment_nochex_email'];
		} else {
			$data['payment_nochex_email'] = $this->config->get('payment_nochex_email');
		}

		if (isset($this->request->post['payment_nochex_account'])) {
			$data['payment_nochex_account'] = $this->request->post['payment_nochex_account'];
		} else {
			$data['payment_nochex_account'] = $this->config->get('payment_nochex_account');
		}

		if (isset($this->request->post['payment_nochex_merchant'])) {
			$data['payment_nochex_merchant'] = $this->request->post['payment_nochex_merchant'];
		} else {
			$data['payment_nochex_merchant'] = $this->config->get('payment_nochex_merchant');
		}

		if (isset($this->request->post['payment_nochex_template'])) {
			$data['payment_nochex_template'] = $this->request->post['payment_nochex_template'];
		} else {
			$data['payment_nochex_template'] = $this->config->get('payment_nochex_template');
		}

		if (isset($this->request->post['payment_nochex_test'])) {
			$data['payment_nochex_test'] = $this->request->post['payment_nochex_test'];
		} else {
			$data['payment_nochex_test'] = $this->config->get('payment_nochex_test');
		}

		if (isset($this->request->post['payment_nochex_total'])) {
			$data['payment_nochex_total'] = $this->request->post['payment_nochex_total'];
		} else {
			$data['payment_nochex_total'] = $this->config->get('payment_nochex_total');
		}

		if (isset($this->request->post['payment_nochex_order_status_id'])) {
			$data['payment_nochex_order_status_id'] = $this->request->post['payment_nochex_order_status_id'];
		} else {
			$data['payment_nochex_order_status_id'] = $this->config->get('payment_nochex_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_nochex_geo_zone_id'])) {
			$data['payment_nochex_geo_zone_id'] = $this->request->post['payment_nochex_geo_zone_id'];
		} else {
			$data['payment_nochex_geo_zone_id'] = $this->config->get('payment_nochex_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_nochex_status'])) {
			$data['payment_nochex_status'] = $this->request->post['payment_nochex_status'];
		} else {
			$data['payment_nochex_status'] = $this->config->get('payment_nochex_status');
		}

		if (isset($this->request->post['payment_nochex_sort_order'])) {
			$data['payment_nochex_sort_order'] = $this->request->post['payment_nochex_sort_order'];
		} else {
			$data['payment_nochex_sort_order'] = $this->config->get('payment_nochex_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/nochex', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/nochex')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_nochex_email']) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if (!$this->request->post['payment_nochex_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		return !$this->error;
	}
}