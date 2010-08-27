<?php 
class ControllerCheckoutShipping extends Controller {
	private $error = array();
 	
  	public function index() {
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$shipping = explode('.', $this->request->post['shipping_method']);
			
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			
			$this->session->data['comment'] = strip_tags($this->request->post['comment']);

	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/payment');
    	}
		
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/cart');
    	}
		
		if (!$this->customer->isLogged()) {
			
			if (isset($this->session->data['guest'])) {
				$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/guest_step_1');
			}
			
			$this->session->data['redirect'] = HTTPS_SERVER . 'index.php?route=checkout/shipping';

	  		$this->redirect(HTTPS_SERVER . 'index.php?route=account/login');
    	} 

    	if (!$this->cart->hasShipping()) {
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);

			$this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

			$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/payment');
    	}

		if (!isset($this->session->data['shipping_address_id'])) {
			$this->session->data['shipping_address_id'] = $this->customer->getAddressId();
		}
	
    	if (!$this->session->data['shipping_address_id']) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/address/shipping');
		}
		
		$this->language->load('checkout/shipping');
				
		$this->load->model('account/address');
		
		$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		

    	if (!$shipping_address) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/address/shipping');
    	}	
		
		$this->tax->setZone($shipping_address['country_id'], $shipping_address['zone_id']);
		
		$this->load->model('checkout/extension');
		
		if (!isset($this->session->data['shipping_methods']) || !$this->config->get('config_shipping_session')) {
			$quote_data = array();
			
			$results = $this->model_checkout_extension->getExtensions('shipping');
			
			foreach ($results as $result) {
				$this->load->model('shipping/' . $result['key']);
				
				$quote = $this->{'model_shipping_' . $result['key']}->getQuote($shipping_address); 
	
				if ($quote) {
					$quote_data[$result['key']] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'], 
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
			}
	
			$sort_order = array();
		  
			foreach ($quote_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $quote_data);
			
			$this->session->data['shipping_methods'] = $quote_data;
		}
			 
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
				
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_shipping_to'] = $this->language->get('text_shipping_to');
    	$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
    	$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
    	$this->data['text_shipping_methods'] = $this->language->get('text_shipping_methods');
    	$this->data['text_comments'] = $this->language->get('text_comments');
    
		$this->data['button_change_address'] = $this->language->get('button_change_address');
    	$this->data['button_back'] = $this->language->get('button_back');
    	$this->data['button_continue'] = $this->language->get('button_continue');
   
   		if (isset($this->error['warning'])) {
    		$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['shipping_methods']) && !$this->session->data['shipping_methods']) {
			$this->data['error_warning'] = $this->language->get('error_no_shipping');
		}	
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=checkout/shipping';
		
		if ($shipping_address['address_format']) {
      		$format = $shipping_address['address_format'];
    	} else {
			$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
		}
		
    	$find = array(
	  		'{firstname}',
	  		'{lastname}',
	  		'{company}',
      		'{address_1}',
      		'{address_2}',
     		'{city}',
      		'{postcode}',
      		'{zone}',
			'{zone_code}',
      		'{country}'
		);
	
		$replace = array(
	  		'firstname' => $shipping_address['firstname'],
	  		'lastname'  => $shipping_address['lastname'],
	  		'company'   => $shipping_address['company'],
      		'address_1' => $shipping_address['address_1'],
      		'address_2' => $shipping_address['address_2'],
      		'city'      => $shipping_address['city'],
      		'postcode'  => $shipping_address['postcode'],
      		'zone'      => $shipping_address['zone'],
			'zone_code' => $shipping_address['zone_code'],
      		'country'   => $shipping_address['country']  
		);
	
		$this->data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		    	
		$this->data['change_address'] = HTTPS_SERVER . 'index.php?route=checkout/address/shipping'; 

		if (isset($this->session->data['shipping_methods'])) {
			$this->data['shipping_methods'] = $this->session->data['shipping_methods']; 
		} else {
			$this->data['shipping_methods'] = array();
		}
    	
		if (isset($this->session->data['shipping_method']['id'])) {
			$this->data['shipping'] = $this->session->data['shipping_method']['id'];
		} else {
			$this->data['shipping'] = '';
		}
		
		if (isset($this->request->post['comment'])) {
			$this->data['comment'] = $this->request->post['comment'];
		} elseif (isset($this->session->data['comment'])) {
    		$this->data['comment'] = $this->session->data['comment'];
		} else {
			$this->data['comment'] = '';
		}
		
    	$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/cart';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/shipping.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/shipping.tpl';
		} else {
			$this->template = 'default/template/checkout/shipping.tpl';
		}
		
		$this->children = array(
			'common/column_right',
			'common/footer',
			'common/column_left',
			'common/header'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
  	}
  
  	public function validate() {
    	if (!isset($this->request->post['shipping_method'])) {
	  		$this->error['warning'] = $this->language->get('error_shipping');
		} else {
			$shipping = explode('.', $this->request->post['shipping_method']);
			
			if (!isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
				$this->error['warning'] = $this->language->get('error_shipping');
			}
		}
	
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
}
?>