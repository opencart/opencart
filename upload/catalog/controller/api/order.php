<?php
class ControllerApiOrder extends Controller {
	public function setShippingAddress() {
		$this->load->language('api/order');
		
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
			
			$this->session->data['shipping_address'] = array(
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
		}
		
		$this->response->setOutput(json_encode($json));	
	}
	
	public function setPaymentAddress() {
		$this->load->language('api/order');
		
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
		}
		
		$this->response->setOutput(json_encode($json));		
	}
		
	public function addOrder() {
		$this->load->language('api/order');
		
		$json = array();
				
		// Cart
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['error']['warning'] = $this->url->link('checkout/cart');
		}
						
		// Customer
		if ($this->request->post['customer_id']) {
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

			if (!$customer_info) {
				$json['error']['warning'] = $this->language->get('error_customer');
			}
		}
		
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$json['error']['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$json['error']['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email']))) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$json['error']['telephone'] = $this->language->get('error_telephone');
		}		

		// Payment Address
		if (!isset($this->session->data['payment_address'])) {
			$json['error']['warning'] = $this->language->get('error_payment_address');
		}	
			
		// Payment Method
		if (!isset($this->request->post['payment_method'])) {
			$json['error']['warning'] = $this->language->get('error_payment_method');
		} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
			$json['error']['warning'] = $this->language->get('error_payment_method');
		}

		// Shipping
		if ($this->cart->hasShipping()) {
			// Shipping Address
			if (!isset($this->session->data['shipping_address'])) {
				$json['error']['warning'] = $this->language->get('error_shipping_address');
			}
		
			// Shipping Method
			if (!isset($this->request->post['shipping_method'])) {
				$json['error']['warning'] = $this->language->get('error_shipping_method');
			} else {
				$shipping = explode('.', $this->request->post['shipping_method']);
	
				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
					$json['error']['warning'] = $this->language->get('error_shipping_method');
				}
			}
		}
		
		
		
		
		if (!$json) {
			// Customer Group
			// $this->config->set('config_customer_group_id', );
		
			// Tax
			if ($this->cart->hasShipping()) {
				$this->tax->setShippingAddress($this->request->post['shipping_country_id'], $this->request->post['shipping_zone_id']);
			} else {
				$this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
			}
			
			$this->tax->setPaymentAddress($this->request->post['payment_country_id'], $this->request->post['payment_zone_id']);				
			$this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));	
			
			
			$order_data = array();
			
			$order_data['customer_id'] = $this->request->post['customer_id'];
			$order_data['customer_group_id'] = $this->request->post['customer_group_id'];
			$order_data['firstname'] = $this->session->data['guest']['firstname'];
			$order_data['lastname'] = $this->session->data['guest']['lastname'];
			$order_data['email'] = $this->session->data['guest']['email'];
			$order_data['telephone'] = $this->session->data['guest']['telephone'];
			$order_data['fax'] = $this->session->data['guest']['fax'];
			$order_data['custom_field'] = $this->session->data['guest']['custom_field'];
		
		
		
		
		}



	
		$this->response->setOutput(json_encode($json));	
	}		
	
	public function calculate() {
		
	}
	
	public function editOrder() {
		// Add the coupon, vouchers and reward points back
		if (isset($this->request->get['order_id'])) {
			$this->load->model('account/order');
			
			$order_info = $this->model_account_order->getOrder($this->request->get['order_id']);
			
			if ($order_info) {
				$order_totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);
				
				foreach ($order_totals as $order_total) {
					$this->load->model('total/' . $order_total['code']);
					
					if (method_exists($this->{'model_total_' . $order_total['code']}, 'confirm')) {
						$this->{'model_total_' . $order_total['code']}->confirm($order_info, $order_total);
					}
				}
			}
		}
					
	}
	
	public function deleteOrder() {
		
	}
	
	public function getOrder() {
		
	}
	

	
	public function confirm() {
		
	}
}