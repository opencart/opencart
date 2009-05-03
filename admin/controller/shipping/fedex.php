<?php
class ControllerShippingFlat extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('shipping/fedex');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('fedex', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->https('extension/shipping'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_cost'] = $this->language->get('entry_cost');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['error_warning'] = @$this->error['warning'];

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('extension/shipping'),
       		'text'      => $this->language->get('text_shipping'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('shipping/fedex'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->https('shipping/fedex');
		
		$this->data['cancel'] = $this->url->https('extension/shipping');
	
		if (isset($this->request->post['fedex_status'])) {
			$this->data['fedex_status'] = $this->request->post['fedex_status'];
		} else {
			$this->data['fedex_status'] = $this->config->get('fedex_status');
		}

		if (isset($this->request->post['fedex_geo_zone_id'])) {
			$this->data['fedex_geo_zone_id'] = $this->request->post['fedex_geo_zone_id'];
		} else {
			$this->data['fedex_geo_zone_id'] = $this->config->get('fedex_geo_zone_id');
		}
		
		if (isset($this->request->post['fedex_cost'])) {
			$this->data['fedex_cost'] = $this->request->post['fedex_cost'];
		} else {
			$this->data['fedex_cost'] = $this->config->get('fedex_cost');
		}

		if (isset($this->request->post['fedex_tax_class_id'])) {
			$this->data['fedex_tax_class_id'] = $this->request->post['fedex_tax_class_id'];
		} else {
			$this->data['fedex_tax_class_id'] = $this->config->get('fedex_tax_class_id');
		}
		
		if (isset($this->request->post['fedex_sort_order'])) {
			$this->data['fedex_sort_order'] = $this->request->post['fedex_sort_order'];
		} else {
			$this->data['fedex_sort_order'] = $this->config->get('fedex_sort_order');
		}				

		$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/geo_zone');
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
								
		$this->id       = 'content';
		$this->template = 'shipping/fedex.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/fedex')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>