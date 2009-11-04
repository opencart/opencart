<?php 
class ControllerCheckoutAddress extends Controller {
	private $error = array(); 
	
	public function shipping() {
		if (!$this->customer->isLogged()) {  
			$this->session->data['redirect'] = $this->url->https('checkout/shipping');
      		
			$this->redirect($this->url->https('account/login'));
    	}	
	
    	$this->language->load('checkout/address');

    	$this->document->title = $this->language->get('heading_title');

		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/cart'),
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/shipping'),
        	'text'      => $this->language->get('text_shipping'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/address/shipping'),
        	'text'      => $this->language->get('text_address'),
        	'separator' => $this->language->get('text_separator')
      	);

		$this->load->model('account/address');
				  
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['address_id'])) {
	  		unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);
			
			$this->session->data['shipping_address_id'] = $this->request->post['address_id'];

			$this->redirect($this->url->https('checkout/shipping'));
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
	  		unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);
			
			$this->session->data['shipping_address_id'] = $this->model_account_address->addAddress($this->request->post);

	  		$this->redirect($this->url->https('checkout/shipping'));
    	}
	
		$this->getForm('shipping');
  	}
  
  	public function payment() {
		if (!$this->customer->isLogged()) {  
      		$this->session->data['redirect'] = $this->url->https('checkout/shipping');

      		$this->redirect($this->url->https('account/login'));
    	}		
		
		$this->language->load('checkout/address');
		
    	$this->document->title = $this->language->get('heading_title');  

		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/cart'),
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		if ($this->cart->hasShipping()) {
      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->url->http('checkout/shipping'),
        		'text'      => $this->language->get('text_shipping'),
        		'separator' => $this->language->get('text_separator')
      		);
		}
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/payment'),
        	'text'      => $this->language->get('text_payment'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/address/payment'),
        	'text'      => $this->language->get('text_address'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->load->model('account/address');
		 	 
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['address_id'])) {
	  		unset($this->session->data['payment_methods']);
			unset($this->session->data['payment_method']);
			
			$this->session->data['payment_address_id'] = $this->request->post['address_id'];
	  		
			$this->redirect($this->url->https('checkout/payment'));
		} 
	   
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
	  		unset($this->session->data['payment_methods']);
			unset($this->session->data['payment_method']);
			
			$this->session->data['payment_address_id'] = $this->model_account_address->addAddress($this->request->post);
	    
	  		$this->redirect($this->url->https('checkout/payment'));
    	}
	
    	$this->getForm('payment');  
  	}
  
  	private function getForm($type) {
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_new_address'] = $this->language->get('text_new_address');
   	 	$this->data['text_entries'] = $this->language->get('text_entries');

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

    	$this->data['action'] = $this->url->https('checkout/address/' . $type);
				
    	$this->data['default'] = $this->session->data[$type . '_address_id'];

    	$this->data['addresses'] = array();

		$results = $this->model_account_address->getAddresses();
    	
		foreach ($results as $result) {
      		$this->data['addresses'][] = array(
        		'address_id' => $result['address_id'],
	    		'address'    => $result['firstname'] . ' ' . $result['lastname'] . ', ' . $result['address_1'] . ', ' . $result['city'] . ', ' . (($result['zone']) ? $result['zone']  . ', ' : FALSE) . $result['country'],
        		'href'       => $this->url->https('account/address/' . $type . '&address_id=' . $result['address_id'])
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
      		$this->data['zone_id'] = 0;
    	}

		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'checkout/address.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();			
  	}

  	private function validate() {
    	if ((strlen(utf8_decode($this->request->post['firstname'])) < 3) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((strlen(utf8_decode($this->request->post['lastname'])) < 3) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if ((strlen(utf8_decode($this->request->post['address_1'])) < 3) || (strlen(utf8_decode($this->request->post['address_1'])) > 64)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}

    	if ((strlen(utf8_decode($this->request->post['city'])) < 3) || (strlen(utf8_decode($this->request->post['city'])) > 32)) {
      		$this->error['city'] = $this->language->get('error_city');
    	} 
	
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}  
  	}

  	public function zone() {	
    	$output = '<select name="zone_id">';

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

    	$output .= '</select>';
	
		$this->response->setOutput($output);
  	}  
}
?>