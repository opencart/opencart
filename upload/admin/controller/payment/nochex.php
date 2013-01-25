<?php 
class ControllerPaymentNOCHEX extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('payment/nochex');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('nochex', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_seller'] = $this->language->get('text_seller');
		$this->data['text_merchant'] = $this->language->get('text_merchant');
		
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_account'] = $this->language->get('entry_account');
		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_template'] = $this->language->get('entry_template');
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
		
 		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
 		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
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
			'href'      => $this->url->link('payment/nochex', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/nochex', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['nochex_email'])) {
			$this->data['nochex_email'] = $this->request->post['nochex_email'];
		} else {
			$this->data['nochex_email'] = $this->config->get('nochex_email');
		}

		if (isset($this->request->post['nochex_account'])) {
			$this->data['nochex_account'] = $this->request->post['nochex_account'];
		} else {
			$this->data['nochex_account'] = $this->config->get('nochex_account');
		}

		if (isset($this->request->post['nochex_merchant'])) {
			$this->data['nochex_merchant'] = $this->request->post['nochex_merchant'];
		} else {
			$this->data['nochex_merchant'] = $this->config->get('nochex_merchant');
		}

		if (isset($this->request->post['nochex_template'])) {
			$this->data['nochex_template'] = $this->request->post['nochex_template'];
		} else {
			$this->data['nochex_template'] = $this->config->get('nochex_template');
		}
		
		if (isset($this->request->post['nochex_test'])) {
			$this->data['nochex_test'] = $this->request->post['nochex_test'];
		} else {
			$this->data['nochex_test'] = $this->config->get('nochex_test');
		}
		
		if (isset($this->request->post['nochex_total'])) {
			$this->data['nochex_total'] = $this->request->post['nochex_total'];
		} else {
			$this->data['nochex_total'] = $this->config->get('nochex_total'); 
		} 
				
		if (isset($this->request->post['nochex_order_status_id'])) {
			$this->data['nochex_order_status_id'] = $this->request->post['nochex_order_status_id'];
		} else {
			$this->data['nochex_order_status_id'] = $this->config->get('nochex_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['nochex_geo_zone_id'])) {
			$this->data['nochex_geo_zone_id'] = $this->request->post['nochex_geo_zone_id'];
		} else {
			$this->data['nochex_geo_zone_id'] = $this->config->get('nochex_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['nochex_status'])) {
			$this->data['nochex_status'] = $this->request->post['nochex_status'];
		} else {
			$this->data['nochex_status'] = $this->config->get('nochex_status');
		}
		
		if (isset($this->request->post['nochex_sort_order'])) {
			$this->data['nochex_sort_order'] = $this->request->post['nochex_sort_order'];
		} else {
			$this->data['nochex_sort_order'] = $this->config->get('nochex_sort_order');
		}

		$this->template = 'payment/nochex.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());		
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/nochex')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['nochex_email']) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if (!$this->request->post['nochex_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>