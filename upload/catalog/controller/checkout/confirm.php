<?php 
class ControllerCheckoutConfirm extends Controller { 
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

    		if (!isset($this->session->data['shipping_address_id'])) {
	  			$this->redirect($this->url->https('checkout/address/shipping'));
    		}
		} else {
			unset($this->session->data['shipping_address_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}
		
		if (!isset($this->session->data['payment_method'])) {
	  		$this->redirect($this->url->https('checkout/payment'));
    	}

    	if (!$this->customer->hasAddress($this->session->data['payment_address_id'])) { 
	  		$this->redirect($this->url->https('checkout/address/payment'));
    	}    
		
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();
		 
		$this->load->model('checkout/extension');
		
		$results = $this->model_checkout_extension->getExtensions('total');
		
		foreach ($results as $result) {
			$this->load->model('total/' . $result['key']);

			$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);
		}
		
		$sort_order = array(); 
	  
		foreach ($total_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $total_data);

		$this->load->language('checkout/confirm');

    	$this->document->title = $this->language->get('heading_title'); 
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->session->data['coupon'] = $this->request->post['coupon'];
			
			$this->session->data['success'] = $this->language->get('text_coupon');
			
			$this->redirect($this->url->https('checkout/confirm'));
		}	
		
		$data = array();
		
		$data['customer_id'] = $this->customer->getId();
		$data['language_id'] = $this->language->getId();
		$data['firstname'] = $this->customer->getFirstName();
		$data['lastname'] = $this->customer->getLastName();
		$data['email'] = $this->customer->getEmail();
		$data['telephone'] = $this->customer->getTelephone();
		$data['fax'] = $this->customer->getFax();
		
		$shipping_address = $this->customer->getAddress(@$this->session->data['shipping_address_id']);
		
		$data['shipping_firstname'] = @$shipping_address['firstname'];
		$data['shipping_lastname'] = @$shipping_address['lastname'];	
		$data['shipping_company'] = @$shipping_address['company'];	
		$data['shipping_address_1'] = @$shipping_address['address_1'];
		$data['shipping_address_2'] = @$shipping_address['address_2'];
		$data['shipping_city'] = @$shipping_address['city'];
		$data['shipping_postcode'] = @$shipping_address['postcode'];
		$data['shipping_zone'] = @$shipping_address['zone'];
		$data['shipping_country'] = @$shipping_address['country'];
		$data['shipping_address_format'] = @$shipping_address['address_format'];
		$data['shipping_method'] = @$this->session->data['shipping_method']['title'];
		
		$payment_address = $this->customer->getAddress($this->session->data['payment_address_id']);
		
		$data['payment_firstname'] = $payment_address['firstname'];
		$data['payment_lastname'] = $payment_address['lastname'];	
		$data['payment_company'] = $payment_address['company'];	
		$data['payment_address_1'] = $payment_address['address_1'];
		$data['payment_address_2'] = $payment_address['address_2'];
		$data['payment_city'] = $payment_address['city'];
		$data['payment_postcode'] = $payment_address['postcode'];
		$data['payment_zone'] = $payment_address['zone'];
		$data['payment_country'] = $payment_address['country'];
		$data['payment_address_format'] = $payment_address['address_format'];
		$data['payment_method'] = @$this->session->data['payment_method']['title'];

		$product_data = array();
	
		foreach ($this->cart->getProducts() as $product) {
      		$option_data = array();

      		foreach ($product['option'] as $option) {
        		$option_data[] = array(
          			'name'   => $option['name'],
          			'value'  => $option['value'],
		  			'prefix' => $option['prefix']
        		);
      		}
 
      		$product_data[] = array(
        		'product_id' => $product['product_id'],
				'name'       => $product['name'],
        		'model'      => $product['model'],
        		'option'     => $option_data,
				'download'   => $product['download'],
				'quantity'   => $product['quantity'],
				'price'      => $product['price'],
				'discount'   => $product['discount'],
        		'total'      => $product['total'],
				'tax'        => $this->tax->getRate($product['tax_class_id'])
      		); 
    	}
				
		$data['products'] = $product_data;
		$data['totals'] = $total_data;
		$data['comment'] = $this->session->data['comment'];
		$data['total'] = $total;
		$data['language_id'] = $this->language->getId();
		$data['currency_id'] = $this->currency->getId();
		$data['currency'] = $this->currency->getCode();
		$data['value'] = $this->currency->getValue($this->currency->getCode());
		$data['ip'] = $this->request->server['REMOTE_ADDR'];
		
		$this->load->model('checkout/order');
		
		$this->session->data['order_id'] = $this->model_checkout_order->create($data);

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

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/confirm'),
        	'text'      => $this->language->get('text_confirm'),
        	'separator' => $this->language->get('text_separator')
      	);
						 	
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
    	$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
    	$this->data['text_payment_address'] = $this->language->get('text_payment_address');
    	$this->data['text_payment_method'] = $this->language->get('text_payment_method');
    	$this->data['text_comment'] = $this->language->get('text_comment');
    	$this->data['text_change'] = $this->language->get('text_change');
    	
		$this->data['column_product'] = $this->language->get('column_product');
    	$this->data['column_model'] = $this->language->get('column_model');
    	$this->data['column_quantity'] = $this->language->get('column_quantity');
    	$this->data['column_price'] = $this->language->get('column_price');
    	$this->data['column_total'] = $this->language->get('column_total');
    	
		$this->data['entry_coupon'] = $this->language->get('entry_coupon');
		
    	$this->data['button_update'] = $this->language->get('button_update');
	
		$this->data['error'] = @$this->error['message'];
		
		$this->data['action'] = $this->url->https('checkout/confirm');
		
		if (isset($this->request->post['coupon'])) {
			$this->data['coupon'] = $this->request->post['coupon'];
		} else {
			$this->data['coupon'] = @$this->session->data['coupon'];
		}

    	$this->data['success'] = @$this->session->data['success'];
    
		unset($this->session->data['success']);

		$shipping_address = $this->customer->getAddress(@$this->session->data['shipping_address_id']);
		
		if ($shipping_address) {
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
			
			$this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		} else {
			$this->data['shipping_address'] = '';
		}
		
		$this->data['shipping_method'] = @$this->session->data['shipping_method']['title'];
		
    	$this->data['checkout_shipping'] = $this->url->https('checkout/shipping');

    	$this->data['checkout_shipping_address'] = $this->url->https('checkout/address/shipping');
		
		$payment_address = $this->customer->getAddress($this->session->data['payment_address_id']);
    	
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
      		'country'   => $payment_address['country']  
		);
		
		$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	
    	$this->data['payment_method'] = @$this->session->data['payment_method']['title'];
	
    	$this->data['checkout_payment'] = $this->url->https('checkout/payment');

    	$this->data['checkout_payment_address'] = $this->url->https('checkout/address/payment');

    	$this->data['products'] = array();

    	foreach ($this->cart->getProducts() as $product) {
      		$option_data = array();

      		foreach ($product['option'] as $option) {
        		$option_data[] = array(
          			'name'  => $option['name'],
          			'value' => $option['value']
        		);
      		} 
 
      		$this->data['products'][] = array(
				'product_id' => $product['product_id'],
        		'name'       => $product['name'],
        		'model'      => $product['model'],
        		'option'     => $option_data,
        		'quantity'   => $product['quantity'],
				'tax'        => $this->tax->getRate($product['tax_class_id']),
        		'price'      => $this->currency->format($product['price']),
				'discount'   => ($product['discount'] ? $this->currency->format($product['price'] - $product['discount']) : NULL),
        		'total'      => $this->currency->format($product['total']),
				'href'       => $this->url->http('product/product&product_id=' . $product['product_id'])
      		); 
    	} 
		
		$this->data['totals'] = $total_data;
	
		$this->data['comment'] = nl2br($this->session->data['comment']);
    
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'checkout/confirm.tpl';
		$this->layout   = 'common/layout';
		$this->children = array('payment/' . $this->session->data['payment_method']['id']);
		
		$this->render();
  	}
			
	private function validate() {
		$this->load->model('checkout/coupon');
			
		$coupon = $this->model_checkout_coupon->getCoupon($this->request->post['coupon']);
			
		if (!$coupon) {
			$this->error['message'] = $this->language->get('error_coupon');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>