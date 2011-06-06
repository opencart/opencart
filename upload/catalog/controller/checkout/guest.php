<?php 
class ControllerCheckoutGuest extends Controller {
  	public function index() {
    	$this->language->load('checkout/checkout');
		
		$json = array();
		
		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		} 			
		
		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');		
		}
					
		if (!$this->config->get('config_guest_checkout') || $this->cart->hasDownload()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		} 
					
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$json) {
				if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
					$json['error']['firstname'] = $this->language->get('error_firstname');
				}
		
				if ((strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
					$json['error']['lastname'] = $this->language->get('error_lastname');
				}
		
				if ((strlen(utf8_decode($this->request->post['email'])) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
					$json['error']['email'] = $this->language->get('error_email');
				}
				
				if ((strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32)) {
					$json['error']['telephone'] = $this->language->get('error_telephone');
				}
				
				if ((strlen(utf8_decode($this->request->post['address_1'])) < 3) || (strlen(utf8_decode($this->request->post['address_1'])) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
				}
		
				if ((strlen(utf8_decode($this->request->post['city'])) < 2) || (strlen(utf8_decode($this->request->post['city'])) > 128)) {
					$json['error']['city'] = $this->language->get('error_city');
				}
				
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
				
				if ($country_info && $country_info['postcode_required'] && (strlen(utf8_decode($this->request->post['postcode'])) < 2) || (strlen(utf8_decode($this->request->post['postcode'])) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
				}
		
				if ($this->request->post['country_id'] == '') {
					$json['error']['country'] = $this->language->get('error_country');
				}
				
				if ($this->request->post['zone_id'] == '') {
					$json['error']['zone'] = $this->language->get('error_zone');
				}	
			}
			
			if (!$json) {
				$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
				$this->session->data['guest']['lastname'] = $this->request->post['lastname'];
				$this->session->data['guest']['email'] = $this->request->post['email'];
				$this->session->data['guest']['telephone'] = $this->request->post['telephone'];
				$this->session->data['guest']['fax'] = $this->request->post['fax'];
				
				$this->session->data['guest']['payment']['firstname'] = $this->request->post['firstname'];
				$this->session->data['guest']['payment']['lastname'] = $this->request->post['lastname'];				
				$this->session->data['guest']['payment']['company'] = $this->request->post['company'];
				$this->session->data['guest']['payment']['address_1'] = $this->request->post['address_1'];
				$this->session->data['guest']['payment']['address_2'] = $this->request->post['address_2'];
				$this->session->data['guest']['payment']['postcode'] = $this->request->post['postcode'];
				$this->session->data['guest']['payment']['city'] = $this->request->post['city'];
				$this->session->data['guest']['payment']['country_id'] = $this->request->post['country_id'];
				$this->session->data['guest']['payment']['zone_id'] = $this->request->post['zone_id'];
								
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
				
				if ($country_info) {
					$this->session->data['guest']['payment']['country'] = $country_info['name'];	
					$this->session->data['guest']['payment']['iso_code_2'] = $country_info['iso_code_2'];
					$this->session->data['guest']['payment']['iso_code_3'] = $country_info['iso_code_3'];
					$this->session->data['guest']['payment']['address_format'] = $country_info['address_format'];
				} else {
					$this->session->data['guest']['payment']['country'] = '';	
					$this->session->data['guest']['payment']['iso_code_2'] = '';
					$this->session->data['guest']['payment']['iso_code_3'] = '';
					$this->session->data['guest']['payment']['address_format'] = '';
				}
							
				$this->load->model('localisation/zone');
	
				$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
				
				if ($zone_info) {
					$this->session->data['guest']['payment']['zone'] = $zone_info['name'];
					$this->session->data['guest']['payment']['zone_code'] = $zone_info['code'];
				} else {
					$this->session->data['guest']['payment']['zone'] = '';
					$this->session->data['guest']['payment']['zone_code'] = '';
				}
				
				if (isset($this->request->post['shipping_address']) && $this->request->post['shipping_address']) {
					$this->session->data['guest']['shipping_address'] = true;
				} else {
					$this->session->data['guest']['shipping_address'] = false;
				}
				
				if ($this->session->data['guest']['shipping_address']) {
					$this->session->data['guest']['shipping']['firstname'] = $this->request->post['firstname'];
					$this->session->data['guest']['shipping']['lastname'] = $this->request->post['lastname'];
					$this->session->data['guest']['shipping']['company'] = $this->request->post['company'];
					$this->session->data['guest']['shipping']['address_1'] = $this->request->post['address_1'];
					$this->session->data['guest']['shipping']['address_2'] = $this->request->post['address_2'];
					$this->session->data['guest']['shipping']['postcode'] = $this->request->post['postcode'];
					$this->session->data['guest']['shipping']['city'] = $this->request->post['city'];
					$this->session->data['guest']['shipping']['country_id'] = $this->request->post['country_id'];
					$this->session->data['guest']['shipping']['zone_id'] = $this->request->post['zone_id'];
					
					if ($country_info) {
						$this->session->data['guest']['shipping']['country'] = $country_info['name'];	
						$this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];
						$this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];
						$this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];
					} else {
						$this->session->data['guest']['shipping']['country'] = '';	
						$this->session->data['guest']['shipping']['iso_code_2'] = '';
						$this->session->data['guest']['shipping']['iso_code_3'] = '';
						$this->session->data['guest']['shipping']['address_format'] = '';
					}
		
					if ($zone_info) {
						$this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
						$this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
					} else {
						$this->session->data['guest']['shipping']['zone'] = '';
						$this->session->data['guest']['shipping']['zone_code'] = '';
					}
					
					$this->tax->setZone($this->request->post['country_id'], $this->request->post['zone_id']);				
				}
				
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['payment_method']);
			}
    	} else {
			$this->data['text_select'] = $this->language->get('text_select');
			$this->data['text_your_details'] = $this->language->get('text_your_details');
			$this->data['text_your_address'] = $this->language->get('text_your_address');
			
			$this->data['entry_firstname'] = $this->language->get('entry_firstname');
			$this->data['entry_lastname'] = $this->language->get('entry_lastname');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_telephone'] = $this->language->get('entry_telephone');
			$this->data['entry_fax'] = $this->language->get('entry_fax');
			$this->data['entry_company'] = $this->language->get('entry_company');
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
				$this->data['lastname'] = $this->session->data['guest']['lastname'];
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
	
			if (isset($this->session->data['guest']['payment']['fax'])) {
				$this->data['fax'] = $this->session->data['guest']['payment']['fax'];				
			} else {
				$this->data['fax'] = '';
			}
	
			if (isset($this->session->data['guest']['payment']['company'])) {
				$this->data['company'] = $this->session->data['guest']['payment']['company'];			
			} else {
				$this->data['company'] = '';
			}
			
			if (isset($this->session->data['guest']['payment']['address_1'])) {
				$this->data['address_1'] = $this->session->data['guest']['payment']['address_1'];			
			} else {
				$this->data['address_1'] = '';
			}
	
			if (isset($this->session->data['guest']['payment']['address_2'])) {
				$this->data['address_2'] = $this->session->data['guest']['payment']['address_2'];			
			} else {
				$this->data['address_2'] = '';
			}
	
			if (isset($this->session->data['guest']['payment']['postcode'])) {
				$this->data['postcode'] = $this->session->data['guest']['payment']['postcode'];					
			} else {
				$this->data['postcode'] = '';
			}
			
			if (isset($this->session->data['guest']['payment']['city'])) {
				$this->data['city'] = $this->session->data['guest']['payment']['city'];			
			} else {
				$this->data['city'] = '';
			}
	
			if (isset($this->session->data['guest']['payment']['country_id'])) {
				$this->data['country_id'] = $this->session->data['guest']['payment']['country_id'];			  	
			} else {
				$this->data['country_id'] = $this->config->get('config_country_id');
			}
	
			if (isset($this->session->data['guest']['payment']['zone_id'])) {
				$this->data['zone_id'] = $this->session->data['guest']['payment']['zone_id'];			
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
					
			$json['output'] = $this->render();	
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));			
  	}
  	
	public function shipping() {
		$this->language->load('checkout/checkout');
		
		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}
	
			if ((strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}
			
			if ((strlen(utf8_decode($this->request->post['address_1'])) < 3) || (strlen(utf8_decode($this->request->post['address_1'])) > 128)) {
				$json['error']['address_1'] = $this->language->get('error_address_1');
			}
	
			if ((strlen(utf8_decode($this->request->post['city'])) < 2) || (strlen(utf8_decode($this->request->post['city'])) > 128)) {
				$json['error']['city'] = $this->language->get('error_city');
			}
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			
			if ($country_info && $country_info['postcode_required'] && (strlen(utf8_decode($this->request->post['postcode'])) < 2) || (strlen(utf8_decode($this->request->post['postcode'])) > 10)) {
				$json['error']['postcode'] = $this->language->get('error_postcode');
			}
	
			if ($this->request->post['country_id'] == '') {
				$json['error']['country'] = $this->language->get('error_country');
			}
			
			if ($this->request->post['zone_id'] == '') {
				$json['error']['zone'] = $this->language->get('error_zone');
			}	
						
			if (!$json) {
				$this->session->data['guest']['shipping']['firstname'] = trim($this->request->post['firstname']);
				$this->session->data['guest']['shipping']['lastname'] = trim($this->request->post['lastname']);
				$this->session->data['guest']['shipping']['company'] = trim($this->request->post['company']);
				$this->session->data['guest']['shipping']['address_1'] = $this->request->post['address_1'];
				$this->session->data['guest']['shipping']['address_2'] = $this->request->post['address_2'];
				$this->session->data['guest']['shipping']['postcode'] = $this->request->post['postcode'];
				$this->session->data['guest']['shipping']['city'] = $this->request->post['city'];
				$this->session->data['guest']['shipping']['country_id'] = $this->request->post['country_id'];
				$this->session->data['guest']['shipping']['zone_id'] = $this->request->post['zone_id'];
				
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
				
				if ($country_info) {
					$this->session->data['guest']['shipping']['country'] = $country_info['name'];	
					$this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];
					$this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];
					$this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];
				} else {
					$this->session->data['guest']['shipping']['country'] = '';	
					$this->session->data['guest']['shipping']['iso_code_2'] = '';
					$this->session->data['guest']['shipping']['iso_code_3'] = '';
					$this->session->data['guest']['shipping']['address_format'] = '';
				}
				
				$this->load->model('localisation/zone');
								
				$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
			
				if ($zone_info) {
					$this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
					$this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
				} else {
					$this->session->data['guest']['shipping']['zone'] = '';
					$this->session->data['guest']['shipping']['zone_code'] = '';
				}
				
				if ($this->cart->hasShipping()) {
					$this->tax->setZone($this->request->post['country_id'], $this->request->post['zone_id']);
				}
			}
		} else {
			$this->data['text_select'] = $this->language->get('text_select');
	
			$this->data['entry_firstname'] = $this->language->get('entry_firstname');
			$this->data['entry_lastname'] = $this->language->get('entry_lastname');
			$this->data['entry_company'] = $this->language->get('entry_company');
			$this->data['entry_address_1'] = $this->language->get('entry_address_1');
			$this->data['entry_address_2'] = $this->language->get('entry_address_2');
			$this->data['entry_postcode'] = $this->language->get('entry_postcode');
			$this->data['entry_city'] = $this->language->get('entry_city');
			$this->data['entry_country'] = $this->language->get('entry_country');
			$this->data['entry_zone'] = $this->language->get('entry_zone');
		
			$this->data['button_continue'] = $this->language->get('button_continue');
						
			if (isset($this->session->data['guest']['shipping']['firstname'])) {
				$this->data['firstname'] = $this->session->data['guest']['shipping']['firstname'];
			} else {
				$this->data['firstname'] = '';
			}
	
			if (isset($this->session->data['guest']['shipping']['lastname'])) {
				$this->data['lastname'] = $this->session->data['guest']['shipping']['lastname'];
			} else {
				$this->data['lastname'] = '';
			}
			
			if (isset($this->session->data['guest']['shipping']['company'])) {
				$this->data['company'] = $this->session->data['guest']['shipping']['company'];			
			} else {
				$this->data['company'] = '';
			}
			
			if (isset($this->session->data['guest']['shipping']['address_1'])) {
				$this->data['address_1'] = $this->session->data['guest']['shipping']['address_1'];			
			} else {
				$this->data['address_1'] = '';
			}
	
			if (isset($this->session->data['guest']['shipping']['address_2'])) {
				$this->data['address_2'] = $this->session->data['guest']['shipping']['address_2'];			
			} else {
				$this->data['address_2'] = '';
			}
	
			if (isset($this->session->data['guest']['shipping']['postcode'])) {
				$this->data['postcode'] = $this->session->data['guest']['shipping']['postcode'];					
			} else {
				$this->data['postcode'] = '';
			}
			
			if (isset($this->session->data['guest']['shipping']['city'])) {
				$this->data['city'] = $this->session->data['guest']['shipping']['city'];			
			} else {
				$this->data['city'] = '';
			}
	
			if (isset($this->session->data['guest']['shipping']['country_id'])) {
				$this->data['country_id'] = $this->session->data['guest']['shipping']['country_id'];			  	
			} else {
				$this->data['country_id'] = $this->config->get('config_country_id');
			}
	
			if (isset($this->session->data['guest']['shipping']['zone_id'])) {
				$this->data['zone_id'] = $this->session->data['guest']['shipping']['zone_id'];			
			} else {
				$this->data['zone_id'] = '';
			}
						
			$this->load->model('localisation/country');
			
			$this->data['countries'] = $this->model_localisation_country->getCountries();
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/guest_shipping.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/guest_shipping.tpl';
			} else {
				$this->template = 'default/template/checkout/guest_shipping.tpl';
			}		
			
			$json['output'] = $this->render();	
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