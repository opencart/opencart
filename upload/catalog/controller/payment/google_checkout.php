<?php
class ControllerPaymentGoogleCheckout extends Controller {
	public function index() {
		if (!$this->config->get('google_checkout_test')) {
			$this->data['action'] = 'https://checkout.google.com/api/checkout/v2/checkout/Merchant/' . $this->config->get('google_checkout_merchant_id');	
		} else {
			$this->data['action'] = 'https://sandbox.google.com/checkout/api/checkout/v2/checkout/Merchant/' . $this->config->get('google_checkout_merchant_id');
		}
		
		$xml  = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml .= '<checkout-shopping-cart xmlns="http://checkout.google.com/schema/2">';
		$xml .= '	<shopping-cart>';
		$xml .= '		<items>';
		
		$products = $this->cart->getProducts();
		
		foreach ($products as $product) { 
			$option_data = array();
			
			foreach ($product['option'] as $option) {
				$option_data[] = $option['name'] . ': ' . $option['value'];
			}
		
			if ($option_data) {
				$name = $product['name'] . ' ' . implode('; ', $option_data);
			} else {
				$name = $product['name'];
			}
			
			$xml .= '			<item>';
			$xml .= '				<merchant-item-id>' . $product['product_id'] . '</merchant-item-id>';
			$xml .= '				<item-name>' . $name . '</item-name>'; 
			$xml .= '				<item-description>' . substr(strip_tags($product['description']), 0, 299) . '</item-description>';   
			$xml .= '				<unit-price currency="' . $this->currency->getCode() . '">' . $product['price'] . '</unit-price>';
			$xml .= '				<quantity>' . $product['quantity'] . '</quantity>';
			$xml .= '			</item>'; 
		}
		
		$xml .= '		</items>';
		$xml .= '	</shopping-cart>';
		$xml .= '	<checkout-flow-support>';  
		$xml .= '		<merchant-checkout-flow-support>';
		$xml .= '			<merchant-calculations>'; 
		$xml .= '				<merchant-calculations-url>' . HTTPS_SERVER . 'index.php?route=payment/google_checkout/shipping' . '</merchant-calculations-url>';
		$xml .= '			</merchant-calculations>';
		$xml .= '			<shipping-methods>';
		$xml .= '				<merchant-calculated-shipping name="SuperShip International">';
		$xml .= '					<price currency="' . $this->currency->getCode() . '">11.00</price>';
		$xml .= '					<address-filters>';
		$xml .= '						<allowed-areas>';
		$xml .= '							<us-country-area country-area="ALL" />';
		$xml .= '						</allowed-areas>';
		$xml .= '						<allow-us-po-box>false</allow-us-po-box>';
		$xml .= '					</address-filters>';
		$xml .= '				</merchant-calculated-shipping>';
		$xml .= '			</shipping-methods>';
		$xml .= '		</merchant-checkout-flow-support>';

		$xml .= '	</checkout-flow-support>';
		$xml .= '</checkout-shopping-cart>';

		$key = $this->config->get('google_checkout_merchant_key');

		$blocksize = 64;
		$hashfunc = 'sha1';
		
		if (strlen($key) > $blocksize) {
			$key = pack('H*', $hashfunc($key));
		}
		
		$key = str_pad($key, $blocksize, chr(0x00));
		$ipad = str_repeat(chr(0x36), $blocksize);
		$opad = str_repeat(chr(0x5c), $blocksize);
		$hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $xml))));

		$this->data['cart'] = base64_encode($xml);
		$this->data['signature'] = base64_encode($hmac);
		
		if (!$this->config->get('google_checkout_test')) {
			$this->data['button'] = 'http://checkout.google.com/checkout/buttons/checkout.gif?merchant_id=' . $this->config->get('google_checkout_merchant_id') . '&w=180&h=46&style=white&variant=text&loc=en_US';	
		} else {
			$this->data['button'] = 'http://sandbox.google.com/checkout/buttons/checkout.gif?merchant_id=' . $this->config->get('google_checkout_merchant_id') . '&w=180&h=46&style=white&variant=text&loc=en_US';
		}
		
		$this->id = 'google_checkout';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/google_checkout.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/google_checkout.tpl';
		} else {
			$this->template = 'default/template/payment/google_checkout.tpl';
		}	
		
		$this->render();
	}

	public function shipping() {
		ob_start();
		
		print_r($this->request->get);
		print_r($this->request->post);

		$content = ob_get_contents();
		
		ob_end_clean();
		
		$log = new Logger('error.txt');
		$log->write($content);
		
/*
<calculate>
    <addresses>
        <anonymous-address>
            <country-code>US</country-code>
            <city>Mountain View</city>
            <region>CA</region>
            <postal-code>94043</postal-code>
        </anonymous-address>
    </addresses>
    <shipping>
        <method name="FedEx Overnight Shipping"/>
        <method name="UPS Ground"/>
    </shipping>
</calculate>



		$this->load->model('checkout/extension');
		
		$quote_data = array();
		
		$results = $this->model_checkout_extension->getExtensions('shipping');
		
		foreach ($results as $result) {
			$this->load->model('shipping/' . $result['key']);
			
			$quote = $this->{'model_shipping_' . $result['key']}->getQuote(); 

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
*/
		$xml = '<shipping-methods>';
		
		foreach ($quote_data as $shipping) {
			$xml .= '<merchant-calculated-shipping name="UPS Next Day Air">';
			$xml .= '	<price currency="' . $this->currency->getCode() . '">20.00</price>';
			$xml .= '		<address-filters>';
			$xml .= '			<allow-us-po-box>false<allow-us-po-box>';
			$xml .= '		</address-filters>';
			$xml .= '</merchant-calculated-shipping>';
			$xml .= '<merchant-calculated-shipping name="UPS Ground">';
			$xml .= '	<price currency="' . $this->currency->getCode() . '">15.00</price>';
			$xml .= '</merchant-calculated-shipping>';
		}
		
		$xml .= '</shipping-methods>';
		
		$this->response->setOutput($xml);
	}
}
?>