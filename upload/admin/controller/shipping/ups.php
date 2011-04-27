<?php
class ControllerShippingUPS extends Controller {
	private $error = array(); 
	
	public function index() {
	
		$this->data = array_merge($this->data, $this->load->language('shipping/ups'));

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('ups', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$errors = array(
			'warning',
			'key',
			'username',
			'password',
			'city',
			'state',
			'country'
		);
		
		foreach ($errors as $error) {
			if (isset($this->error[$error])) {
				$this->data['error_' . $error] = $this->error[$error];
			} else {
				$this->data['error_' . $error] = '';
			}
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
			'href'      => $this->url->link('shipping/ups', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('shipping/ups', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['pickups'] = array();
		  
		$this->data['pickups'][] = array(
			'value' => '01',
			'text'  => $this->language->get('text_daily_pickup')
		);

		$this->data['pickups'][] = array(
			'value' => '03',
			'text'  => $this->language->get('text_customer_counter')
		);

		$this->data['pickups'][] = array(
			'value' => '06',
			'text'  => $this->language->get('text_one_time_pickup')
		);

		$this->data['pickups'][] = array(
			'value' => '07',
			'text'  => $this->language->get('text_on_call_air_pickup')
		);

		$this->data['pickups'][] = array(
			'value' => '19',
			'text'  => $this->language->get('text_letter_center')
		);		
		
		$this->data['pickups'][] = array(
			'value' => '20',
			'text'  => $this->language->get('text_air_service_center')
		);	
		
		$this->data['pickups'][] = array(
			'value' => '11',
			'text'  => $this->language->get('text_suggested_retail_rates')
		);	
			
		$this->data['packages'] = array();
		  
		$this->data['packages'][] = array(
			'value' => '02',
			'text'  => $this->language->get('text_package')
		);

		$this->data['packages'][] = array(
			'value' => '01',
			'text'  => $this->language->get('text_ups_letter')
		);

		$this->data['packages'][] = array(
			'value' => '03',
			'text'  => $this->language->get('text_ups_tube')
		);

		$this->data['packages'][] = array(
			'value' => '04',
			'text'  => $this->language->get('text_ups_pak')
		);

		$this->data['packages'][] = array(
			'value' => '21',
			'text'  => $this->language->get('text_ups_express_box')
		);		
		
		$this->data['packages'][] = array(
			'value' => '24',
			'text'  => $this->language->get('text_ups_25kg_box')
		);	
		
		$this->data['packages'][] = array(
			'value' => '25',
			'text'  => $this->language->get('text_ups_10kg_box')
		);	
				
		$this->data['classifications'][] = array(
			'value' => '01',
			'text'  => '01'
		);		
		
		$this->data['classifications'][] = array(
			'value' => '03',
			'text'  => '03'
		);	
		
		$this->data['classifications'][] = array(
			'value' => '04',
			'text'  => '04'
		);			
				
		$this->data['origins'] = array();
		  
		$this->data['origins'][] = array(
			'value' => 'US',
			'text'  => $this->language->get('text_us')
		);

		$this->data['origins'][] = array(
			'value' => 'CA',
			'text'  => $this->language->get('text_ca')
		);

		$this->data['origins'][] = array(
			'value' => 'EU',
			'text'  => $this->language->get('text_eu')
		);

		$this->data['origins'][] = array(
			'value' => 'PR',
			'text'  => $this->language->get('text_pr')
		);

		$this->data['origins'][] = array(
			'value' => 'MX',
			'text'  => $this->language->get('text_mx')
		);		

		$this->data['origins'][] = array(
			'value' => 'other',
			'text'  => $this->language->get('text_other')
		);	
		
		$fields = array(
			'ups_key',
			'ups_username',
			'ups_password',
			'ups_pickup',
			'ups_packaging',
			'ups_customer',
			'ups_origin',
			'ups_city',
			'ups_state',
			'ups_country',
			'ups_postcode',
			'ups_test',
			'ups_quote_type',
			'ups_us_01',
			'ups_us_02',
			'ups_us_03',
			'ups_us_07',
			'ups_us_08',
			'ups_us_11',
			'ups_us_12',
			'ups_us_13',
			'ups_us_14',
			'ups_us_54',
			'ups_us_59',
			'ups_us_65',
			'ups_pr_01',
			'ups_pr_02',
			'ups_pr_03',
			'ups_pr_07',
			'ups_pr_08',
			'ups_pr_14',
			'ups_pr_54',
			'ups_pr_65',
			'ups_ca_01',
			'ups_ca_02',
			'ups_ca_07',
			'ups_ca_08',
			'ups_ca_11',
			'ups_ca_12',
			'ups_ca_13',
			'ups_ca_14',
			'ups_ca_54',
			'ups_ca_65',
			'ups_mx_07',
			'ups_mx_08',
			'ups_mx_54',
			'ups_mx_65',
			'ups_eu_07',
			'ups_eu_08',
			'ups_eu_11',
			'ups_eu_54',
			'ups_eu_65',
			'ups_eu_82',
			'ups_eu_83',
			'ups_eu_84',
			'ups_eu_85',
			'ups_eu_86',
			'ups_other_07',
			'ups_other_08',
			'ups_other_11',
			'ups_other_54',
			'ups_other_65',
			'ups_display_weight',
			'ups_insurance',
			'ups_measurement_code',
			'ups_measurement_class',
			'ups_length',
			'ups_width',
			'ups_height',
			'ups_weight_code',
			'ups_weight_class',
			'ups_tax_class_id',
			'ups_geo_zone_id',
			'ups_status',
			'ups_sort_order'
		);
		
		foreach ($fields as $field) {
			if (isset($this->request->post[$field])) {
				$this->data[$field] = $this->request->post[$field];
			} else {
				$this->data[$field] = $this->config->get($field);
			}	
		}

		$this->data['quote_types'] = array();
		  
		$this->data['quote_types'][] = array(
			'value' => 'residential',
			'text'  => $this->language->get('text_residential')
		);

		$this->data['quote_types'][] = array(
			'value' => 'commercial',
			'text'  => $this->language->get('text_commercial')
		);
		
		$this->load->model('localisation/length_class');
		
		$this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();
		
		$this->load->model('localisation/weight_class');
		
		$this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();
			
		$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->load->model('localisation/geo_zone');
		
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();				

		$this->template = 'shipping/ups.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
		
 		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/ups')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['ups_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}
		
		if (!$this->request->post['ups_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if (!$this->request->post['ups_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['ups_city']) {
			$this->error['city'] = $this->language->get('error_city');
		}

		if (!$this->request->post['ups_state']) {
			$this->error['state'] = $this->language->get('error_state');
		}

		if (!$this->request->post['ups_country']) {
			$this->error['country'] = $this->language->get('error_country');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>