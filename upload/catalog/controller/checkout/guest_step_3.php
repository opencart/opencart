<?php 
class ControllerCheckoutGuestStep3 extends Controller {
	private $error = array();
	
	public function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['coupon']) && $this->validateCoupon()) {
			$this->session->data['coupon'] = $this->request->post['coupon'];

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/guest_step_3');
		}
		
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/cart');
    	}
		
		if ($this->customer->isLogged()) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/shipping');
    	} 

		if (!isset($this->session->data['guest'])) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/guest_step_1');
    	} 

    	if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_method'])) {
	  			$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/guest_step_2');
    		}
		} else {
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}
		
		if (!isset($this->session->data['payment_method'])) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/guest_step_2');
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
		
		$data = array();
		
		$data['store_id'] = $this->config->get('config_store_id');
		$data['store_name'] = $this->config->get('config_name');
		$data['store_url'] = $this->config->get('config_url');
		$data['customer_id'] = 0;
		$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		$data['firstname'] = $this->session->data['guest']['firstname'];
		$data['lastname'] = $this->session->data['guest']['lastname'];
		$data['email'] = $this->session->data['guest']['email'];
		$data['telephone'] = $this->session->data['guest']['telephone'];
		$data['fax'] = $this->session->data['guest']['fax'];
		
		if ($this->cart->hasShipping()) {
			if (isset($this->session->data['guest']['shipping'])) {
				$data['shipping_firstname'] = $this->session->data['guest']['shipping']['firstname'];
				$data['shipping_lastname'] = $this->session->data['guest']['shipping']['lastname'];	
				$data['shipping_company'] = $this->session->data['guest']['shipping']['company'];	
				$data['shipping_address_1'] = $this->session->data['guest']['shipping']['address_1'];
				$data['shipping_address_2'] = $this->session->data['guest']['shipping']['address_2'];
				$data['shipping_city'] = $this->session->data['guest']['shipping']['city'];
				$data['shipping_postcode'] = $this->session->data['guest']['shipping']['postcode'];
				$data['shipping_zone'] = $this->session->data['guest']['shipping']['zone'];
				$data['shipping_zone_id'] = $this->session->data['guest']['shipping']['zone_id'];
				$data['shipping_country'] = $this->session->data['guest']['shipping']['country'];
				$data['shipping_country_id'] = $this->session->data['guest']['shipping']['country_id'];
				$data['shipping_address_format'] = $this->session->data['guest']['shipping']['address_format'];
			} else {
				$data['shipping_firstname'] = $this->session->data['guest']['firstname'];
				$data['shipping_lastname'] = $this->session->data['guest']['lastname'];	
				$data['shipping_company'] = $this->session->data['guest']['company'];	
				$data['shipping_address_1'] = $this->session->data['guest']['address_1'];
				$data['shipping_address_2'] = $this->session->data['guest']['address_2'];
				$data['shipping_city'] = $this->session->data['guest']['city'];
				$data['shipping_postcode'] = $this->session->data['guest']['postcode'];
				$data['shipping_zone'] = $this->session->data['guest']['zone'];
				$data['shipping_zone_id'] = $this->session->data['guest']['zone_id'];
				$data['shipping_country'] = $this->session->data['guest']['country'];
				$data['shipping_country_id'] = $this->session->data['guest']['country_id'];
				$data['shipping_address_format'] = $this->session->data['guest']['address_format'];
			}
			if (isset($this->session->data['shipping_method']['title'])) {
				$data['shipping_method'] = $this->session->data['shipping_method']['title'];
			} else {
				$data['shipping_method'] = '';
			}
		} else {
			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';	
			$data['shipping_company'] = '';	
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_zone'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_country'] = '';
			$data['shipping_country_id'] = '';
			$data['shipping_address_format'] = '';
			$data['shipping_method'] = '';
		}
		
		$data['payment_firstname'] = $this->session->data['guest']['firstname'];
		$data['payment_lastname'] = $this->session->data['guest']['lastname'];	
		$data['payment_company'] = $this->session->data['guest']['company'];	
		$data['payment_address_1'] = $this->session->data['guest']['address_1'];
		$data['payment_address_2'] = $this->session->data['guest']['address_2'];
		$data['payment_city'] = $this->session->data['guest']['city'];
		$data['payment_postcode'] = $this->session->data['guest']['postcode'];
		$data['payment_zone'] = $this->session->data['guest']['zone'];
		$data['payment_zone_id'] = $this->session->data['guest']['zone_id'];
		$data['payment_country'] = $this->session->data['guest']['country'];
		$data['payment_country_id'] = $this->session->data['guest']['country_id'];
		$data['payment_address_format'] = $this->session->data['guest']['address_format'];
	
		if (isset($this->session->data['payment_method']['title'])) {
			$data['payment_method'] = $this->session->data['payment_method']['title'];
		} else {
			$data['payment_method'] = '';
		}
		
		$product_data = array();
	
		foreach ($this->cart->getProducts() as $product) {
      		$option_data = array();

      		foreach ($product['option'] as $option) {
        		$option_data[] = array(
					'product_option_value_id' => $option['product_option_value_id'],			   
          			'name'                    => $option['name'],
          			'value'                   => $option['value'],
		  			'prefix'                  => $option['prefix']
        		);
      		}
 
      		$product_data[] = array(
        		'product_id' => $product['product_id'],
				'name'       => $product['name'],
        		'model'      => $product['model'],
        		'option'     => $option_data,
				'download'   => $product['download'],
				'quantity'   => $product['quantity'], 
				'subtract'   => $product['subtract'],
				'price'      => $product['price'],
        		'total'      => $product['total'],
				'tax'        => $this->tax->getRate($product['tax_class_id'])
      		); 
    	}
		
		$data['products'] = $product_data;
		$data['totals'] = $total_data;
		$data['comment'] = $this->session->data['comment'];
		$data['total'] = $total;
		$data['language_id'] = $this->config->get('config_language_id');
		$data['currency_id'] = $this->currency->getId();
		$data['currency'] = $this->currency->getCode();
		$data['value'] = $this->currency->getValue($this->currency->getCode());
		
		if (isset($this->session->data['coupon'])) {
			$this->load->model('checkout/coupon');
		
			$coupon = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);
			
			if ($coupon) {
				$data['coupon_id'] = $coupon['coupon_id'];
			} else {
				$data['coupon_id'] = 0;
			}
		} else {
			$data['coupon_id'] = 0;
		}
		
		$data['ip'] = $this->request->server['REMOTE_ADDR'];
		
		$this->load->model('checkout/order');
		
		$this->session->data['order_id'] = $this->model_checkout_order->create($data);

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
        	'href'      => HTTPS_SERVER . 'index.php?route=checkout/guest_step_1',
        	'text'      => $this->language->get('text_guest_step_1'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=checkout/guest_step_2',
        	'text'      => $this->language->get('text_guest_step_2'),
        	'separator' => $this->language->get('text_separator')
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=checkout/guest_step_3',
        	'text'      => $this->language->get('text_confirm'),
        	'separator' => $this->language->get('text_separator')
      	);
						 	
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
    	$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
    	$this->data['text_payment_address'] = $this->language->get('text_payment_address');
    	$this->data['text_payment_method'] = $this->language->get('text_payment_method');
    	$this->data['text_comment'] = $this->language->get('text_comment');
		$this->data['text_coupon'] = $this->language->get('text_coupon');
    	$this->data['text_change'] = $this->language->get('text_change');
		
		$this->data['button_coupon'] = $this->language->get('button_coupon');

		$this->data['entry_coupon'] = $this->language->get('entry_coupon');
		
		$this->data['column_product'] = $this->language->get('column_product');
    	$this->data['column_model'] = $this->language->get('column_model');
    	$this->data['column_quantity'] = $this->language->get('column_quantity');
    	$this->data['column_price'] = $this->language->get('column_price');
    	$this->data['column_total'] = $this->language->get('column_total');
		
		if (isset($this->error['warning'])) {
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
		
		if ($this->cart->hasShipping()) {
			if (isset($this->session->data['guest']['shipping'])) {
				$shipping_address = $this->session->data['guest']['shipping'];
			} else {
				$shipping_address = $this->session->data['guest'];
			}
			
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
			
			$this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		} else {
			$this->data['shipping_address'] = '';
		}
		
		if (isset($this->session->data['shipping_method']['title'])) {
			$this->data['shipping_method'] = $this->session->data['shipping_method']['title'];
		} else {
			$this->data['shipping_method'] = '';
		}
		
    	$this->data['checkout_shipping'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';

    	$this->data['checkout_shipping_address'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_1';
		
		$payment_address = $this->session->data['guest'];
    	
		if ($payment_address) {
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
			
			$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		} else {
			$this->data['payment_address'] = '';
		}

		if (isset($this->session->data['payment_method']['title'])) {
			$this->data['payment_method'] = $this->session->data['payment_method']['title'];
		} else {
			$this->data['payment_method'] = '';
		}
	
    	$this->data['checkout_payment'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';

    	$this->data['checkout_payment_address'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_1';
		
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
				'subtract'   => $product['subtract'],
				'tax'        => $this->tax->getRate($product['tax_class_id']),
        		'price'      => $this->currency->format($product['price']),
        		'total'      => $this->currency->format($product['total']),
				'href'       => HTTP_SERVER . 'index.php?route=product/product&product_id=' . $product['product_id']
      		); 
    	} 
		
		$this->data['totals'] = $total_data;
	
		$this->data['comment'] = nl2br($this->session->data['comment']);
    
		$this->data['coupon_status'] = $this->config->get('coupon_status');
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_3';
		
		if (isset($this->request->post['coupon'])) {
			$this->data['coupon'] = $this->request->post['coupon'];
		} elseif (isset($this->session->data['coupon'])) {
			$this->data['coupon'] = $this->session->data['coupon'];
		} else {
			$this->data['coupon'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/confirm.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/confirm.tpl';
		} else {
			$this->template = 'default/template/checkout/confirm.tpl';
		}

		$this->children = array(
			'common/column_right',
			'common/footer',
			'common/column_left',
			'common/header',
			'payment/' . $this->session->data['payment_method']['id']
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validateCoupon() {

  		$this->load->model('checkout/coupon');

		$this->language->load('checkout/confirm');

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