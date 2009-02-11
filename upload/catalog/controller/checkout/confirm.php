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
		
		$this->load->model('checkout/total');
		
		$totals = $this->model_checkout_total->getTotals();

		// Language is loaded here so it does not interfer with loaded totals.
		$this->load->language('checkout/confirm');

    	$this->document->title = $this->language->get('heading_title'); 
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->session->data['success'] = $this->language->get('text_coupon');
			
			$this->redirect($this->url->https('checkout/confirm'));
		}	
		
		$subtotal = $this->cart->getSubtotal();
		$taxes = $this->cart->getTaxes();
		$total = $this->cart->getSubtotal();
		
		$total_data = array();
		
		// Sub Total
      	$total_data[] = array(
        	'title'      => $this->language->get('text_sub_total'),
	    	'text'       => $this->currency->format($subtotal),
        	'value'      => $subtotal,
			'sort_order' => 0
      	);
		
		$i = 1;
		
		foreach ($totals as $result) {
      		$total_data[] = array(
        		'title'      => $result['title'],
	    		'text'       => $result['text'],
        		'value'      => $result['value'],
				'sort_order' => $i++
      		);
			
			$total += $result['value'];
			
			if (!isset($taxes[$result['tax_class_id']])) {
				$taxes[$result['tax_class_id']] = @$result['tax'];
			} else {
				$taxes[$result['tax_class_id']] += @$result['tax'];
			}
		}
		
		// Taxes
		foreach ($taxes as $key => $value) {
        	$total_data[] = array(
	       		'title'      => $this->tax->getDescription($key) . ':', 
	       		'text'       => $this->currency->format($value),
	       		'value'      => $value,
				'sort_order' => $i++
	    	);
			
			if (!$this->config->get('config_tax')) {
				$total += $value;
			}
		}
		
		// Total
      	$total_data[] = array(
        	'title'      => $this->language->get('text_total'),
	    	'text'       => $this->currency->format($total),
        	'value'      => $total,
			'sort_order' => $i++
      	);
		
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
				'tax'        => $this->tax->getRate($product['tax_class_id']),
      		); 
    	}
		
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
		 
		$data['products'] = $product_data;
		$data['totals'] = $total_data;
		$data['comment'] = @$this->session->data['comment'];
		$data['total'] = $total;
		$data['order_status_id'] = $this->config->get('config_order_status_id');
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
    	$this->data['text_your_comments'] = $this->language->get('text_your_comments');
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
			$this->data['coupon'] = $this->coupon->getCode();
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
        		'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
				'discount'   => ($product['discount'] ? $this->currency->format($this->tax->calculate($product['price'] - $product['discount'], $product['tax_class_id'], $this->config->get('config_tax'))) : NULL),
        		'total'      => $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax'))),
				'href'       => $this->url->http('product/product&product_id=' . $product['product_id'])
      		); 
    	} 
		
		$this->data['totals'] = $total_data;
	
		$this->data['comment'] = $this->session->data['comment'];
    
		$this->id       = 'content';
		$this->template = 'checkout/confirm.tpl';
		$this->layout   = 'module/layout';
		$this->children = array('payment/' . $this->session->data['payment_method']['id']);
		
		$this->render();
  	}
			
	private function validate() {
		if (!$this->coupon->set($this->request->post['coupon'])) {
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