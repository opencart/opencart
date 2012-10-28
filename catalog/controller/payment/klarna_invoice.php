<?php
class ControllerPaymentKlarnaInvoice extends Controller {
	protected function index() {
		$this->language->load('payment/klarna_invoice');
		
		$this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_additional'] = $this->language->get('text_additional');
		$this->data['text_male'] = $this->language->get('text_male');
		$this->data['text_female'] = $this->language->get('text_female');
		$this->data['text_wait'] = $this->language->get('text_wait');
		
		$this->data['entry_gender'] = $this->language->get('entry_gender');
		$this->data['entry_pno'] = $this->language->get('entry_pno');
		$this->data['entry_cellno'] = $this->language->get('entry_cellno');
		$this->data['entry_house_no'] = $this->language->get('entry_house_no');
		$this->data['entry_house_ext'] = $this->language->get('entry_house_ext');

		$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
 		
		if ($order_info) {
 			$this->data['iso_code_2'] = $order_info['payment_iso_code_2'];
		} else {
			$this->data['iso_code_2'] = '';
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/klarna_invoice.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/klarna_invoice.tpl';
		} else {
			$this->template = 'default/template/payment/klarna_invoice.tpl';
		}

		$this->render();
	}
	
	public function send() {
		$json = array();
		
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
			switch ($this->request->post['gender']) {
				case 'M':
					$gender = 1;
					break;
				case 'F':
					$gender = 0;
					break;
			}
						
			// Country language and encoding because Klarna does not work well when language and country are not from the same place.
			// Its completly pointless to convert countries to numbers instead of just using the countries ISO code! What a waste of time for developers having to look up which number equals the country.
			// Same for language and encoding.
			// Encoding should not even be shown tot he developer it should be done at Klarna's end.
			switch (strtoupper($order_info['payment_iso_code_2'])) {
				// Sweden
				case 'SE':
					$country = 209;
					$language = 138;
					$encoding = 2;
					break;
				// Finland
				case 'FI':
					$country = 73;
					$language = 37;
					$encoding = 4;
					break;
				// Denmark
				case 'DK':
					$country = 59;
					$language = 27;
					$encoding = 5;
					break;
				// Norway	
				case 'NO':
					$country = 164;
					$language = 97;
					$encoding = 3;
					break;
				// Germany	
				case 'DE':
					$country = 81;
					$language = 28;
					$encoding = 6;
					break;
				// Netherlands															
				case 'NL':
					$country = 154;
					$language = 101;
					$encoding = 7;
					break;					
			}

			// Billing Address & Shipping address because Klarna does not handle different ones very well.
            $address = array(
				'email'           => $order_info['email'],
				'telno'           => $order_info['telephone'],
				'cellno'          => $this->request->post['cellno'],
				'company'         => $order_info['payment_company'],
				'careof'          => '',
				'fname'           => $order_info['payment_firstname'],
				'lname'           => $order_info['payment_lastname'],
				'street'          => $order_info['payment_address_1'],
				'house_number'    => $this->request->post['house_no'],
				'house_extension' => $this->request->post['house_ext'],
				'zip'             => str_replace(' ', '', $order_info['payment_postcode']),
				'city'            => $order_info['payment_city'],
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
						'artno'    => $product['model'],
						'title'    => $product['name'],
						'price'    => (int)str_replace('.', '', $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), $order_info['currency_code'], false, false)),
						'vat'      => (float)str_replace('.', '', $this->currency->format($this->tax->getTax($product['price'], $product['tax_class_id']), $order_info['currency_code'], false, false)),	
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
					'price'    => (int)str_replace('.', '', $this->currency->format($this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']), $order_info['currency_code'], false, false)),
					'vat'      => (float)str_replace('.', '', $this->currency->format($this->tax->getTax($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']), $order_info['currency_code'], false, false)),	
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
			switch (strtoupper($order_info['currency_code'])) {
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
			
			// Developers have to guess which vars go in which order. Did you hear of key => values?
			$data = array(
			   '4.1',
			   'api:opencart:' . VERSION,
			   $this->request->post['pno'], // pno
			   $gender, // gender
			   '', // reference
			   '', // reference_code
			   (string)$this->session->data['order_id'], // orderid1
			   '', // orderid2
			   $address, // shipping address
			   $address, // billing address
			   $order_info['ip'], // clientip
			   0, // flags
			   $currency, // currency
			   $country, // country
			   $language, // language
			   (int)$this->config->get('klarna_invoice_merchant'), // eid
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

			/*
			From the PHP.net web site:
			
			Warning
			
			This function is EXPERIMENTAL. The behaviour of this function, its name, and surrounding documentation may change without notice in a future release of PHP. This function should be used at your own risk.
	
			Yet Klarna decided to use xmlrpc when no other payment gateway in the world does this!
			*/
			$request = xmlrpc_encode_request('add_invoice', $data);
			
			$header  = 'Host: ' . $url . "\r\n";
			$header .= 'User-Agent: Kreditor PHP Client' . "\r\n";
			$header .= 'Connection: close' . "\r\n";
			$header .= 'Content-Type: text/xml' . "\r\n";
			$header .= 'Content-Length: ' . strlen($request) . "\r\n";
			
			$curl = curl_init('https://' . $url);
			
			curl_setopt($curl, CURLOPT_HEADER, $header);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			curl_setopt($curl, CURLOPT_PORT, 443);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			
			$response = curl_exec($curl);
			
			if (curl_errno($curl)) {
				 curl_error($curl);
			} else {
				curl_close($curl);
				
				preg_match('/<member><name>faultString<\/name><value><string>(.+)<\/string><\/value><\/member>/', $response, $match);
				
				if (isset($match[1])) {
					$json['error'] = utf8_encode($match[1]);
				} else {
					$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('klarna_invoice_order_status_id'));
					
					$json['redirect'] = $this->url->link('checkout/success');
				}		
			}			
		}
		
		$this->response->setOutput(json_encode($json));	
	}
}
?>