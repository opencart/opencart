<?php 
class ControllerPaymentAuthorizeNet extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/authorizenet');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('authorizenet', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_key'] = $this->language->get('entry_key');
		$this->data['entry_callback'] = $this->language->get('entry_callback');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['help_callback'] = $this->language->get('help_callback');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->data['error_merchant'] = @$this->error['merchant'];
		$this->data['error_key'] = @$this->error['key'];

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
			'href'      => $this->url->link('payment/authorizenet', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/authorizenet', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['authorizenet_merchant'])) {
			$this->data['authorizenet_merchant'] = $this->request->post['authorizenet_merchant'];
		} else {
			$this->data['authorizenet_merchant'] = $this->config->get('authorizenet_merchant');
		}

		if (isset($this->request->post['authorizenet_key'])) {
			$this->data['authorizenet_key'] = $this->request->post['authorizenet_key'];
		} else {
			$this->data['authorizenet_key'] = $this->config->get('authorizenet_key');
		}

		if (isset($this->request->post['authorizenet_test'])) {
			$this->data['authorizenet_test'] = $this->request->post['authorizenet_test'];
		} else {
			$this->data['authorizenet_test'] = $this->config->get('authorizenet_test');
		}
		
		$this->data['callback'] = HTTP_CATALOG . 'index.php?route=payment/authorizenet/callback';
		
		if (isset($this->request->post['authorizenet_total'])) {
			$this->data['authorizenet_total'] = $this->request->post['authorizenet_total'];
		} else {
			$this->data['authorizenet_total'] = $this->config->get('authorizenet_total'); 
		} 
				
		if (isset($this->request->post['authorizenet_order_status_id'])) {
			$this->data['authorizenet_order_status_id'] = $this->request->post['authorizenet_order_status_id'];
		} else {
			$this->data['authorizenet_order_status_id'] = $this->config->get('authorizenet_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();	
		
		if (isset($this->request->post['authorizenet_geo_zone_id'])) {
			$this->data['authorizenet_geo_zone_id'] = $this->request->post['authorizenet_geo_zone_id'];
		} else {
			$this->data['authorizenet_geo_zone_id'] = $this->config->get('authorizenet_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['authorizenet_status'])) {
			$this->data['authorizenet_status'] = $this->request->post['authorizenet_status'];
		} else {
			$this->data['authorizenet_status'] = $this->config->get('authorizenet_status');
		}
		
		if (isset($this->request->post['authorizenet_sort_order'])) {
			$this->data['authorizenet_sort_order'] = $this->request->post['authorizenet_sort_order'];
		} else {
			$this->data['authorizenet_sort_order'] = $this->config->get('authorizenet_sort_order');
		}

		$this->template = 'payment/authorizenet.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
 		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/authorizenet')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!@$this->request->post['authorizenet_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!@$this->request->post['authorizenet_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>