<?php
class ControllerShippingUPS extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('shipping/ups');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('ups', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->https('extension/shipping'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_1dm'] = $this->language->get('text_1dm');
		$this->data['text_1da'] = $this->language->get('text_1da');
		$this->data['text_1dp'] = $this->language->get('text_1dp');
		$this->data['text_2dm'] = $this->language->get('text_2dm');
		$this->data['text_2da'] = $this->language->get('text_2da');
		$this->data['text_3ds'] = $this->language->get('text_3ds');
		$this->data['text_gnd'] = $this->language->get('text_gnd');
		$this->data['text_std'] = $this->language->get('text_std');
		$this->data['text_xpr'] = $this->language->get('text_xpr');
		$this->data['text_xdm'] = $this->language->get('text_xdm');
		$this->data['text_xpd'] = $this->language->get('text_xpd');
		
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_packaging'] = $this->language->get('entry_packaging');
		$this->data['entry_rate'] = $this->language->get('entry_rate');
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_service'] = $this->language->get('entry_service');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
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

		if (isset($this->error['postcode'])) {
			$this->data['error_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_postcode'] = '';
		}
		
		if (isset($this->error['packaging'])) {
			$this->data['error_packaging'] = $this->error['packaging'];
		} else {
			$this->data['error_packaging'] = '';
		}

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
       		'href'      => $this->url->https('shipping/ups'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->https('shipping/ups');
		
		$this->data['cancel'] = $this->url->https('extension/shipping');

		if (isset($this->request->post['ups_postcode'])) {
			$this->data['ups_postcode'] = $this->request->post['ups_postcode'];
		} else {
			$this->data['ups_postcode'] = $this->config->get('ups_postcode');
		}
		
		if (isset($this->request->post['ups_packaging'])) {
			$this->data['ups_packaging'] = $this->request->post['ups_packaging'];
		} else {
			$this->data['ups_packaging'] = $this->config->get('ups_packaging');
		}

		$this->data['rates'] = array();
		  
		$this->data['rates'][] = array(
			'code' => 'RDP',
			'text' => $this->language->get('text_rdp')
		);

		$this->data['rates'][] = array(
			'code' => 'OCA',
			'text' => $this->language->get('text_oca')
		);

		$this->data['rates'][] = array(
			'code' => 'OTP',
			'text' => $this->language->get('text_otp')
		);

		$this->data['rates'][] = array(
			'code' => 'LC',
			'text' => $this->language->get('text_lc')
		);

		$this->data['rates'][] = array(
			'code' => 'CC',
			'text' => $this->language->get('text_cc')
		);
		
		if (isset($this->request->post['ups_rate'])) {
			$this->data['ups_rate'] = $this->request->post['ups_rate'];
		} else {
			$this->data['ups_rate'] = $this->config->get('ups_rate');
		}
		
		$this->data['types'] = array();
		
		$this->data['types'][] = array(
			'code' => 'RES',
			'text' => $this->language->get('text_res')
		);

		$this->data['types'][] = array(
			'code' => 'COM',
			'text' => $this->language->get('text_com')
		);
		
		if (isset($this->request->post['ups_address'])) {
			$this->data['ups_address'] = $this->request->post['ups_address'];
		} else {
			$this->data['ups_address'] = $this->config->get('ups_address');
		}		

		if (isset($this->request->post['ups_1dm'])) {
			$this->data['ups_1dm'] = $this->request->post['ups_1dm'];
		} else {
			$this->data['ups_1dm'] = $this->config->get('ups_1dm');
		}		

		if (isset($this->request->post['ups_1da'])) {
			$this->data['ups_1da'] = $this->request->post['ups_1da'];
		} else {
			$this->data['ups_1da'] = $this->config->get('ups_1da');
		}		

		if (isset($this->request->post['ups_1dp'])) {
			$this->data['ups_1dp'] = $this->request->post['ups_1dp'];
		} else {
			$this->data['ups_1dp'] = $this->config->get('ups_1dp');
		}	

		if (isset($this->request->post['ups_2dm'])) {
			$this->data['ups_2dm'] = $this->request->post['ups_2dm'];
		} else {
			$this->data['ups_2dm'] = $this->config->get('ups_2dm');
		}	
		
		if (isset($this->request->post['ups_2da'])) {
			$this->data['ups_2da'] = $this->request->post['ups_2da'];
		} else {
			$this->data['ups_2da'] = $this->config->get('ups_2da');
		}	
		
		if (isset($this->request->post['ups_3ds'])) {
			$this->data['ups_3ds'] = $this->request->post['ups_3ds'];
		} else {
			$this->data['ups_3ds'] = $this->config->get('ups_3ds');
		}	

		if (isset($this->request->post['ups_gnd'])) {
			$this->data['ups_gnd'] = $this->request->post['ups_gnd'];
		} else {
			$this->data['ups_gnd'] = $this->config->get('ups_gnd');
		}

		if (isset($this->request->post['ups_std'])) {
			$this->data['ups_std'] = $this->request->post['ups_std'];
		} else {
			$this->data['ups_std'] = $this->config->get('ups_std');
		}

		if (isset($this->request->post['ups_xpr'])) {
			$this->data['ups_xpr'] = $this->request->post['ups_xpr'];
		} else {
			$this->data['ups_xpr'] = $this->config->get('ups_xpr');
		}

		if (isset($this->request->post['ups_xdm'])) {
			$this->data['ups_xdm'] = $this->request->post['ups_xdm'];
		} else {
			$this->data['ups_xdm'] = $this->config->get('ups_xdm');
		}

		if (isset($this->request->post['ups_xpd'])) {
			$this->data['ups_xpd'] = $this->request->post['ups_xpd'];
		} else {
			$this->data['ups_xpd'] = $this->config->get('ups_xpd');
		}
		
		if (isset($this->request->post['ups_tax_class_id'])) {
			$this->data['ups_tax_class_id'] = $this->request->post['ups_tax_class_id'];
		} else {
			$this->data['ups_tax_class_id'] = $this->config->get('ups_tax_class_id');
		}
		
		$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		if (isset($this->request->post['ups_geo_zone_id'])) {
			$this->data['ups_geo_zone_id'] = $this->request->post['ups_geo_zone_id'];
		} else {
			$this->data['ups_geo_zone_id'] = $this->config->get('ups_geo_zone_id');
		}
		
		$this->load->model('localisation/geo_zone');
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['ups_status'])) {
			$this->data['ups_status'] = $this->request->post['ups_status'];
		} else {
			$this->data['ups_status'] = $this->config->get('ups_status');
		}

		if (isset($this->request->post['ups_sort_order'])) {
			$this->data['ups_sort_order'] = $this->request->post['ups_sort_order'];
		} else {
			$this->data['ups_sort_order'] = $this->config->get('ups_sort_order');
		}				
								
		$this->template = 'shipping/ups.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
 		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/ups')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->post['ups_postcode']) || !$this->request->post['ups_postcode']) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}
		
		if (!isset($this->request->post['ups_packaging']) || !$this->request->post['ups_packaging']) {
			$this->error['packaging'] = $this->language->get('error_packaging');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>