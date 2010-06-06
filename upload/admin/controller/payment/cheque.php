<?php 
class ControllerPaymentCheque extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/cheque');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('cheque', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		
		$this->data['entry_payable'] = $this->language->get('entry_payable');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['payable'])) {
			$this->data['error_payable'] = $this->error['payable'];
		} else {
			$this->data['error_payable'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/cheque&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/cheque&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];
		
		if (isset($this->request->post['cheque_payable'])) {
			$this->data['cheque_payable'] = $this->request->post['cheque_payable'];
		} else {
			$this->data['cheque_payable'] = $this->config->get('cheque_payable');
		}
		
		if (isset($this->request->post['cheque_order_status_id'])) {
			$this->data['cheque_order_status_id'] = $this->request->post['cheque_order_status_id'];
		} else {
			$this->data['cheque_order_status_id'] = $this->config->get('cheque_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['cheque_geo_zone_id'])) {
			$this->data['cheque_geo_zone_id'] = $this->request->post['cheque_geo_zone_id'];
		} else {
			$this->data['cheque_geo_zone_id'] = $this->config->get('cheque_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['cheque_status'])) {
			$this->data['cheque_status'] = $this->request->post['cheque_status'];
		} else {
			$this->data['cheque_status'] = $this->config->get('cheque_status');
		}
		
		if (isset($this->request->post['cheque_sort_order'])) {
			$this->data['cheque_sort_order'] = $this->request->post['cheque_sort_order'];
		} else {
			$this->data['cheque_sort_order'] = $this->config->get('cheque_sort_order');
		}
		
		$this->template = 'payment/cheque.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/cheque')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['cheque_payable']) {
			$this->error['payable'] = $this->language->get('error_payable');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>