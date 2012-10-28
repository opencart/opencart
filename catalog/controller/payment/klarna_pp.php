<?php
class ControllerPaymentKlarnaPP extends Controller {
	protected function index() {
		$this->language->load('payment/klarna_invoice');
		
		$this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_additional'] = $this->language->get('text_additional');		
		$this->data['text_male'] = $this->language->get('text_male');
		$this->data['text_female'] = $this->language->get('text_female');
		$this->data['text_wait'] = $this->language->get('text_wait');
		
		$this->data['entry_gender'] = $this->language->get('entry_gender');
		$this->data['entry_dob'] = $this->language->get('entry_dob');
		$this->data['entry_house_no'] = $this->language->get('entry_house_no');
		$this->data['entry_house_ext'] = $this->language->get('entry_house_ext');

		    	
		$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
 
		if ($order_info) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/klarna_invoice.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/klarna_invoice.tpl';
			} else {
				$this->template = 'default/template/payment/klarna_invoice.tpl';
			}
	
			$this->render();
		}
	}
	
	public function send() {
		$this->load->model('checkout/order');
				
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		if ($order_info) {	
			// Server
			switch ($this->config->get('klarna_invoice_server')) {
				case 'live':
					$url = 'payment.klarna.com';
					break;
				case 'beta':
					$url = 'payment-beta.klarna.com';
					break;						
			}
			
			// Gender
			switch ($order_info['gender']) {
				case 'M':
					$gender = 1;
					break;
				case 'F':
					$gender = 0;
					break;
			}
						
			// Payment Country
			switch ($order_info['payment_iso_code_2']) {
				// Sweden
				case 'SE':
					$country = 209;
					break;
				// Finland
				case 'FI':
					$country = 73;
					break;
				// Denmark
				case 'DK':
					$country = 59;
					break;
				// Norway	
				case 'NO':
					$country = 164;
					break;
				// Germany	
				case 'DE':
					$country = 81;
					break;
				// Netherlands															
				case 'NL':
					$country = 154;
					break;					
			}

			// Billing Address
            $billing = array(
				'email'           => $order_info['email'],
				'telno'           => $order_info['telephone'],
				'cellno'          => '',
				'careof'          => '',
				'company'         => $order_info['payment_company'],
				'fname'           => $order_info['payment_firstname'],
				'lname'           => $order_info['payment_firstname'],
				'street'          => $order_info['payment_address_1'],
				'house_number'    => $this->request->post['house_no'],
				'house_extension' => $this->request->post['house_extension'],
				'zip'             => str_replace(' ', '', $order_info['payment_postcode']),
				'city'            => $order_info['payment_city'],
				'country'         => $country,
			);
			
			// Shipping Country
			switch ($order_info['shipping_iso_code_2']) {
				// Sweden
				case 'SE':
					$country = 209;
					break;
				// Finland
				case 'FI':
					$country = 73;
					break;
				// Denmark
				case 'DK':
					$country = 59;
					break;
				// Norway	
				case 'NO':
					$country = 164;
					break;
				// Germany	
				case 'DE':
					$country = 81;
					break;
				// Netherlands															
				case 'NL':
					$country = 154;
					break;					
			}
				
			// Shipping Address
			$shipping = array(
				'email'           => $order_info['email'],
				'telno'           => $order_info['telephone'],
				'cellno'          => '',
				'careof'          => '',
				'company'         => $order_info['shipping_company'],
				'fname'           => $order_info['shipping_firstname'],
				'lname'           => $order_info['shipping_lastname'],
				'street'          => $order_info['shipping_address_1'],
				'house_number'    => $this->request->post['house_no'],
				'house_extension' => '',
				'zip'             => str_replace(' ', '', $order_info['shipping_postcode']),
				'city'            => $order_info['shipping_city'],
				'country'         => $country,
			);
	
			// IS_SHIPMENT = 8;
			// IS_HANDLING = 16;
			// INC_VAT = 32;			
			
			// Products
			$goodslist = array();
			
			foreach ($this->cart->getProducts() as $product) {
				$goodslist[] = array(
					'qty'   => $product['quantity'],
					'goods' => array(
						'artNo'    => $product['model'],
						'title'    => $product['name'],
						'price'    => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), $order_info['currency_code'], false, false),
						'vat'      => $this->currency->format($this->tax->getTax($product['price'], $product['tax_class_id']), $order_info['currency_code'], false, false),	
						'discount' => 0,   
						'flags'    => 32
					)	
				);
			}

			// Shipping
			$goodslist[] = array(
				'qty'   => 1,
				'goods' => array(
					'artNo'    => $order_info['shipping_code'],
					'title'    => $order_info['shipping_method'],
					'price'    => $this->currency->format($this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']), $order_info['currency_code'], false, false),
					'vat'      => $this->currency->format($this->tax->getTax($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']), $order_info['currency_code'], false, false),	
					'discount' => 0,   
					'flags'    => 8 + 32
				)	
			);
				
			// Digest
			$digest = '';
			
			foreach ($goodslist as $goods) {
			   $digest .= $goods['goods']['title'] . ':';
			}
			
			$digest = base64_encode(pack('H*', hash('sha512', $digest . $this->config->get('klarna_invoice_secret'))));

			// Currency
			switch ($order_info['currency_code']) {
				// Swedish krona
				case 'SEK':
					$currency = 0;
					break;
				// Norwegian krona	
				case 'NOK':
					$currency = 1;
					break;	
				// Euro					
				case 'EUR':
					$currency = 2;
					break;
				// Danish krona		
				case 'DKK':
					$currency = 3;
					break;
			}	
						
			// Language
			switch (strtolower($order_info['language_code'])) {
				// Swedish
				case 'sv':
					$language = 138;
					break;
				// Norwegian	
				case 'nb':
					$language = 97;
					break;	
				// Finnish					
				case 'FI':
					$language = 37;
					break;
				// Danish		
				case 'DK':
					$language = 27;
					break;
				// German		
				case 'DE':
					$language = 28;
					break;	
				// Dutch																
				case 'NL':
					$language = 101;
					break;					
			}			
			
			// Encoding
			switch (strtolower($order_info['encoding'])) {
				// Sweden
				case 'SEK':
					$encoding = 2;
					break;
				// Norway	
				case 'NOK':
					$encoding = 3;
					break;	
				// Finland					
				case 'EUR':
					$encoding = 4;
					break;
				// Denmark	
				case 'DKK':
					$encoding = 5;
					break;
				// Germany		
				case 'DKK':
					$encoding = 6;
					break;	
				// Netherlands		
				case 'DKK':
					$encoding = 7;
					break;										
			}	
						
			$data = array(
			   '4.1',
			   'api:opencart:' . VERSION,
			   '07071960', // pno
			   $gender, // gender
			   '', // reference
			   '', // reference_code
			   $this->session->data['order_id'], // orderid1
			   '', // orderid2
			   $shipping, // shipping
			   $billing, // billing
			   $order_info['ip'], // clientip
			   0, // flags
			   $currency, // currency
			   $country, // country
			   $language, // language
			   $this->config->get('klarna_invoice_merchant'), // eid
			   $digest, // digest
			   $encoding, // encoding
			   -1, // pclass
			   $goodslist, // goodslist
			   $order_info['comment'], // comment
			   array('delay_adjust' => 1),
			   array(),
			   array(),
			   array(),
			   array(),
			   array()
			);

			$request = xmlrpc_encode_request('add_invoice', $data);

			$header  = 'POST / HTTP/1.0' . "\r\n";
			$header .= 'User-Agent: Kreditor PHP Client' . "\r\n";
			$header .= 'Host: ' . $url . "\n";
			$header .= 'Connection: close' . "\r\n";
			$header .= 'Content-Type: text/xml' . "\r\n";
			$header .= 'Content-Length: ' . strlen($request) . "\r\n";
			
			$curl = curl_init('https://' . $url);
			
			curl_setopt($curl, CURLOPT_PORT, 443);
			curl_setopt($curl, CURLOPT_HEADER, $header);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_VERBOSE, true);
			
			$response = curl_exec($curl);
			
			if (curl_errno($curl)) {
				echo curl_error($curl);
			} else {
				curl_close($curl);
			
				$response = xmlrpc_decode($response);
				
				print_r($response);
			}			
							
			//$this->model_checkout_order->confirm($order_id, $order_status_id);
		}	
	}
}
?>