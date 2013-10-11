<?php 
class ControllerCheckoutPaymentAddress extends Controller {
	public function index() {
		$this->language->load('checkout/checkout');
		
		$this->data['text_address_existing'] = $this->language->get('text_address_existing');
		$this->data['text_address_new'] = $this->language->get('text_address_new');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_loading'] = $this->language->get('text_loading');

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

		if (isset($this->session->data['payment_address']['address_id'])) {
			$this->data['address_id'] = $this->session->data['payment_address']['address_id'];
		} else {
			$this->data['address_id'] = $this->customer->getAddressId();
		}
		
		$this->load->model('account/address');
		
		$this->data['addresses'] = $this->model_account_address->getAddresses();
												
		if (isset($this->session->data['payment_address']['country_id'])) {
			$this->data['country_id'] = $this->session->data['payment_address']['country_id'];		
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}
				
		if (isset($this->session->data['payment_address']['zone_id'])) {
			$this->data['zone_id'] = $this->session->data['payment_address']['zone_id'];		
		} else {
			$this->data['zone_id'] = '';
		}
		
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();
	
		$this->data['shipping_required'] = $this->cart->hasShipping();
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/payment_address.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/payment_address.tpl';
		} else {
			$this->template = 'default/template/checkout/payment_address.tpl';
		}
	
		$this->response->setOutput($this->render());			
  	}
	
	public function save() {
		$this->language->load('checkout/checkout');
		
		$json = array();
		
		// Validate if customer is logged in.
		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');
		}
		
		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart');
		}	
		
		// Validate minimum quantity requirments.			
		$products = $this->cart->getProducts();
				
		foreach ($products as $product) {
			$product_total = 0;
				
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}		
			
			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart');
				
				break;
			}				
		}
				
		if (!$json) {
			if (isset($this->request->post['payment_address']) && $this->request->post['payment_address'] == 'existing') {
				$this->load->model('account/address');
				
				if (empty($this->request->post['address_id'])) {
					$json['error']['warning'] = $this->language->get('error_address');
				} elseif (!in_array($this->request->post['address_id'], array_keys($this->model_account_address->getAddresses()))) {
					$json['error']['warning'] = $this->language->get('error_address');
				}
					
				if (!$json) {	
					// Default Payment Address
					$this->load->model('account/address');
	
					$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->request->post['address_id']);
						
					unset($this->session->data['payment_method']);	
					unset($this->session->data['payment_methods']);
				}
			} else {
				if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
					$json['error']['firstname'] = $this->language->get('error_firstname');
				}
		
				if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
					$json['error']['lastname'] = $this->language->get('error_lastname');
				}
					
				if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
					$json['error']['address_1'] = $this->language->get('error_address_1');
				}
		
				if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 32)) {
					$json['error']['city'] = $this->language->get('error_city');
				}
				
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
				
				if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2 || utf8_strlen($this->request->post['postcode']) > 10)) {
					$json['error']['postcode'] = $this->language->get('error_postcode');
				}
				
				if ($this->request->post['country_id'] == '') {
					$json['error']['country'] = $this->language->get('error_country');
				}
				
				if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
					$json['error']['zone'] = $this->language->get('error_zone');
				}
				
				// Custom Field Validation
				$this->load->model('account/custom_field');
				
				$custom_fields = $this->model_account_custom_field->getCustomFields('payment_address', $this->config->get('config_customer_group_id'));
				
				foreach ($custom_fields as $custom_field) {
					if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					}
				}
							
				if (!$json) {
					// Default Payment Address
					$this->load->model('account/address');
					
					$address_id = $this->model_account_address->addAddress($this->request->post);

					$this->session->data['payment_address'] = $this->model_account_address->getAddress($address_id);
															
					unset($this->session->data['payment_method']);	
					unset($this->session->data['payment_methods']);
				}		
			}		
		}
		
		$this->response->setOutput(json_encode($json));
	}
}
?>