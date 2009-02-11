<?php 
class ControllerCheckoutShipping extends Controller {
	private $error = array();
 	
  	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->https('checkout/shipping');

	  		$this->redirect($this->url->https('account/login'));
    	} 

    	if ((!$this->cart->hasProducts()) || ((!$this->cart->hasStock()) && (!$this->config->get('config_stock_checkout')))) {
	  		$this->redirect($this->url->https('checkout/cart'));
    	}

    	if (!$this->cart->hasShipping()) {
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			
			$this->redirect($this->url->https('checkout/payment'));
    	}

    	if (!$this->customer->hasAddress(@$this->session->data['shipping_address_id'])) {
	  		$this->session->data['shipping_address_id'] = $this->customer->getAddressId();
    	}

    	if (!$this->customer->hasAddress(@$this->session->data['shipping_address_id'])) {
	  		$this->redirect($this->url->https('checkout/address/shipping'));
		}

		$this->load->model('checkout/shipping');
		
		if (!isset($this->session->data['shipping_methods'])) {
			$this->session->data['shipping_methods'] = $this->model_checkout_shipping->getQuotes();
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$shipping = explode('.', $this->request->post['shipping']);
			
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			
			$this->session->data['comment'] = strip_tags($this->request->post['comment']);

	  		$this->redirect($this->url->https('checkout/payment'));
    	}
		
    	$this->load->language('checkout/shipping');
 
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
    
		$this->data['error'] = @$this->error['message'];
    
		$this->data['action'] = $this->url->https('checkout/shipping');
		
		$address = $this->customer->getAddress($this->session->data['shipping_address_id']);
    	
		if ($address['address_format']) {
      		$format = $address['address_format'];
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
	  		'firstname' => $address['firstname'],
	  		'lastname'  => $address['lastname'],
	  		'company'   => $address['company'],
      		'address_1' => $address['address_1'],
      		'address_2' => $address['address_2'],
      		'city'      => $address['city'],
      		'postcode'  => $address['postcode'],
      		'zone'      => $address['zone'],
      		'country'   => $address['country']  
		);
	
		$this->data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		    	
		$this->data['change_address'] = $this->url->https('checkout/address/shipping'); 

		$this->data['methods'] = $this->session->data['shipping_methods']; 
    	
		$this->data['default'] = @$this->session->data['shipping_method']['id'];
		
    	$this->data['comment'] = @$this->session->data['comment'];

    	$this->data['back'] = $this->url->https('checkout/cart');
		
		$this->id       = 'content';
		$this->template = 'checkout/shipping.tpl';
		$this->layout   = 'module/layout';
		
		$this->render();		
  	}
  
  	public function validate() {
    	if (!isset($this->request->post['shipping'])) {
	  		$this->error['message'] = $this->language->get('error_shipping');
		} else {
			$shipping = explode('.', $this->request->post['shipping']);
			
			if (!isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
				$this->error['message'] = $this->language->get('error_shipping');
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