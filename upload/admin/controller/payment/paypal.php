<?php 
class ControllerPaymentPayPal extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/paypal');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('paypal', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->https('extension/payment'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
				
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['error_warning'] = @$this->error['warning'];
		$this->data['error_email'] = @$this->error['email'];

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('extension/payment'),
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('payment/paypal'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->https('payment/paypal');
		
		$this->data['cancel'] = $this->url->https('extension/payment');
		
		if (isset($this->request->post['paypal_status'])) {
			$this->data['paypal_status'] = $this->request->post['paypal_status'];
		} else {
			$this->data['paypal_status'] = $this->config->get('paypal_status');
		}
		
		if (isset($this->request->post['paypal_geo_zone_id'])) {
			$this->data['paypal_geo_zone_id'] = $this->request->post['paypal_geo_zone_id'];
		} else {
			$this->data['paypal_geo_zone_id'] = $this->config->get('paypal_geo_zone_id'); 
		} 

		if (isset($this->request->post['paypal_order_status_id'])) {
			$this->data['paypal_order_status_id'] = $this->request->post['paypal_order_status_id'];
		} else {
			$this->data['paypal_order_status_id'] = $this->config->get('paypal_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['paypal_email'])) {
			$this->data['paypal_email'] = $this->request->post['paypal_email'];
		} else {
			$this->data['paypal_email'] = $this->config->get('paypal_email');
		}
		
		if (isset($this->request->post['paypal_test'])) {
			$this->data['paypal_test'] = $this->request->post['paypal_test'];
		} else {
			$this->data['paypal_test'] = $this->config->get('paypal_test');
		}
		
		if (isset($this->request->post['paypal_sort_order'])) {
			$this->data['paypal_sort_order'] = $this->request->post['paypal_sort_order'];
		} else {
			$this->data['paypal_sort_order'] = $this->config->get('paypal_sort_order');
		}
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->id       = 'content';
		$this->template = 'payment/paypal.tpl';
		$this->layout   = 'module/layout';
		
 		$this->render();
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paypal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!@$this->request->post['paypal_email']) {
			$this->error['email'] = $this->language->get('error_email');
		}
				
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>