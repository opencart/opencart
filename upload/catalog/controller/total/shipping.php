<?php 
class ControllerTotalShipping extends Controller {
	public function index() {
		if ($this->cart->hasShipping() && $this->config->get('shipping_estimator')) {
			$this->language->load('total/shipping');
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_shipping'] = $this->language->get('text_shipping');
			$this->data['text_select'] = $this->language->get('text_select');
			
			$this->data['entry_country'] = $this->language->get('entry_country');
			$this->data['entry_zone'] = $this->language->get('entry_zone');
			$this->data['entry_postcode'] = $this->language->get('entry_postcode');
			
			$this->data['button_quote'] = $this->language->get('button_quote');
			$this->data['button_shipping'] = $this->language->get('button_shipping');
			
			if (isset($this->session->data['country_id'])) {
				$this->data['country_id'] = $this->session->data['country_id'];			  	
			} else {
				$this->data['country_id'] = $this->config->get('config_country_id');
			}
				
			$this->load->model('localisation/country');
			
			$this->data['countries'] = $this->model_localisation_country->getCountries();
						
			if (isset($this->session->data['zone_id'])) {
				$this->data['zone_id'] = $this->session->data['zone_id'];			
			} else {
				$this->data['zone_id'] = '';
			}
			
			if (isset($this->session->data['postcode'])) {
				$this->data['postcode'] = $this->session->data['postcode'];					
			} else {
				$this->data['postcode'] = '';
			}
			
			if (isset($this->session->data['shipping_methods'])) {
				$this->data['shipping_methods'] = $this->session->data['shipping_methods']; 
			} else {
				$this->data['shipping_methods'] = array();
			}
			
			if (isset($this->session->data['shipping_method']['code'])) {
				$this->data['code'] = $this->session->data['shipping_method']['code'];
			} else {
				$this->data['code'] = '';
			}
														
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/shipping.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/total/shipping.tpl';
			} else {
				$this->template = 'default/template/total/shipping.tpl';
			}
						
			$this->render();		
		}
  	}
	
	public function quote() {
		$this->language->load('total/shipping');
		
		$json = array();	
		
		if (!$this->cart->hasProducts()) {
			$json['redirect'] = $this->url->link('checkout/cart');				
		}	
		
		if (isset($this->request->post['country_id']) && isset($this->request->post['zone_id']) && isset($this->request->post['postcode'])) {
			if ($this->request->post['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}
			
			if ($this->request->post['zone_id'] == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
			}	
				
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			
			if ($country_info && $country_info['postcode_required'] && (strlen(utf8_decode($this->request->post['postcode'])) < 2) || (strlen(utf8_decode($this->request->post['postcode'])) > 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
			}
							
			if (!isset($json['error'])) {		
				$this->tax->setZone($this->request->post['country_id'], $this->request->post['zone_id']);
			
				$this->session->data['country_id'] = $this->request->post['country_id'];
				$this->session->data['zone_id'] = $this->request->post['zone_id'];
				$this->session->data['postcode'] = $this->request->post['postcode'];
			
				if ($country_info) {
					$country = $country_info['name'];
					$iso_code_2 = $country_info['iso_code_2'];
					$iso_code_3 = $country_info['iso_code_3'];
					$address_format = $country_info['address_format'];
				} else {
					$country = '';
					$iso_code_2 = '';
					$iso_code_3 = '';	
					$address_format = '';
				}
				
				$this->load->model('localisation/zone');
			
				$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
				
				if ($zone_info) {
					$zone = $zone_info['name'];
					$code = $zone_info['code'];
				} else {
					$zone = '';
					$code = '';
				}	
			 
				$address_data = array(
					'firstname'      => '',
					'lastname'       => '',
					'company'        => '',
					'address_1'      => '',
					'address_2'      => '',
					'postcode'       => $this->request->post['postcode'],
					'city'           => '',
					'zone_id'        => $this->request->post['zone_id'],
					'zone'           => $zone,
					'zone_code'      => $code,
					'country_id'     => $this->request->post['country_id'],
					'country'        => $country,	
					'iso_code_2'     => $iso_code_2,
					'iso_code_3'     => $iso_code_3,
					'address_format' => $address_format
				);
			
				$quote_data = array();
				
				$this->load->model('setting/extension');
				
				$results = $this->model_setting_extension->getExtensions('shipping');
				
				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('shipping/' . $result['code']);
						
						$quote = $this->{'model_shipping_' . $result['code']}->getQuote($address_data); 
			
						if ($quote) {
							$quote_data[$result['code']] = array( 
								'title'      => $quote['title'],
								'quote'      => $quote['quote'], 
								'sort_order' => $quote['sort_order'],
								'error'      => $quote['error']
							);
						}
					}
				}
		
				$sort_order = array();
			  
				foreach ($quote_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
		
				array_multisort($sort_order, SORT_ASC, $quote_data);
				
				$this->session->data['shipping_methods'] = $quote_data;
				
				if (isset($this->session->data['shipping_methods'])) {
					$json['shipping_methods'] = $this->session->data['shipping_methods']; 
				}
				
				if (!isset($this->session->data['shipping_methods']) || !$this->session->data['shipping_methods']) {
					$json['error']['warning'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact'));
				}				
			}	
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));						
	}
	
	public function calculate() {
		$this->language->load('total/shipping');
		
		$json = array();
		
		if (isset($this->request->post['shipping_method']) && $this->request->post['shipping_method']) {
			$shipping = explode('.', $this->request->post['shipping_method']);
					
			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
				$json['error'] = $this->language->get('error_shipping');
			}
		} else {
			$json['error'] = $this->language->get('error_shipping');
		}
		
		if (!isset($json['error'])) {
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
				
			$this->session->data['success'] = $this->language->get('text_success');
				
			$json['redirect'] = $this->url->link('checkout/cart', '', 'SSL');			
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));			
	}
	
  	public function zone() {
		$output = '<option value="">' . $this->language->get('text_select') . '</option>';
		
		$this->load->model('localisation/zone');

    	$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
        
      	foreach ($results as $result) {
        	$output .= '<option value="' . $result['zone_id'] . '"';
	
	    	if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
	      		$output .= ' selected="selected"';
	    	}
	
	    	$output .= '>' . $result['name'] . '</option>';
    	} 
		
		if (!$results) {
		  	$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}
	
		$this->response->setOutput($output);
  	}	
}
?>