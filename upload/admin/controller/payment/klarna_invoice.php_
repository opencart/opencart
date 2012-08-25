<?php
class ControllerPaymentKlarnaInvoice extends Controller {
    private $error = array();

    public function index() {
		$this->load->language('payment/klarna_invoice');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('klarna_invoice', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_live'] = $this->language->get('text_live');
		$this->data['text_beta'] = $this->language->get('text_beta');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_secret'] = $this->language->get('entry_secret');
		$this->data['entry_server'] = $this->language->get('entry_server');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} elseif (!function_exists('xmlrpc_encode_request')) {
			$this->data['error_warning'] = $this->language->get('error_xmlrpc');
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
		}
		
 		if (isset($this->error['secret'])) {
			$this->data['error_secret'] = $this->error['secret'];
		} else {
			$this->data['error_secret'] = '';
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
			'href'      => $this->url->link('payment/klarna_invoice', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('payment/klarna_invoice', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['klarna_invoice_merchant'])) {
			$this->data['klarna_invoice_merchant'] = $this->request->post['klarna_invoice_merchant'];
		} else {
			$this->data['klarna_invoice_merchant'] = $this->config->get('klarna_invoice_merchant');
		}	
			
		if (isset($this->request->post['klarna_invoice_secret'])) {
			$this->data['klarna_invoice_secret'] = $this->request->post['klarna_invoice_secret'];
		} else {
			$this->data['klarna_invoice_secret'] = $this->config->get('klarna_invoice_secret');
		}
		
		if (isset($this->request->post['klarna_invoice_server'])) {
			$this->data['klarna_invoice_server'] = $this->request->post['klarna_invoice_server'];
		} else {
			$this->data['klarna_invoice_server'] = $this->config->get('klarna_invoice_server');
		}
		
		if (isset($this->request->post['klarna_invoice_order_status_id'])) {
			$this->data['klarna_invoice_order_status_id'] = $this->request->post['klarna_invoice_order_status_id'];
		} else {
			$this->data['klarna_invoice_order_status_id'] = $this->config->get('klarna_invoice_order_status_id');
		}
		
		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['klarna_invoice_geo_zone_id'])) {
			$this->data['klarna_invoice_geo_zone_id'] = $this->request->post['klarna_invoice_geo_zone_id'];
		} else {
			$this->data['klarna_invoice_geo_zone_id'] = $this->config->get('klarna_invoice_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
				
		if (isset($this->request->post['klarna_invoice_status'])) {
			$this->data['klarna_invoice_status'] = $this->request->post['klarna_invoice_status'];
		} else {
			$this->data['klarna_invoice_status'] = $this->config->get('klarna_invoice_status');
		}
				
		if (isset($this->request->post['klarna_invoice_sort_order'])) {
			$this->data['klarna_invoice_sort_order'] = $this->request->post['klarna_invoice_sort_order'];
		} else {
			$this->data['klarna_invoice_sort_order'] = $this->config->get('klarna_invoice_sort_order');
		}
																				
        $this->template = 'payment/klarna_invoice.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
        );
		
        $this->response->setOutput($this->render());
    }
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/klarna_invoice')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['klarna_invoice_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
		
		if (!$this->request->post['klarna_invoice_secret']) {
			$this->error['secret'] = $this->language->get('error_secret');
		}
								
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	public function order() {
		
	}
}