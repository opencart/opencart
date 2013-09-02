<?php 
class ControllerCheckoutGuest extends Controller {
  	public function index() {
    	$this->language->load('checkout/checkout');
		
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_your_details'] = $this->language->get('text_your_details');
		$this->data['text_your_account'] = $this->language->get('text_your_account');
		$this->data['text_your_address'] = $this->language->get('text_your_address');
		$this->data['text_loading'] = $this->language->get('text_loading');
		
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		if (isset($this->session->data['guest']['firstname'])) {
			$this->data['firstname'] = $this->session->data['guest']['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if (isset($this->session->data['guest']['lastname'])) {
			$this->data['lastname'] = $this->session->data['payment_address']['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		
		if (isset($this->session->data['guest']['email'])) {
			$this->data['email'] = $this->session->data['guest']['email'];
		} else {
			$this->data['email'] = '';
		}
		
		if (isset($this->session->data['guest']['telephone'])) {
			$this->data['telephone'] = $this->session->data['guest']['telephone'];		
		} else {
			$this->data['telephone'] = '';
		}

		if (isset($this->session->data['guest']['fax'])) {
			$this->data['fax'] = $this->session->data['guest']['fax'];				
		} else {
			$this->data['fax'] = '';
		}

		if (isset($this->session->data['payment_address']['company'])) {
			$this->data['company'] = $this->session->data['payment_address']['company'];			
		} else {
			$this->data['company'] = '';
		}

		$this->load->model('account/customer_group');

		$this->data['customer_groups'] = array();
		
		if (is_array($this->config->get('config_customer_group_display'))) {
			$customer_groups = $this->model_account_customer_group->getCustomerGroups();
			
			foreach ($customer_groups as $customer_group) {
				if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$this->data['customer_groups'][] = $customer_group;
				}
			}
		}
		
		if (isset($this->session->data['guest']['customer_group_id'])) {
    		$this->data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}
										
		if (isset($this->session->data['payment_address']['address_1'])) {
			$this->data['address_1'] = $this->session->data['payment_address']['address_1'];			
		} else {
			$this->data['address_1'] = '';
		}

		if (isset($this->session->data['payment_address']['address_2'])) {
			$this->data['address_2'] = $this->session->data['payment_address']['address_2'];			
		} else {
			$this->data['address_2'] = '';
		}

		if (isset($this->session->data['payment_address']['postcode'])) {
			$this->data['postcode'] = $this->session->data['payment_address']['postcode'];							
		} elseif (isset($this->session->data['shipping_address']['postcode'])) {
			$this->data['postcode'] = $this->session->data['shipping_address']['postcode'];			
		} else {
			$this->data['postcode'] = '';
		}
		
		if (isset($this->session->data['payment_address']['city'])) {
			$this->data['city'] = $this->session->data['payment_address']['city'];			
		} else {
			$this->data['city'] = '';
		}

		if (isset($this->session->data['payment_address']['country_id'])) {
			$this->data['country_id'] = $this->session->data['payment_address']['country_id'];			  	
		} elseif (isset($this->session->data['shipping_address']['country_id'])) {
			$this->data['country_id'] = $this->session->data['shipping_address']['country_id'];		
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->session->data['payment_address']['zone_id'])) {
			$this->data['zone_id'] = $this->session->data['payment_address']['zone_id'];	
		} elseif (isset($this->session->data['shipping_address']['zone_id'])) {
			$this->data['zone_id'] = $this->session->data['shipping_address']['zone_id'];						
		} else {
			$this->data['zone_id'] = '';
		}
		
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->data['shipping_required'] = $this->cart->hasShipping();
		
		if (isset($this->session->data['guest']['shipping_address'])) {
			$this->data['shipping_address'] = $this->session->data['guest']['shipping_address'];			
		} else {
			$this->data['shipping_address'] = true;
		}			
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/guest.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/guest.tpl';
		} else {
			$this->template = 'default/template/checkout/guest.tpl';
		}
		
		$this->response->setOutput($this->render());		
  	}
	
	public function save() {
    	$this->language->load('checkout/checkout');

		$json = array();
		
		// Validate if customer is logged in.
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		} 			
		
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');		
		}
		
		// Check if guest checkout is avaliable.			
		if (!$this->config->get('config_guest_checkout') || $this->config->get('config_customer_price') || $this->cart->hasDownload()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		} 
					
		if (!$json) {
			if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}
	
			if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}
	
			if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
				$json['error']['email'] = $this->language->get('error_email');
			}
			
			if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
			}
			
			// Customer Group
			$this->load->model('account/customer_group');
 	
			if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $this->request->post['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
			
			$customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);

			if ($customer_group) {  
			
			}
									
			if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}
	
			if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
				$json['error']['city'] = $this->language->get('error_city');
			}
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			
			if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2) || (utf8_strlen($this->request->post['postcode']) > 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
			}
	
			if ($this->request->post['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}
			
			if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
			}	
		}
			
		if (!$json) {
			$this->session->data['account'] = 'guest';
			
			$this->session->data['guest']['customer_group_id'] = $customer_group_id;
			$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
			$this->session->data['guest']['lastname'] = $this->request->post['lastname'];
			$this->session->data['guest']['email'] = $this->request->post['email'];
			$this->session->data['guest']['telephone'] = $this->request->post['telephone'];
			$this->session->data['guest']['fax'] = $this->request->post['fax'];
			
			$this->session->data['payment_address']['firstname'] = $this->request->post['firstname'];
			$this->session->data['payment_address']['lastname'] = $this->request->post['lastname'];				
			$this->session->data['payment_address']['company'] = $this->request->post['company'];
			$this->session->data['payment_address']['address_1'] = $this->request->post['address_1'];
			$this->session->data['payment_address']['address_2'] = $this->request->post['address_2'];
			$this->session->data['payment_address']['postcode'] = $this->request->post['postcode'];
			$this->session->data['payment_address']['city'] = $this->request->post['city'];
			$this->session->data['payment_address']['country_id'] = $this->request->post['country_id'];
			$this->session->data['payment_address']['zone_id'] = $this->request->post['zone_id'];
							
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			
			if ($country_info) {
				$this->session->data['payment_address']['country'] = $country_info['name'];	
				$this->session->data['payment_address']['iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['payment_address']['iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['payment_address']['address_format'] = $country_info['address_format'];
			} else {
				$this->session->data['payment_address']['country'] = '';	
				$this->session->data['payment_address']['iso_code_2'] = '';
				$this->session->data['payment_address']['iso_code_3'] = '';
				$this->session->data['payment_address']['address_format'] = '';
			}
						
			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
			
			if ($zone_info) {
				$this->session->data['payment_address']['zone'] = $zone_info['name'];
				$this->session->data['payment_address']['zone_code'] = $zone_info['code'];
			} else {
				$this->session->data['payment_address']['zone'] = '';
				$this->session->data['payment_address']['zone_code'] = '';
			}
			
			if (!empty($this->request->post['shipping_address'])) {
				$this->session->data['guest']['shipping_address'] = $this->request->post['shipping_address'];
			} else {
				$this->session->data['guest']['shipping_address'] = false;
			}
			
			// Default Payment Address			
			if ($this->session->data['guest']['shipping_address']) {
				$this->session->data['shipping_address']['firstname'] = $this->request->post['firstname'];
				$this->session->data['shipping_address']['lastname'] = $this->request->post['lastname'];
				$this->session->data['shipping_address']['company'] = $this->request->post['company'];
				$this->session->data['shipping_address']['address_1'] = $this->request->post['address_1'];
				$this->session->data['shipping_address']['address_2'] = $this->request->post['address_2'];
				$this->session->data['shipping_address']['postcode'] = $this->request->post['postcode'];
				$this->session->data['shipping_address']['city'] = $this->request->post['city'];
				$this->session->data['shipping_address']['country_id'] = $this->request->post['country_id'];
				$this->session->data['shipping_address']['zone_id'] = $this->request->post['zone_id'];
				
				if ($country_info) {
					$this->session->data['shipping_address']['country'] = $country_info['name'];	
					$this->session->data['shipping_address']['iso_code_2'] = $country_info['iso_code_2'];
					$this->session->data['shipping_address']['iso_code_3'] = $country_info['iso_code_3'];
					$this->session->data['shipping_address']['address_format'] = $country_info['address_format'];
				} else {
					$this->session->data['shipping_address']['country'] = '';	
					$this->session->data['shipping_address']['iso_code_2'] = '';
					$this->session->data['shipping_address']['iso_code_3'] = '';
					$this->session->data['shipping_address']['address_format'] = '';
				}
	
				if ($zone_info) {
					$this->session->data['shipping_address']['zone'] = $zone_info['name'];
					$this->session->data['shipping_address']['zone_code'] = $zone_info['code'];
				} else {
					$this->session->data['shipping_address']['zone'] = '';
					$this->session->data['shipping_address']['zone_code'] = '';
				}
			}
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}
					
		$this->response->setOutput(json_encode($json));	
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