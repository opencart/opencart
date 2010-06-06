<?php 
class ControllerPaymentPayPoint extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/paypoint');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('paypoint', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_live'] = $this->language->get('text_live');
		$this->data['text_successful'] = $this->language->get('text_successful');
		$this->data['text_fail'] = $this->language->get('text_fail');
				
		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_test'] = $this->language->get('entry_test');
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
		
		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
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
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/paypoint&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/paypoint&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];
		
		if (isset($this->request->post['paypoint_merchant'])) {
			$this->data['paypoint_merchant'] = $this->request->post['paypoint_merchant'];
		} else {
			$this->data['paypoint_merchant'] = $this->config->get('paypoint_merchant');
		}
		
		if (isset($this->request->post['paypoint_test'])) {
			$this->data['paypoint_test'] = $this->request->post['paypoint_test'];
		} else {
			$this->data['paypoint_test'] = $this->config->get('paypoint_test');
		}
		
		if (isset($this->request->post['paypoint_order_status_id'])) {
			$this->data['paypoint_order_status_id'] = $this->request->post['paypoint_order_status_id'];
		} else {
			$this->data['paypoint_order_status_id'] = $this->config->get('paypoint_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['paypoint_geo_zone_id'])) {
			$this->data['paypoint_geo_zone_id'] = $this->request->post['paypoint_geo_zone_id'];
		} else {
			$this->data['paypoint_geo_zone_id'] = $this->config->get('paypoint_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['paypoint_status'])) {
			$this->data['paypoint_status'] = $this->request->post['paypoint_status'];
		} else {
			$this->data['paypoint_status'] = $this->config->get('paypoint_status');
		}
		
		if (isset($this->request->post['paypoint_sort_order'])) {
			$this->data['paypoint_sort_order'] = $this->request->post['paypoint_sort_order'];
		} else {
			$this->data['paypoint_sort_order'] = $this->config->get('paypoint_sort_order');
		}
		
		$this->template = 'payment/paypoint.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paypoint')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['paypoint_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
				
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>