<?php  
class ControllerCheckoutPayment extends Controller {
	private $error = array();
	
  	public function index() {
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['coupon']) && $this->validateCoupon()) {
			$this->session->data['coupon'] = $this->request->post['coupon'];
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/payment');
		}
		
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
      		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/cart');
    	}
		
    	if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = HTTPS_SERVER . 'index.php?route=checkout/shipping';
			
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=account/login');
    	} 
		
		$this->load->model('account/address');
		
    	if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_address_id']) || !$this->session->data['shipping_address_id']) {
	  			$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/shipping');
			}
			
			if (!isset($this->session->data['shipping_method'])) {
	  			$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/shipping');
			}
		} else {
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			
			$this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}
		
    	if (!isset($this->session->data['payment_address_id']) && isset($this->session->data['shipping_address_id']) && $this->session->data['shipping_address_id']) {
      		$this->session->data['payment_address_id'] = $this->session->data['shipping_address_id'];
    	}
		
    	if (!isset($this->session->data['payment_address_id'])) {
	  		$this->session->data['payment_address_id'] = $this->customer->getAddressId();
    	}

    	if (!$this->session->data['payment_address_id']) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/address/payment');
    	}
		
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();
		 
		$this->load->model('checkout/extension');
		
		$sort_order = array(); 
		
		$results = $this->model_checkout_extension->getExtensions('total');
		
		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
		}
		
		array_multisort($sort_order, SORT_ASC, $results);
		
		foreach ($results as $result) {
			$this->load->model('total/' . $result['key']);

			$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
		}
		
		$sort_order = array(); 
	  
		foreach ($total_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $total_data);

		$this->language->load('checkout/confirm');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/address');
		
		$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);		
		
    	if (!$payment_address) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/address/payment');
    	}		
		
		if (!$this->cart->hasShipping()) {
    		$this->tax->setZone($payment_address['country_id'], $payment_address['zone_id']);
		}
		
		$this->load->model('checkout/extension');
		
		$method_data = array();
		
		$results = $this->model_checkout_extension->getExtensions('payment');

		foreach ($results as $result) {
			$this->load->model('payment/' . $result['key']);
			
			$method = $this->{'model_payment_' . $result['key']}->getMethod($payment_address); 
			 
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
		
		$this->language->load('checkout/payment');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && !isset($this->request->post['coupon']) && $this->validate()) {
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
		  
	  	  	$this->session->data['comment'] = strip_tags($this->request->post['comment']);
		  
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/confirm');
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

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=checkout/payment',
        	'text'      => $this->language->get('text_payment'),
        	'separator' => $this->language->get('text_separator')
      	); 
				
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_payment_to'] = $this->language->get('text_payment_to');
    	$this->data['text_payment_address'] = $this->language->get('text_payment_address');
    	$this->data['text_payment_method'] = $this->language->get('text_payment_method');
    	$this->data['text_payment_methods'] = $this->language->get('text_payment_methods');
    	$this->data['text_comments'] = $this->language->get('text_comments');
		$this->data['text_coupon'] = $this->language->get('text_coupon');

    	$this->data['button_change_address'] = $this->language->get('button_change_address');
    	$this->data['button_continue'] = $this->language->get('button_continue');
    	$this->data['button_back'] = $this->language->get('button_back');
		$this->data['button_coupon'] = $this->language->get('button_coupon');
    	
    	$this->data['entry_coupon'] = $this->language->get('entry_coupon');
    	
		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
    		$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
    		unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
    	$this->data['action'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
    			
		$this->data['coupon_status'] = $this->config->get('coupon_status');

		if (isset($this->request->post['coupon'])) {
			$this->data['coupon'] = $this->request->post['coupon'];
		} elseif (isset($this->session->data['coupon'])) {
			$this->data['coupon'] = $this->session->data['coupon'];
		} else {
			$this->data['coupon'] = '';
		}
		
		if ($payment_address['address_format']) {
      		$format = $payment_address['address_format'];
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
	  		'firstname' => $payment_address['firstname'],
	  		'lastname'  => $payment_address['lastname'],
	  		'company'   => $payment_address['company'],
      		'address_1' => $payment_address['address_1'],
      		'address_2' => $payment_address['address_2'],
      		'city'      => $payment_address['city'],
      		'postcode'  => $payment_address['postcode'],
      		'zone'      => $payment_address['zone'],
			'zone_code' => $payment_address['zone_code'],
      		'country'   => $payment_address['country']  
		);
	
		$this->data['address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
		$this->data['change_address'] = HTTPS_SERVER . 'index.php?route=checkout/address/payment';

		if (isset($this->session->data['payment_methods'])) {
        	$this->data['payment_methods'] = $this->session->data['payment_methods']; 
      	} else {
        	$this->data['payment_methods'] = array();
		}
	  
		if (isset($this->request->post['payment_method'])) {
			$this->data['payment'] = $this->request->post['payment_method'];
		} elseif (isset($this->session->data['payment_method']['id'])) {
    		$this->data['payment'] = $this->session->data['payment_method']['id'];
		} else {
			$this->data['payment'] = '';
		}
		
		if (isset($this->request->post['comment'])) {
			$this->data['comment'] = $this->request->post['comment'];
		} elseif (isset($this->session->data['comment'])) {
    		$this->data['comment'] = $this->session->data['comment'];
		} else {
			$this->data['comment'] = '';
		}
		
		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
			
			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), HTTP_SERVER . 'index.php?route=information/information&information_id=' . $this->config->get('config_checkout_id'), $information_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
		
		if (isset($this->request->post['agree'])) { 
      		$this->data['agree'] = $this->request->post['agree'];
		} else {
			$this->data['agree'] = '';
		}
		
    	if ($this->cart->hasShipping()) {
      		$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/shipping';
    	} else {
      		$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/cart';
    	}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/payment.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/payment.tpl';
		} else {
			$this->template = 'default/template/checkout/payment.tpl';
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
      	
    	if (!isset($this->request->post['payment_method'])) {
	  		$this->error['warning'] = $this->language->get('error_payment');
		} else {
			if (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
				$this->error['warning'] = $this->language->get('error_payment');
			}
		}
		
		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
			
			if ($information_info) {
    			if (!isset($this->request->post['agree'])) {
      				$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
    			}
			}
		}
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
  	
  	private function validateCoupon() {
  	
  		$this->load->model('checkout/coupon');
		
		$this->language->load('checkout/payment');
		
		$coupon = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);
			
		if (!$coupon) {
			$this->error['warning'] = $this->language->get('error_coupon');
		}
  		
  		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
}
?>