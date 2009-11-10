<?php 
class ControllerCheckoutShipping extends Controller {
	private $error = array();
 	
  	public function index() {
    	if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$this->redirect($this->url->https('checkout/cart'));
    	}
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->https('checkout/shipping');

	  		$this->redirect($this->url->https('account/login'));
    	} 

    	if (!$this->cart->hasShipping()) {
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);

			$this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

			$this->redirect($this->url->https('checkout/payment'));
    	}

		if (!isset($this->session->data['shipping_address_id'])) {
			$this->session->data['shipping_address_id'] = $this->customer->getAddressId();
		}
	
    	if (!$this->session->data['shipping_address_id']) {
	  		$this->redirect($this->url->https('checkout/address/shipping'));
		}
		
		$this->load->model('account/address');
		
		$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);		

    	if (!$shipping_address) {
	  		$this->redirect($this->url->https('checkout/address/shipping'));
    	}	
		
		$this->tax->setZone($shipping_address['country_id'], $shipping_address['zone_id']);
		
		$this->load->model('checkout/extension');
			
		$quote_data = array();
		
		$results = $this->model_checkout_extension->getExtensions('shipping');
		
		foreach ($results as $result) {
			$this->load->model('shipping/' . $result['key']);
			
			$quote = $this->{'model_shipping_' . $result['key']}->getQuote($shipping_address['country_id'], $shipping_address['zone_id'], $shipping_address['postcode']); 

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
		
		$this->language->load('checkout/shipping');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$shipping = explode('.', $this->request->post['shipping_method']);
			
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			
			$this->session->data['comment'] = strip_tags($this->request->post['comment']);

	  		$this->redirect($this->url->https('checkout/payment'));
    	}
 
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
		
		$this->data['action'] = $this->url->https('checkout/shipping');
		
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
      		'country'   => $shipping_address['country']  
		);
	
		$this->data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		    	
		$this->data['change_address'] = $this->url->https('checkout/address/shipping'); 

		$this->data['methods'] = $this->session->data['shipping_methods']; 
    	
		if (isset($this->session->data['shipping_method']['id'])) {
			$this->data['default'] = $this->session->data['shipping_method']['id'];
		} else {
			$this->data['default'] = '';
		}
		
		if (isset($this->session->data['comment'])) {
    		$this->data['comment'] = $this->session->data['comment'];
		} else {
			$this->data['comment'] = '';
		}
		
    	$this->data['back'] = $this->url->https('checkout/cart');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/shipping.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/shipping.tpl';
		} else {
			$this->template = 'default/template/checkout/shipping.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
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