<?php
class ControllerApiPayment extends Controller {
	public function address() {
		$this->load->language('api/payment');
		
		$json = array();
		
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$json['error']['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$json['error']['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
			$json['error']['address_1'] = $this->language->get('error_address_1');
		}

		if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 32)) {
			$json['error']['city'] = $this->language->get('error_city');
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
			$json['error']['postcode'] = $this->language->get('error_postcode');
		}

		if ($this->request->post['country_id'] == '') {
			$json['error']['country'] = $this->language->get('error_country');
		}

		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
			$json['error']['zone'] = $this->language->get('error_zone');
		}

		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields(array('filter_customer_group_id' => $this->config->get('config_customer_group_id')));

		foreach ($custom_fields as $custom_field) {
			if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
				$json['error']['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
			}
		}
		
		if (!$json) {
			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
	
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
	
			if ($zone_query->num_rows) {
				$zone = $zone_info['name'];
				$zone_code = $zone_info['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}
			
			$this->session->data['payment_address'] = array(
				'address_id'     => $this->request->post['address_id'],
				'firstname'      => $this->request->post['firstname'],
				'lastname'       => $this->request->post['lastname'],
				'company'        => $this->request->post['company'],
				'address_1'      => $this->request->post['address_1'],
				'address_2'      => $this->request->post['address_2'],
				'postcode'       => $this->request->post['postcode'],
				'city'           => $this->request->post['city'],
				'zone_id'        => $this->request->post['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $this->request->post['country_id'],
				'country'        => $country,
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format,
				'custom_field'   => unserialize($this->request->post['custom_field'])
			);
			
			$json['success'] = $this->language->get('text_success');
			
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}
		
		$this->response->setOutput(json_encode($json));		
	}
	
	public function method() {
		$this->load->language('api/payment');
		
		$json = array();
				
		// Payment Address
		if (!isset($this->session->data['payment_address'])) {
			$json['error']['warning'] = $this->language->get('error_payment_address');
		}
		
		// Payment Method
		if (empty($this->session->data['payment_methods'])) {
			$json['error']['warning'] = $this->language->get('error_payment_methods');	
		} elseif (!isset($this->request->post['payment_method'])) {
			$json['error']['warning'] = $this->language->get('error_payment_method');
		} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
			$json['error']['warning'] = $this->language->get('error_payment_method');
		}
				
		if (!$json) {
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
		
			$json['success'] = $this->language->get('text_success');
		}
	}		
	
	public function methods() {
		$this->load->language('api/payment');
		
		$json = array();
		
		// Payment Address
		if (!isset($this->session->data['payment_address'])) {
			$json['error']['payment_address'] = $this->language->get('error_payment_address');
		}		
		
		if (!$json) {
			// Totals
			$total_data = array();					
			$total = 0;
			$taxes = $this->cart->getTaxes();
			
			$this->load->model('setting/extension');
			
			$sort_order = array(); 
			
			$results = $this->model_setting_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);
		
					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
				}
			}
			
			$json['payment_method'] = array();
			
			$results = $this->model_setting_extension->getExtensions('payment');

			$recurring = $this->cart->hasRecurringProducts();
	
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('payment/' . $result['code']);
					
					$method = $this->{'model_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total); 
					
					if ($method) {
						if ($recurring > 0) {
							if (method_exists($this->{'model_payment_' . $result['code']}, 'recurringPayments')) {
								if ($this->{'model_payment_' . $result['code']}->recurringPayments() == true) {
									$json['payment_method'][$result['code']] = $method;
								}
							}
						} else {
							$json['payment_method'][$result['code']] = $method;
						}
					}
				}
			}

			$sort_order = array(); 
		  
			foreach ($json['payment_method'] as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $json['payment_method']);			
			
			if ($json['payment_method']) {
				$this->session->data['payment_methods'] = $json['payment_method'];
			} else {
				$json['error']['payment_method'] = $this->language->get('error_no_payment');
			}			
		}
		
		$this->response->setOutput(json_encode($json));		
	}
}