<?php
class ControllerPaymentPPPro extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/pp_pro');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_pro', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_authorization'] = $this->language->get('text_authorization');
		$data['text_sale'] = $this->language->get('text_sale');

		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_signature'] = $this->language->get('entry_signature');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_transaction'] = $this->language->get('entry_transaction');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_test'] = $this->language->get('help_test');
		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

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
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/pp_pro', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/pp_pro', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['pp_pro_username'])) {
			$data['pp_pro_username'] = $this->request->post['pp_pro_username'];
		} else {
			$data['pp_pro_username'] = $this->config->get('pp_pro_username');
		}

		if (isset($this->request->post['pp_pro_password'])) {
			$data['pp_pro_password'] = $this->request->post['pp_pro_password'];
		} else {
			$data['pp_pro_password'] = $this->config->get('pp_pro_password');
		}

		if (isset($this->request->post['pp_pro_signature'])) {
			$data['pp_pro_signature'] = $this->request->post['pp_pro_signature'];
		} else {
			$data['pp_pro_signature'] = $this->config->get('pp_pro_signature');
		}

		if (isset($this->request->post['pp_pro_test'])) {
			$data['pp_pro_test'] = $this->request->post['pp_pro_test'];
		} else {
			$data['pp_pro_test'] = $this->config->get('pp_pro_test');
		}

		if (isset($this->request->post['pp_pro_method'])) {
			$data['pp_pro_transaction'] = $this->request->post['pp_pro_transaction'];
		} else {
			$data['pp_pro_transaction'] = $this->config->get('pp_pro_transaction');
		}

		if (isset($this->request->post['pp_pro_total'])) {
			$data['pp_pro_total'] = $this->request->post['pp_pro_total'];
		} else {
			$data['pp_pro_total'] = $this->config->get('pp_pro_total');
		}

		if (isset($this->request->post['pp_pro_order_status_id'])) {
			$data['pp_pro_order_status_id'] = $this->request->post['pp_pro_order_status_id'];
		} else {
			$data['pp_pro_order_status_id'] = $this->config->get('pp_pro_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['pp_pro_geo_zone_id'])) {
			$data['pp_pro_geo_zone_id'] = $this->request->post['pp_pro_geo_zone_id'];
		} else {
			$data['pp_pro_geo_zone_id'] = $this->config->get('pp_pro_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['pp_pro_status'])) {
			$data['pp_pro_status'] = $this->request->post['pp_pro_status'];
		} else {
			$data['pp_pro_status'] = $this->config->get('pp_pro_status');
		}

		if (isset($this->request->post['pp_pro_sort_order'])) {
			$data['pp_pro_sort_order'] = $this->request->post['pp_pro_sort_order'];
		} else {
			$data['pp_pro_sort_order'] = $this->config->get('pp_pro_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/pp_pro.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/pp_pro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['pp_pro_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if (!$this->request->post['pp_pro_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['pp_pro_signature']) {
			$this->error['signature'] = $this->language->get('error_signature');
		}

		return !$this->error;
	}
}