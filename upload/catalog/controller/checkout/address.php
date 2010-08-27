<?php 
class ControllerCheckoutAddress extends Controller {
	private $error = array(); 
	
	public function shipping() {
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/cart');
    	}
		
    	if (!$this->cart->hasShipping()) {
			$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/cart');
    	}
		
		if (!$this->customer->isLogged()) {  
			$this->session->data['redirect'] = HTTPS_SERVER . 'index.php?route=checkout/shipping';
      		
			$this->redirect(HTTPS_SERVER . 'index.php?route=account/login');
    	}	

    	$this->language->load('checkout/address');

    	$this->document->title = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=checkout/cart',
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=checkout/shipping',
        	'text'      => $this->language->get('text_shipping'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=checkout/address/shipping',
        	'text'      => $this->language->get('text_address'),
        	'separator' => $this->language->get('text_separator')
      	);

		$this->load->model('account/address');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['address_id'])) {
			$this->session->data['shipping_address_id'] = $this->request->post['address_id'];
			
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);
			
			if ($this->cart->hasShipping()) {
				$address_info = $this->model_account_address->getAddress($this->request->post['address_id']);
			
				if ($address_info) {
					$this->tax->setZone($address_info['country_id'], $address_info['zone_id']);
				}
			}
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/shipping');
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->session->data['shipping_address_id'] = $this->model_account_address->addAddress($this->request->post);
			
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);

			if ($this->cart->hasShipping()) {
				$this->tax->setZone($this->request->post['country_id'], $this->request->post['zone_id']);
			}	

			$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/shipping');
		}
	
		$this->getForm('shipping');
  	}
  
  	public function payment() {
    	if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/cart');
    	}
		
		if (!$this->customer->isLogged()) {  
			$this->session->data['redirect'] = HTTPS_SERVER . 'index.php?route=checkout/shipping';
      		
			$this->redirect(HTTPS_SERVER . 'index.php?route=account/login');
    	}	
		
		$this->language->load('checkout/address');
		
    	$this->document->title = $this->language->get('heading_title');  

		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=checkout/cart',
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		if ($this->cart->hasShipping()) {
      		$this->document->breadcrumbs[] = array(
        		'href'      => HTTP_SERVER . 'index.php?route=checkout/shipping',
        		'text'      => $this->language->get('text_shipping'),
        		'separator' => $this->language->get('text_separator')
      		);
		}
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=checkout/payment',
        	'text'      => $this->language->get('text_payment'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=checkout/address/payment',
        	'text'      => $this->language->get('text_address'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->load->model('account/address');
		 	 
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['address_id'])) {
			$this->session->data['payment_address_id'] = $this->request->post['address_id'];
	  		
			unset($this->session->data['payment_methods']);
			unset($this->session->data['payment_method']);
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/payment');
		} 
	   
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->session->data['payment_address_id'] = $this->model_account_address->addAddress($this->request->post);
	  		
			unset($this->session->data['payment_methods']);
			unset($this->session->data['payment_method']);
			
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/payment');
    	}
	
    	$this->getForm('payment');  
  	}
  
  	private function getForm($type) {
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_new_address'] = $this->language->get('text_new_address');
   	 	$this->data['text_entries'] = $this->language->get('text_entries');
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
    
		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}
		
		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}
		
		if (isset($this->error['address_1'])) {
			$this->data['error_address_1'] = $this->error['address_1'];
		} else {
			$this->data['error_address_1'] = '';
		}
		
		if (isset($this->error['city'])) {
			$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$this->data['error_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_postcode'] = '';
		}
		
		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$this->data['error_zone'] = $this->error['zone'];
		} else {
			$this->data['error_zone'] = '';
		}
		
    	$this->data['action'] = HTTPS_SERVER . 'index.php?route=checkout/address/' . $type;
		
		if (isset($this->session->data[$type . '_address_id'])) {
			$this->data['default'] = $this->session->data[$type . '_address_id'];
		} else {
			$this->data['default'] = '';
		}

    	$this->data['addresses'] = array();

		$results = $this->model_account_address->getAddresses();
    	
		foreach ($results as $result) {
      		$this->data['addresses'][] = array(
        		'address_id' => $result['address_id'],
	    		'address'    => $result['firstname'] . ' ' . $result['lastname'] . ', ' . $result['address_1'] . ', ' . $result['city'] . ', ' . (($result['zone']) ? $result['zone']  . ', ' : FALSE) . (($result['postcode']) ? $result['postcode']  . ', ' : FALSE) . $result['country'],
        		'href'       => HTTPS_SERVER . 'index.php?route=account/address/' . $type . '&address_id=' . $result['address_id']
      		);
    	}
		
		if (isset($this->request->post['firstname'])) {
    		$this->data['firstname'] = $this->request->post['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
    		$this->data['lastname'] = $this->request->post['lastname'];
		} else {
			$this->data['lastname'] = '';
		}

		if (isset($this->request->post['company'])) {
    		$this->data['company'] = $this->request->post['company'];
		} else {
			$this->data['company'] = '';
		}

		if (isset($this->request->post['address_1'])) {
    		$this->data['address_1'] = $this->request->post['address_1'];
		} else {
			$this->data['address_1'] = '';
		}

		if (isset($this->request->post['address_2'])) {
    		$this->data['address_2'] = $this->request->post['address_2'];
		} else {
			$this->data['address_2'] = '';
		}

		if (isset($this->request->post['city'])) {
    		$this->data['city'] = $this->request->post['city'];
		} else {
			$this->data['city'] = '';
		}

		if (isset($this->request->post['postcode'])) {
    		$this->data['postcode'] = $this->request->post['postcode'];				
		} else {
			$this->data['postcode'] = '';
		}

		if (isset($this->request->post['country_id'])) {
    		$this->data['country_id'] = $this->request->post['country_id'];			
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}

		if (isset($this->request->post['zone_id'])) {
    		$this->data['zone_id'] = $this->request->post['zone_id'];			
		} else {
			$this->data['zone_id'] = 'FALSE';
		}
		
		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/address.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/address.tpl';
		} else {
			$this->template = 'default/template/checkout/address.tpl';
		}
		
		$this->children = array(
			'common/column_right',
			'common/footer',
			'common/column_left',
			'common/header'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));			
  	}

  	private function validate() {
    	if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if ((strlen(utf8_decode($this->request->post['address_1'])) < 3) || (strlen(utf8_decode($this->request->post['address_1'])) > 64)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}

    	if ((strlen(utf8_decode($this->request->post['city'])) < 3) || (strlen(utf8_decode($this->request->post['city'])) > 32)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}
		
		$this->load->model('localisation/country');
		
		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
		
		if ($country_info && $country_info['postcode_required']) {
			if ((strlen(utf8_decode($this->request->post['postcode'])) < 2) || (strlen(utf8_decode($this->request->post['postcode'])) > 10)) {
				$this->error['postcode'] = $this->language->get('error_postcode');
			}
    	}
		
		if ($this->request->post['country_id'] == 'FALSE') {
      		$this->error['country'] = $this->language->get('error_country');
    	}
		
    	if ($this->request->post['zone_id'] == 'FALSE') {
      		$this->error['zone'] = $this->language->get('error_zone');
    	}
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}  
  	}

  	public function zone() {	
		$output = '<option value="FALSE">' . $this->language->get('text_select') . '</option>';

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
			if (!$this->request->get['zone_id']) {
		  		$output .= '<option value="0" selected="selected">' . $this->language->get('text_none') . '</option>';
			} else {
				$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
			}
    	}
	
		$this->response->setOutput($output, $this->config->get('config_compression'));
  	}
	
	public function postcode() {

  		$this->language->load('checkout/address');

  		$this->load->model('localisation/country');

    	$result = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		$output = '';

      	if ($result['postcode_required']) {
        	$output = '<span class="required">*</span> ' . $this->language->get('entry_postcode');
		} else {
			$output = $this->language->get('entry_postcode');
		}

		$this->response->setOutput($output, $this->config->get('config_compression'));
	}
}
?>