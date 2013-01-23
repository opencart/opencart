<?php 
class ControllerPaymentGoogleCheckout extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('payment/google_checkout');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('google_checkout', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$this->data['entry_merchant_key'] = $this->language->get('entry_merchant_key');
		$this->data['entry_callback'] = $this->language->get('entry_callback');
		$this->data['entry_test'] = $this->language->get('entry_test');
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

		if (isset($this->error['merchant_id'])) { 
			$this->data['error_merchant_id'] = $this->error['merchant_id'];
		} else {
			$this->data['error_merchant_id'] = '';
		}
		
		if (isset($this->error['merchant_key'])) { 
			$this->data['error_merchant_key'] = $this->error['merchant_key'];
		} else {
			$this->data['error_merchant_key'] = '';
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
			'href'      => $this->url->link('payment/google_checkout', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/google_checkout', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['google_checkout_merchant_id'])) {
			$this->data['google_checkout_merchant_id'] = $this->request->post['google_checkout_merchant_id'];
		} else {
			$this->data['google_checkout_merchant_id'] = $this->config->get('google_checkout_merchant_id');
		}
		
		if (isset($this->request->post['google_checkout_merchant_key'])) {
			$this->data['google_checkout_merchant_key'] = $this->request->post['google_checkout_merchant_key'];
		} else {
			$this->data['google_checkout_merchant_key'] = $this->config->get('google_checkout_merchant_key');
		}
		
		$this->data['callback'] = HTTP_CATALOG . 'index.php?route=payment/google_checkout/callback';
		
		if (isset($this->request->post['google_checkout_test'])) {
			$this->data['google_checkout_test'] = $this->request->post['google_checkout_test'];
		} else {
			$this->data['google_checkout_test'] = $this->config->get('google_checkout_test');
		}
		
		if (isset($this->request->post['google_checkout_total'])) {
			$this->data['google_checkout_total'] = $this->request->post['google_checkout_total'];
		} else {
			$this->data['google_checkout_total'] = $this->config->get('google_checkout_total'); 
		} 

		if (isset($this->request->post['google_checkout_order_status_id'])) {
			$this->data['google_checkout_order_status_id'] = $this->request->post['google_checkout_order_status_id'];
		} else {
			$this->data['google_checkout_order_status_id'] = $this->config->get('google_checkout_order_status_id');
		}
		
		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['google_checkout_geo_zone_id'])) {
			$this->data['google_checkout_geo_zone_id'] = $this->request->post['google_checkout_geo_zone_id'];
		} else {
			$this->data['google_checkout_geo_zone_id'] = $this->config->get('google_checkout_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
								
		if (isset($this->request->post['google_checkout_status'])) {
			$this->data['google_checkout_status'] = $this->request->post['google_checkout_status'];
		} else {
			$this->data['google_checkout_status'] = $this->config->get('google_checkout_status');
		}

		if (isset($this->request->post['google_checkout_sort_order'])) {
			$this->data['google_checkout_sort_order'] = $this->request->post['google_checkout_sort_order'];
		} else {
			$this->data['google_checkout_sort_order'] = $this->config->get('google_checkout_sort_order');
		}
		
		$this->template = 'payment/google_checkout.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
 		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/google_checkout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['google_checkout_merchant_id']) {
			$this->error['merchant_id'] = $this->language->get('error_merchant_id');
		}

		if (!$this->request->post['google_checkout_merchant_key']) {
			$this->error['merchant_key'] = $this->language->get('error_merchant_key');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>