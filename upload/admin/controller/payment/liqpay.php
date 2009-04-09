<?php 
class ControllerPaymentLiqPay extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/liqpay');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('liqpay', $this->request->post);				
			
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
		$this->data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$this->data['entry_merchant_signature'] = $this->language->get('entry_merchant_signature');
		$this->data['entry_liqpay_type'] = $this->language->get('entry_liqpay_type');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['help_merchant_id'] = $this->language->get('help_merchant_id');
		$this->data['help_merchant_signature'] = $this->language->get('help_merchant_signature');
		$this->data['help_liqpay_type'] = $this->language->get('help_liqpay_type');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['error_warning'] = @$this->error['warning'];
		$this->data['error_merchant_id'] = @$this->error['merchant_id'];
		$this->data['error_merchant_signature'] = @$this->error['merchant_signature'];
		$this->data['error_liqpay_type'] = @$this->error['liqpay_type'];

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
       		'href'      => $this->url->https('payment/liqpay'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->https('payment/liqpay');
		
		$this->data['cancel'] = $this->url->https('extension/payment');
		
		if (isset($this->request->post['liqpay_status'])) {
			$this->data['liqpay_status'] = $this->request->post['liqpay_status'];
		} else {
			$this->data['liqpay_status'] = $this->config->get('liqpay_status');
		}
		
		if (isset($this->request->post['liqpay_geo_zone_id'])) {
			$this->data['liqpay_geo_zone_id'] = $this->request->post['liqpay_geo_zone_id'];
		} else {
			$this->data['liqpay_geo_zone_id'] = $this->config->get('liqpay_geo_zone_id'); 
		} 

		if (isset($this->request->post['liqpay_order_status_id'])) {
			$this->data['liqpay_order_status_id'] = $this->request->post['liqpay_order_status_id'];
		} else {
			$this->data['liqpay_order_status_id'] = $this->config->get('liqpay_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['liqpay_merchant_id'])) {
			$this->data['liqpay_merchant_id'] = $this->request->post['liqpay_merchant_id'];
		} else {
			$this->data['liqpay_merchant_id'] = $this->config->get('liqpay_merchant_id');
		}

		if (isset($this->request->post['liqpay_merchant_signature'])) {
			$this->data['liqpay_merchant_signature'] = $this->request->post['liqpay_merchant_signature'];
		} else {
			$this->data['liqpay_merchant_signature'] = $this->config->get('liqpay_merchant_signature');
		}

		if (isset($this->request->post['liqpay_liqpay_type'])) {
			$this->data['liqpay_liqpay_type'] = $this->request->post['liqpay_liqpay_type'];
		} else {
			$this->data['liqpay_liqpay_type'] = $this->config->get('liqpay_liqpay_type');
		}
		
		if (isset($this->request->post['liqpay_sort_order'])) {
			$this->data['liqpay_sort_order'] = $this->request->post['liqpay_sort_order'];
		} else {
			$this->data['liqpay_sort_order'] = $this->config->get('liqpay_sort_order');
		}
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->id       = 'content';
		$this->template = 'payment/liqpay.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/liqpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!ereg("^[i]{1}[0-9]{10}$", @$this->request->post['liqpay_merchant_id'])) {
			$this->error['merchant_id'] = $this->language->get('error_merchant_id');
		}

		if (!$this->request->post['liqpay_merchant_signature']) {
			$this->error['merchant_signature'] = $this->language->get('error_merchant_signature');
		}

		if (($this->request->post['liqpay_liqpay_type'] != 'liqpay') && $this->request->post['liqpay_liqpay_type'] != 'card') {
			$this->error['liqpay_type'] = $this->language->get('error_liqpay_type');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>