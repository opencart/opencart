<?php 
class ControllerPaymentPPProPF extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/pp_pro_pf');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_pro_pf', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_authorization'] = $this->language->get('text_authorization');
		$this->data['text_sale'] = $this->language->get('text_sale');

		$this->data['entry_vendor'] = $this->language->get('entry_vendor');
		$this->data['entry_user'] = $this->language->get('entry_user');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_partner'] = $this->language->get('entry_partner');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_transaction'] = $this->language->get('entry_transaction');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['vendor'])) {
			$this->data['error_vendor'] = $this->error['vendor'];
		} else {
			$this->data['error_vendor'] = '';
		}

		if (isset($this->error['user'])) {
			$this->data['error_user'] = $this->error['user'];
		} else {
			$this->data['error_user'] = '';
		}

		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}

		if (isset($this->error['partner'])) {
			$this->data['error_partner'] = $this->error['partner'];
		} else {
			$this->data['error_partner'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/pp_pro_pf', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('payment/pp_pro_pf', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['pp_pro_pf_vendor'])) {
			$this->data['pp_pro_pf_vendor'] = $this->request->post['pp_pro_pf_vendor'];
		} else {
			$this->data['pp_pro_pf_vendor'] = $this->config->get('pp_pro_pf_vendor');
		}

		if (isset($this->request->post['pp_pro_pf_user'])) {
			$this->data['pp_pro_pf_user'] = $this->request->post['pp_pro_pf_user'];
		} else {
			$this->data['pp_pro_pf_user'] = $this->config->get('pp_pro_pf_user');
		}

		if (isset($this->request->post['pp_pro_pf_password'])) {
			$this->data['pp_pro_pf_password'] = $this->request->post['pp_pro_pf_password'];
		} else {
			$this->data['pp_pro_pf_password'] = $this->config->get('pp_pro_pf_password');
		}

		if (isset($this->request->post['pp_pro_pf_partner'])) {
			$this->data['pp_pro_pf_partner'] = $this->request->post['pp_pro_pf_partner'];
		} elseif ($this->config->has('pp_pro_pf_partner')) {
			$this->data['pp_pro_pf_partner'] = $this->config->get('pp_pro_pf_partner');
		} else {
			$this->data['pp_pro_pf_partner'] = 'PayPal';
		}

		if (isset($this->request->post['pp_pro_pf_test'])) {
			$this->data['pp_pro_pf_test'] = $this->request->post['pp_pro_pf_test'];
		} else {
			$this->data['pp_pro_pf_test'] = $this->config->get('pp_pro_pf_test');
		}

		if (isset($this->request->post['pp_pro_pf_method'])) {
			$this->data['pp_pro_pf_transaction'] = $this->request->post['pp_pro_pf_transaction'];
		} else {
			$this->data['pp_pro_pf_transaction'] = $this->config->get('pp_pro_pf_transaction');
		}

		if (isset($this->request->post['pp_pro_pf_total'])) {
			$this->data['pp_pro_pf_total'] = $this->request->post['pp_pro_pf_total'];
		} else {
			$this->data['pp_pro_pf_total'] = $this->config->get('pp_pro_pf_total'); 
		} 

		if (isset($this->request->post['pp_pro_pf_order_status_id'])) {
			$this->data['pp_pro_pf_order_status_id'] = $this->request->post['pp_pro_pf_order_status_id'];
		} else {
			$this->data['pp_pro_pf_order_status_id'] = $this->config->get('pp_pro_pf_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['pp_pro_pf_geo_zone_id'])) {
			$this->data['pp_pro_pf_geo_zone_id'] = $this->request->post['pp_pro_pf_geo_zone_id'];
		} else {
			$this->data['pp_pro_pf_geo_zone_id'] = $this->config->get('pp_pro_pf_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['pp_pro_pf_status'])) {
			$this->data['pp_pro_pf_status'] = $this->request->post['pp_pro_pf_status'];
		} else {
			$this->data['pp_pro_pf_status'] = $this->config->get('pp_pro_pf_status');
		}

		if (isset($this->request->post['pp_pro_pf_sort_order'])) {
			$this->data['pp_pro_pf_sort_order'] = $this->request->post['pp_pro_pf_sort_order'];
		} else {
			$this->data['pp_pro_pf_sort_order'] = $this->config->get('pp_pro_pf_sort_order');
		}

		$this->template = 'payment/pp_pro_pf.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/pp_pro_pf')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['pp_pro_pf_vendor']) {
			$this->error['vendor'] = $this->language->get('error_vendor');
		}

		if (!$this->request->post['pp_pro_pf_user']) {
			$this->error['user'] = $this->language->get('error_user');
		}

		if (!$this->request->post['pp_pro_pf_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['pp_pro_pf_partner']) {
			$this->error['partner'] = $this->language->get('error_partner');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>