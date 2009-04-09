<?php
class ControllerShippingZone extends Controller { 
	private $error = array();
	
	public function index() {  
		$this->load->language('shipping/zone');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				 
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('zone', $this->request->post);	

			$this->session->data['success'] = $this->language->get('text_success');
									
			$this->redirect($this->url->https('extension/shipping'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_cost'] = $this->language->get('entry_cost');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['help_cost'] = $this->language->get('help_cost');
		
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
       		'href'      => $this->url->https('shipping/zone'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->https('shipping/zone');
		
		$this->data['cancel'] = $this->url->https('extension/shipping');

		$this->load->model('localisation/geo_zone');
		
		if (isset($this->request->post['zone_status'])) {
			$this->data['zone_status'] = $this->request->post['zone_status'];
		} else {
			$this->data['zone_status'] = $this->config->get('zone_status');
		}
		
		$geo_zones = $this->model_localisation_geo_zone->getGeoZones();
		
		foreach ($geo_zones as $geo_zone) {
			if (isset($this->request->post['zone_' . $geo_zone['geo_zone_id'] . '_cost'])) {
				$this->data['zone_' . $geo_zone['geo_zone_id'] . '_cost'] = $this->request->post['zone_' . $geo_zone['geo_zone_id'] . '_cost'];
			} else {
				$this->data['zone_' . $geo_zone['geo_zone_id'] . '_cost'] = $this->config->get('zone_' . $geo_zone['geo_zone_id'] . '_cost');
			}		
			
			if (isset($this->request->post['zone_' . $geo_zone['geo_zone_id'] . '_status'])) {
				$this->data['zone_' . $geo_zone['geo_zone_id'] . '_status'] = $this->request->post['zone_' . $geo_zone['geo_zone_id'] . '_status'];
			} else {
				$this->data['zone_' . $geo_zone['geo_zone_id'] . '_status'] = $this->config->get('zone_' . $geo_zone['geo_zone_id'] . '_status');
			}		
		}
		
		$this->data['geo_zones'] = $geo_zones;

		if (isset($this->request->post['zone_tax_class_id'])) {
			$this->data['zone_tax_class_id'] = $this->request->post['zone_tax_class_id'];
		} else {
			$this->data['zone_tax_class_id'] = $this->config->get('zone_tax_class_id');
		}
		
		if (isset($this->request->post['zone_sort_order'])) {
			$this->data['zone_sort_order'] = $this->request->post['zone_sort_order'];
		} else {
			$this->data['zone_sort_order'] = $this->config->get('zone_sort_order');
		}	
		
		$this->load->model('localisation/tax_class');
				
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
								
		$this->id       = 'content';
		$this->template = 'shipping/zone.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();
	}
		
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/zone')) {
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