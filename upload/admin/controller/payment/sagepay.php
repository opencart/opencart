<?php 
class ControllerPaymentSagepay extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/sagepay');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('sagepay', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_sim'] = $this->language->get('text_sim');
		$data['text_test'] = $this->language->get('text_test');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_defered'] = $this->language->get('text_defered');
		$data['text_authenticate'] = $this->language->get('text_authenticate');
		
		$data['entry_vendor'] = $this->language->get('entry_vendor');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_transaction'] = $this->language->get('entry_transaction');
		$data['entry_total'] = $this->language->get('entry_total');	
		$data['entry_order_status'] = $this->language->get('entry_order_status');		
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$data['help_total'] = $this->language->get('help_total');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

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

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/sagepay', 'token=' . $this->session->data['token'], 'SSL')
		);
				
		$data['action'] = $this->url->link('payment/sagepay', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['sagepay_vendor'])) {
			$data['sagepay_vendor'] = $this->request->post['sagepay_vendor'];
		} else {
			$data['sagepay_vendor'] = $this->config->get('sagepay_vendor');
		}
		
		if (isset($this->request->post['sagepay_password'])) {
			$data['sagepay_password'] = $this->request->post['sagepay_password'];
		} else {
			$data['sagepay_password'] = $this->config->get('sagepay_password');
		}

		if (isset($this->request->post['sagepay_test'])) {
			$data['sagepay_test'] = $this->request->post['sagepay_test'];
		} else {
			$data['sagepay_test'] = $this->config->get('sagepay_test');
		}
		
		if (isset($this->request->post['sagepay_transaction'])) {
			$data['sagepay_transaction'] = $this->request->post['sagepay_transaction'];
		} else {
			$data['sagepay_transaction'] = $this->config->get('sagepay_transaction');
		}
		
		if (isset($this->request->post['sagepay_total'])) {
			$data['sagepay_total'] = $this->request->post['sagepay_total'];
		} else {
			$data['sagepay_total'] = $this->config->get('sagepay_total'); 
		} 
				
		if (isset($this->request->post['sagepay_order_status_id'])) {
			$data['sagepay_order_status_id'] = $this->request->post['sagepay_order_status_id'];
		} else {
			$data['sagepay_order_status_id'] = $this->config->get('sagepay_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['sagepay_geo_zone_id'])) {
			$data['sagepay_geo_zone_id'] = $this->request->post['sagepay_geo_zone_id'];
		} else {
			$data['sagepay_geo_zone_id'] = $this->config->get('sagepay_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['sagepay_status'])) {
			$data['sagepay_status'] = $this->request->post['sagepay_status'];
		} else {
			$data['sagepay_status'] = $this->config->get('sagepay_status');
		}
		
		if (isset($this->request->post['sagepay_sort_order'])) {
			$data['sagepay_sort_order'] = $this->request->post['sagepay_sort_order'];
		} else {
			$data['sagepay_sort_order'] = $this->config->get('sagepay_sort_order');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('payment/sagepay.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/sagepay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['sagepay_vendor']) {
			$this->error['vendor'] = $this->language->get('error_vendor');
		}

		if (!$this->request->post['sagepay_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}
		
		return !$this->error;
	}
}