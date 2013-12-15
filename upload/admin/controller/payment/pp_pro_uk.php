<?php 
class ControllerPaymentPPProUK extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/pp_pro_uk');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_pro_uk', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

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
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/pp_pro_uk', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('payment/pp_pro_uk', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['pp_pro_uk_username'])) {
			$data['pp_pro_uk_username'] = $this->request->post['pp_pro_uk_username'];
		} else {
			$data['pp_pro_uk_username'] = $this->config->get('pp_pro_uk_username');
		}

		if (isset($this->request->post['pp_pro_uk_password'])) {
			$data['pp_pro_uk_password'] = $this->request->post['pp_pro_uk_password'];
		} else {
			$data['pp_pro_uk_password'] = $this->config->get('pp_pro_uk_password');
		}

		if (isset($this->request->post['pp_pro_uk_signature'])) {
			$data['pp_pro_uk_signature'] = $this->request->post['pp_pro_uk_signature'];
		} else {
			$data['pp_pro_uk_signature'] = $this->config->get('pp_pro_uk_signature');
		}

		if (isset($this->request->post['pp_pro_uk_test'])) {
			$data['pp_pro_uk_test'] = $this->request->post['pp_pro_uk_test'];
		} else {
			$data['pp_pro_uk_test'] = $this->config->get('pp_pro_uk_test');
		}

		if (isset($this->request->post['pp_pro_uk_method'])) {
			$data['pp_pro_uk_transaction'] = $this->request->post['pp_pro_uk_transaction'];
		} else {
			$data['pp_pro_uk_transaction'] = $this->config->get('pp_pro_uk_transaction');
		}

		if (isset($this->request->post['pp_pro_uk_total'])) {
			$data['pp_pro_uk_total'] = $this->request->post['pp_pro_uk_total'];
		} else {
			$data['pp_pro_uk_total'] = $this->config->get('pp_pro_uk_total'); 
		} 

		if (isset($this->request->post['pp_pro_uk_order_status_id'])) {
			$data['pp_pro_uk_order_status_id'] = $this->request->post['pp_pro_uk_order_status_id'];
		} else {
			$data['pp_pro_uk_order_status_id'] = $this->config->get('pp_pro_uk_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['pp_pro_uk_geo_zone_id'])) {
			$data['pp_pro_uk_geo_zone_id'] = $this->request->post['pp_pro_uk_geo_zone_id'];
		} else {
			$data['pp_pro_uk_geo_zone_id'] = $this->config->get('pp_pro_uk_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['pp_pro_uk_status'])) {
			$data['pp_pro_uk_status'] = $this->request->post['pp_pro_uk_status'];
		} else {
			$data['pp_pro_uk_status'] = $this->config->get('pp_pro_uk_status');
		}

		if (isset($this->request->post['pp_pro_uk_sort_order'])) {
			$data['pp_pro_uk_sort_order'] = $this->request->post['pp_pro_uk_sort_order'];
		} else {
			$data['pp_pro_uk_sort_order'] = $this->config->get('pp_pro_uk_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/pp_pro_uk.tpl', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/pp_pro_uk')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['pp_pro_uk_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if (!$this->request->post['pp_pro_uk_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['pp_pro_uk_signature']) {
			$this->error['signature'] = $this->language->get('error_signature');
		}

		return !$this->error;	
	}
}