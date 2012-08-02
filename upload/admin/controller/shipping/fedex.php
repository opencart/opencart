<?php
class ControllerShippingFedex extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('shipping/fedex');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('fedex', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_key'] = $this->language->get('entry_key');
		$this->data['entry_password'] = $this->language->get('entry_password');
		$this->data['entry_account'] = $this->language->get('entry_account');
		$this->data['entry_meter'] = $this->language->get('entry_meter');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_service'] = $this->language->get('entry_service');
		$this->data['entry_delivery_type'] = $this->language->get('entry_delivery_type');
		$this->data['entry_packaging_type'] = $this->language->get('entry_packaging_type');
		$this->data['entry_rate_type'] = $this->language->get('entry_rate_type');
		$this->data['entry_destination_type'] = $this->language->get('entry_destination_type');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
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
		
 		if (isset($this->error['key'])) {
			$this->data['error_key'] = $this->error['key'];
		} else {
			$this->data['error_key'] = '';
		}
		
 		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
		}
				
 		if (isset($this->error['account'])) {
			$this->data['error_account'] = $this->error['account'];
		} else {
			$this->data['error_account'] = '';
		}
 		
		if (isset($this->error['meter'])) {
			$this->data['error_meter'] = $this->error['meter'];
		} else {
			$this->data['error_meter'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/fedex', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/fedex', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['fedex_key'])) {
			$this->data['fedex_key'] = $this->request->post['fedex_key'];
		} else {
			$this->data['fedex_key'] = $this->config->get('fedex_key');
		}
		
		if (isset($this->request->post['fedex_password'])) {
			$this->data['fedex_password'] = $this->request->post['fedex_password'];
		} else {
			$this->data['fedex_password'] = $this->config->get('fedex_password');
		}
					
		if (isset($this->request->post['fedex_account'])) {
			$this->data['fedex_account'] = $this->request->post['fedex_account'];
		} else {
			$this->data['fedex_account'] = $this->config->get('fedex_account');
		}

		if (isset($this->request->post['fedex_meter'])) {
			$this->data['fedex_meter'] = $this->request->post['fedex_meter'];
		} else {
			$this->data['fedex_meter'] = $this->config->get('fedex_meter');
		}

		if (isset($this->request->post['fedex_test'])) {
			$this->data['fedex_test'] = $this->request->post['fedex_test'];
		} else {
			$this->data['fedex_test'] = $this->config->get('fedex_test');
		}
		
		if (isset($this->request->post['fedex_priority_overnight'])) {
			$this->data['fedex_priority_overnight'] = $this->request->post['fedex_priority_overnight'];
		} else {
			$this->data['fedex_priority_overnight'] = $this->config->get('fedex_priority_overnight');
		}
		
		if (isset($this->request->post['fedex_standard_overnight'])) {
			$this->data['fedex_standard_overnight'] = $this->request->post['fedex_standard_overnight'];
		} else {
			$this->data['fedex_standard_overnight'] = $this->config->get('fedex_standard_overnight');
		}
		
		if (isset($this->request->post['fedex_first_overnight'])) {
			$this->data['fedex_first_overnight'] = $this->request->post['fedex_first_overnight'];
		} else {
			$this->data['fedex_first_overnight'] = $this->config->get('fedex_first_overnight');
		}	
			
		if (isset($this->request->post['fedex_2_day'])) {
			$this->data['fedex_2_day'] = $this->request->post['fedex_2_day'];
		} else {
			$this->data['fedex_2_day'] = $this->config->get('fedex_2_day');
		}	
		
		if (isset($this->request->post['fedex_express_saver'])) {
			$this->data['fedex_express_saver'] = $this->request->post['fedex_express_saver'];
		} else {
			$this->data['fedex_express_saver'] = $this->config->get('fedex_express_saver');
		}	
		
		if (isset($this->request->post['fedex_international_priority'])) {
			$this->data['fedex_international_priority'] = $this->request->post['fedex_international_priority'];
		} else {
			$this->data['fedex_international_priority'] = $this->config->get('fedex_international_priority');
		}
		
		if (isset($this->request->post['fedex_international_economy'])) {
			$this->data['fedex_international_economy'] = $this->request->post['fedex_international_economy'];
		} else {
			$this->data['fedex_international_economy'] = $this->config->get('fedex_international_economy');
		}
		
		if (isset($this->request->post['fedex_international_first'])) {
			$this->data['fedex_international_first'] = $this->request->post['fedex_international_first'];
		} else {
			$this->data['fedex_international_first'] = $this->config->get('fedex_international_first');
		}
		
		if (isset($this->request->post['fedex_1_day_freight'])) {
			$this->data['fedex_1_day_freight'] = $this->request->post['fedex_1_day_freight'];
		} else {
			$this->data['fedex_1_day_freight'] = $this->config->get('fedex_1_day_freight');
		}
		
		if (isset($this->request->post['fedex_2_day_freight'])) {
			$this->data['fedex_2_day_freight'] = $this->request->post['fedex_2_day_freight'];
		} else {
			$this->data['fedex_2_day_freight'] = $this->config->get('fedex_2_day_freight');
		}
		
		if (isset($this->request->post['fedex_3_day_freight'])) {
			$this->data['fedex_3_day_freight'] = $this->request->post['fedex_3_day_freight'];
		} else {
			$this->data['fedex_3_day_freight'] = $this->config->get('fedex_3_day_freight');
		}
		
		if (isset($this->request->post['fedex_ground'])) {
			$this->data['fedex_ground'] = $this->request->post['fedex_ground'];
		} else {

			$this->data['fedex_ground'] = $this->config->get('fedex_ground');
		}
		
		if (isset($this->request->post['fedex_ground_home'])) {
			$this->data['fedex_ground_home'] = $this->request->post['fedex_ground_home'];
		} else {
			$this->data['fedex_ground_home'] = $this->config->get('fedex_ground_home');
		}
		
		if (isset($this->request->post['fedex_international_priority_freight'])) {
			$this->data['fedex_international_priority_freight'] = $this->request->post['fedex_international_priority_freight'];
		} else {
			$this->data['fedex_international_priority_freight'] = $this->config->get('fedex_international_priority_freight');
		}
		
		if (isset($this->request->post['fedex_international_economy_freight'])) {
			$this->data['fedex_international_economy_freight'] = $this->request->post['fedex_international_economy_freight'];
		} else {
			$this->data['fedex_international_economy_freight'] = $this->config->get('fedex_international_economy_freight');
		}
		
		if (isset($this->request->post['fedex_europe_first_international_priority'])) {
			$this->data['fedex_europe_first_international_priority'] = $this->request->post['fedex_europe_first_international_priority'];
		} else {
			$this->data['fedex_europe_first_international_priority'] = $this->config->get('fedex_europe_first_international_priority');
		}
																														
		if (isset($this->request->post['fedex_tax_class_id'])) {
			$this->data['fedex_tax_class_id'] = $this->request->post['fedex_tax_class_id'];
		} else {
			$this->data['fedex_tax_class_id'] = $this->config->get('fedex_tax_class_id');
		}
		
		if (isset($this->request->post['fedex_geo_zone_id'])) {
			$this->data['fedex_geo_zone_id'] = $this->request->post['fedex_geo_zone_id'];
		} else {
			$this->data['fedex_geo_zone_id'] = $this->config->get('fedex_geo_zone_id');
		}
		
		if (isset($this->request->post['fedex_status'])) {
			$this->data['fedex_status'] = $this->request->post['fedex_status'];
		} else {
			$this->data['fedex_status'] = $this->config->get('fedex_status');
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
								
		$this->template = 'shipping/fedex.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
 		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/fedex')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['fedex_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}
		
		if (!$this->request->post['fedex_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}
						
		if (!$this->request->post['fedex_account']) {
			$this->error['account'] = $this->language->get('error_account');
		}
		
		if (!$this->request->post['fedex_meter']) {
			$this->error['meter'] = $this->language->get('error_meter');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>