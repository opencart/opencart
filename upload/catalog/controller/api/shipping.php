<?php
class ControllerApiShipping extends Controller {
	public function index() {
			// Shipping
			$json['shipping_method'] = array();
			
			if ($this->cart->hasShipping()) {
				// Shipping address
				if ((utf8_strlen($this->request->post['shipping_firstname']) < 1) || (utf8_strlen($this->request->post['shipping_firstname']) > 32)) {
					$json['error']['shipping']['firstname'] = $this->language->get('error_firstname');
				}
		
				if ((utf8_strlen($this->request->post['shipping_lastname']) < 1) || (utf8_strlen($this->request->post['shipping_lastname']) > 32)) {
					$json['error']['shipping']['lastname'] = $this->language->get('error_lastname');
				}
				
				if ((utf8_strlen($this->request->post['shipping_address_1']) < 3) || (utf8_strlen($this->request->post['shipping_address_1']) > 128)) {
					$json['error']['shipping']['address_1'] = $this->language->get('error_address_1');
				}
		
				if ((utf8_strlen($this->request->post['shipping_city']) < 3) || (utf8_strlen($this->request->post['shipping_city']) > 128)) {
					$json['error']['shipping']['city'] = $this->language->get('error_city');
				}
				
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);
				
				if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['shipping_postcode']) < 2 || utf8_strlen($this->request->post['shipping_postcode']) > 10)) {
					$json['error']['shipping']['postcode'] = $this->language->get('error_postcode');
				}
		
				if ($this->request->post['shipping_country_id'] == '') {
					$json['error']['shipping']['country'] = $this->language->get('error_country');
				}
				
				if (!isset($this->request->post['shipping_zone_id']) || $this->request->post['shipping_zone_id'] == '') {
					$json['error']['shipping']['zone'] = $this->language->get('error_zone');
				}
							
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);
				
				if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['shipping_postcode']) < 2 || utf8_strlen($this->request->post['shipping_postcode']) > 10)) {
					$json['error']['shipping']['postcode'] = $this->language->get('error_postcode');
				}

				if (!isset($json['error']['shipping'])) {
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
				
					$zone_info = $this->model_localisation_zone->getZone($this->request->post['shipping_zone_id']);
					
					if ($zone_info) {
						$zone = $zone_info['name'];
						$zone_code = $zone_info['code'];
					} else {
						$zone = '';
						$zone_code = '';
					}					
	
					$address_data = array(
						'firstname'      => $this->request->post['shipping_firstname'],
						'lastname'       => $this->request->post['shipping_lastname'],
						'company'        => $this->request->post['shipping_company'],
						'address_1'      => $this->request->post['shipping_address_1'],
						'address_2'      => $this->request->post['shipping_address_2'],
						'postcode'       => $this->request->post['shipping_postcode'],
						'city'           => $this->request->post['shipping_city'],
						'zone_id'        => $this->request->post['shipping_zone_id'],
						'zone'           => $zone,
						'zone_code'      => $zone_code,
						'country_id'     => $this->request->post['shipping_country_id'],
						'country'        => $country,	
						'iso_code_2'     => $iso_code_2,
						'iso_code_3'     => $iso_code_3,
						'address_format' => $address_format
					);
					
					// Shipping method
					$results = $this->model_setting_extension->getExtensions('shipping');
					
					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('shipping/' . $result['code']);
							
							$quote = $this->{'model_shipping_' . $result['code']}->getQuote($address_data); 
				
							if ($quote) {
								$json['shipping_method'][$result['code']] = array( 
									'title'      => $quote['title'],
									'quote'      => $quote['quote'], 
									'sort_order' => $quote['sort_order'],
									'error'      => $quote['error']
								);
							}
						}
					}
			
					$sort_order = array();
				  
					foreach ($json['shipping_method'] as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}
			
					array_multisort($sort_order, SORT_ASC, $json['shipping_method']);

					if (!$json['shipping_method']) {
						$json['error']['shipping_method'] = $this->language->get('error_no_shipping');
					} elseif ($this->request->post['shipping_code']) {
						$shipping = explode('.', $this->request->post['shipping_code']);
						
						if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($json['shipping_method'][$shipping[0]]['quote'][$shipping[1]])) {		
							$json['error']['shipping_method'] = $this->language->get('error_shipping');
						} else {
							$this->session->data['shipping_method'] = $json['shipping_method'][$shipping[0]]['quote'][$shipping[1]];
						}				
					}					
				}
			}
	
	}
}