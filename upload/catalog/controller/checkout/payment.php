<?php  
class ControllerCheckoutPayment extends Controller {
	private $error = array();
	
  	public function index() {
    	if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->https('checkout/shipping');
			
	  		$this->redirect($this->url->https('account/login'));
    	} 
 
    	if ((!$this->cart->hasProducts()) || ((!$this->cart->hasStock()) && (!$this->config->get('config_stock_checkout')))) {
      		$this->redirect($this->url->https('checkout/cart'));
    	}
			
    	if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_method'])) {
	  			$this->redirect($this->url->https('checkout/shipping'));
			}

			if (!$this->customer->hasAddress($this->session->data['shipping_address_id'])) {
	  			$this->redirect($this->url->https('checkout/address/shipping'));
    		}
		} else {
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}
			
    	if (!$this->customer->hasAddress(@$this->session->data['payment_address_id'])) {
      		$this->session->data['payment_address_id'] = @$this->session->data['shipping_address_id'];
    	}
	
    	if (!$this->customer->hasAddress($this->session->data['payment_address_id'])) {
	  		$this->session->data['payment_address_id'] = $this->customer->getAddressId();
    	}

    	if (!isset($this->session->data['payment_address_id'])) {
	  		$this->redirect($this->url->https('checkout/address/payment'));
    	}

		$this->load->model('checkout/extension');
		
		if (!isset($this->session->data['payment_methods'])) {
			$method_data = array();
		
			$results = $this->model_checkout_extension->getExtensions('payment');

			foreach ($results as $result) {
				$this->load->model('payment/' . $result['key']);
			
				$method = $this->{'model_payment_' . $result['key']}->getMethod(); 
			 
				if ($method) {
					$method_data[$result['key']] = $method;
				}
			}
					 
			$sort_order = array(); 
	  
			foreach ($method_data as $key => $value) {
      			$sort_order[$key] = $value['sort_order'];
    		}

    		array_multisort($sort_order, SORT_ASC, $method_data);			
		
			$this->session->data['payment_methods'] = $method_data;
		}
		
		$this->load->language('checkout/payment');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment']];
		  
	  	  	$this->session->data['comment'] = strip_tags($this->request->post['comment']);
		  
	  		$this->redirect($this->url->https('checkout/confirm'));
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

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/payment'),
        	'text'      => $this->language->get('text_payment'),
        	'separator' => $this->language->get('text_separator')
      	); 
				
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_payment_to'] = $this->language->get('text_payment_to');
    	$this->data['text_payment_address'] = $this->language->get('text_payment_address');
    	$this->data['text_payment_method'] = $this->language->get('text_payment_method');
    	$this->data['text_payment_methods'] = $this->language->get('text_payment_methods');
    	$this->data['text_comments'] = $this->language->get('text_comments');

    	$this->data['button_change_address'] = $this->language->get('button_change_address');
    	$this->data['button_continue'] = $this->language->get('button_continue');
    	$this->data['button_back'] = $this->language->get('button_back');

    	$this->data['error'] = @$this->error['message'];

    	$this->data['action'] = $this->url->https('checkout/payment');
    
		$address = $this->customer->getAddress($this->session->data['payment_address_id']);
    	
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
		
		$this->data['change_address'] = $this->url->https('checkout/address/payment');
		
		$this->data['methods'] = $this->session->data['payment_methods'];

    	$this->data['default'] = @$this->session->data['payment_method']['id'];
 
    	$this->data['comment'] = @$this->session->data['comment'];

    	if ($this->cart->hasShipping()) {
      		$this->data['back'] = $this->url->https('checkout/shipping');
    	} else {
      		$this->data['back'] = $this->url->https('checkout/cart');
    	}
	
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'checkout/payment.tpl';
		$this->layout   = 'module/layout';
		
		$this->render();	
  	}
  
  	private function validate() {
    	if (!isset($this->request->post['payment'])) {
	  		$this->error['message'] = $this->language->get('error_payment');
		} else {
			if (!isset($this->session->data['payment_methods'][$this->request->post['payment']])) {
				$this->error['message'] = $this->language->get('error_payment');
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
