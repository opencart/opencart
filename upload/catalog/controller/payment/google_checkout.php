<?php
class ControllerPaymentGoogleCheckout extends Controller {
	public function index() {
		$this->language->load('payment/google_checkout');

		$this->data['button_confirm'] = $this->language->get('button_confirm');

		if (!$this->config->get('google_checkout_test')) {
			$this->data['action'] = 'https://checkout.google.com/api/checkout/v2/checkout/Merchant/' . $this->config->get('google_checkout_merchant_id');	
		} else {
			$this->data['action'] = 'https://sandbox.google.com/checkout/api/checkout/v2/checkout/Merchant/' . $this->config->get('google_checkout_merchant_id');
		}

		$this->data['merchant'] = $this->config->get('google_checkout_merchant_id');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/google_checkout.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/google_checkout.tpl';
		} else {
			$this->template = 'default/template/payment/google_checkout.tpl';
		}	

		$this->render();
	}

	public function send() {
		$this->language->load('payment/google_checkout');

		$json = array();

		if ($this->cart->hasShipping() && !isset($this->session->data['shipping_method'])) {
			$json['error'] = $this->language->get('error_shipping');	
		}

		if (!$json) {
			$xml  = '<?xml version="1.0" encoding="UTF-8"?>';
			$xml .= '<checkout-shopping-cart xmlns="http://checkout.google.com/schema/2">';
			$xml .= '	<shopping-cart>';
		//	$xml .= '   	<merchant-private-data>';
		//	$xml .= '			<order_id>' . $this->session->data['order_id'] . '</order_id>';
		//	$xml .= '   	</merchant-private-data>'; 
			$xml .= '		<items>';

			$products = $this->cart->getProducts();

			foreach ($products as $product) { 
				$xml .= '			<item>';
				$xml .= '				<merchant-item-id>' . $product['key'] . '</merchant-item-id>';

				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = $option['name'] . ': ' . $option['value'];
				}

				if ($option_data) {
					$xml .= '				<item-name>' . $product['name'] . ' ' . implode('; ', $option_data) . '</item-name>'; 
					$xml .= '				<item-description>' . $product['name'] . ' ' . implode('; ', $option_data) . '</item-description>';  
				} else {
					$xml .= '				<item-name>' . $product['name'] . '</item-name>'; 
					$xml .= '				<item-description>' . $product['name'] . '</item-description>';  
				}

				$xml .= '				<unit-price currency="' . $this->currency->getCode() . '">' . $this->currency->format($product['price'], $this->currency->getCode(), false, false) . '</unit-price>';
				$xml .= '				<quantity>' . $product['quantity'] . '</quantity>';
				$xml .= '			</item>'; 
			}

			$xml .= '		</items>';
			$xml .= '	</shopping-cart>';

			if ($this->cart->hasShipping()) {
				$xml .= '	<checkout-flow-support>';  
				$xml .= '		<merchant-checkout-flow-support>';
				$xml .= '			<shipping-methods>';
				$xml .= '				<flat-rate-shipping name="' . $this->session->data['shipping_method']['title'] . '">';
				$xml .= '					<price currency="' . $this->currency->getCode() . '">' . $this->currency->format($this->session->data['shipping_method']['cost'], $this->currency->getCode(), false, false) . '</price>';
				$xml .= '				</flat-rate-shipping>';
				$xml .= '			</shipping-methods>';
				$xml .= '		</merchant-checkout-flow-support>';
				$xml .= '	</checkout-flow-support>';
			}

			if ($this->cart->hasShipping()) {
				$xml .= '	<checkout-flow-support>';  
				$xml .= '		<merchant-checkout-flow-support>';
				$xml .= '			<shipping-methods>';
				$xml .= '				<flat-rate-shipping name="' . $this->session->data['shipping_method']['title'] . '">';
				$xml .= '					<price currency="' . $this->currency->getCode() . '">' . $this->currency->format($this->session->data['shipping_method']['cost'], $this->currency->getCode(), false, false) . '</price>';
				$xml .= '				</flat-rate-shipping>';
				$xml .= '			</shipping-methods>';
				$xml .= '		</merchant-checkout-flow-support>';
				$xml .= '	</checkout-flow-support>';
			}

			$xml .= '</checkout-shopping-cart>';

			$key = $this->config->get('google_checkout_merchant_key');
			$blocksize = 64;
			$hash = 'sha1';

			if (strlen($key) > $blocksize) {
				$key = pack('H*', $hash($key));
			}

			$key = str_pad($key, $blocksize, chr(0x00));
			$ipad = str_repeat(chr(0x36), $blocksize);
			$opad = str_repeat(chr(0x5c), $blocksize);
			$hmac = pack('H*', $hash(($key ^ $opad) . pack('H*', $hash(($key ^ $ipad) . $xml))));

			$json['cart'] = base64_encode($xml);
			$json['signature'] = base64_encode($hmac);	
		}

		$this->response->setOutput(json_encode($json));			
	}

	public function callback() {
		$this->log->write(http_build_query($this->request->get));
		$this->log->write(http_build_query($this->request->post));
		/*	
		order-summary.google-order-number=923823874108605

		&order-summary.total-chargeback-amount.currency=USD
		&order-summary.total-chargeback-amount=0.0
		&order-summary.total-charge-amount=0.0
		&order-summary.total-charge-amount.currency=USD
		&order-summary.total-refund-amount.currency=USD
		&order-summary.total-refund-amount=0.0

		&order-summary.purchase-date=2010-04-21T14%3A09%3A40.000Z
		&order-summary.archived=false

		&order-summary.shopping-cart.items.item-1.item-name=Peanut+Butter
		&order-summary.shopping-cart.items.item-1.item-description=Crunchy+peanut+butter
		&order-summary.shopping-cart.items.item-1.unit-price.currency=USD
		&order-summary.shopping-cart.items.item-1.unit-price=2.95
		&order-summary.shopping-cart.items.item-1.quantity=1
		&order-summary.shopping-cart.items=order-summary.shopping-cart.items.item-1

		&order-summary.order-adjustment.total-tax=0.0
		&order-summary.order-adjustment.total-tax.currency=USD
		&order-summary.order-adjustment.adjustment-total.currency=USD
		&order-summary.order-adjustment.adjustment-total=0.0

		&order-summary.buyer-id=539251754962590
		&order-summary.buyer-marketing-preferences.email-allowed=false
		&order-summary.buyer-shipping-address.email=test%40sandbox.google.com
		&order-summary.buyer-shipping-address.company-name=
		&order-summary.buyer-shipping-address.contact-name=Jan+Test
		&order-summary.buyer-shipping-address.address1=123+Test+St
		&order-summary.buyer-shipping-address.address2=
		&order-summary.buyer-shipping-address.phone=111+222-3333
		&order-summary.buyer-shipping-address.fax=
		&order-summary.buyer-shipping-address.structured-name.first-name=Test%20First%20Name
		&order-summary.buyer-shipping-address.structured-name.last-name=Test%20Last%20Name
		&order-summary.buyer-shipping-address.country-code=US
		&order-summary.buyer-shipping-address.postal-code=10001
		&order-summary.buyer-shipping-address.city=Test+City
		&order-summary.buyer-shipping-address.region=NY

		&order-summary.order-total=2.95
		&order-summary.order-total.currency=USD
		&order-summary.fulfillment-order-state=NEW
		&order-summary.financial-order-state=REVIEWING	







	_type=new-order-notification
  &serial-number=85f54628-538a-44fc-8605-ae62364f6c71
  &google-order-number=841171949013218
  &buyer-shipping-address.contact-name=Will%20Shipp-Toomey
  &buyer-shipping-address.email=willstoomey%40example.com
  &buyer-shipping-address.address1=10%20Example%20Road
  &buyer-shipping-address.city=Sampleville
  &buyer-shipping-address.region=CA
  &buyer-shipping-address.postal-code=94141
  &buyer-shipping-address.country-code=US
  &buyer-shipping-address.phone=5555551234
  &buyer-shipping-address.structured-name.first-name=Will
  &buyer-shipping-address.structured-name.last-name=Shipp-Toomey
  &buyer-billing-address.contact-name=Bill%20Hu
  &buyer-billing-address.email=billhu%40example.com
  &buyer-billing-address.address1=99%20Credit%20Lane
  &buyer-billing-address.city=Mountain%20View
  &buyer-billing-address.region=CA
  &buyer-billing-address.postal-code=94043
  &buyer-billing-address.country-code=US
  &buyer-billing-address.phone=5555557890
  &buyer-billing-address.structured-name.first-name=Bill
  &buyer-billing-address.structured-name.last-name=Hu
  &buyer-id=294873009217523
  &fulfillment-order-state=NEW
  &financial-order-state=REVIEWING
  &shopping-cart.cart-expiration.good-until-date=2007-12-31T23%3A59%3A59-05%3A00
  &shopping-cart.items.item-1.merchant-item-id=GGLAA1453
  &shopping-cart.items.item-1.item-name=Dry%20Food%20Pack
  &shopping-cart.items.item-1.item-description=One%20pack%20of%20nutritious%20dried%emergency%20food.
  &shopping-cart.items.item-1.quantity=1
  &shopping-cart.items.item-1.tax-table-selector=food
  &shopping-cart.items.item-1.unit-price=4.99
  &shopping-cart.items.item-1.unit-price.currency=USD
  &shopping-cart.items.item-2.merchant-item-id=GGLAA1453
  &shopping-cart.items.item-2.item-name=Megasound%202GB%20MP3%20Player
  &shopping-cart.items.item-2.item-description=Portable%20player%20holds%20500%20songs.
  &shopping-cart.items.item-2.quantity=1
  &shopping-cart.items.item-2.tax-table-selector=food
  &shopping-cart.items.item-2.unit-price=179.99
  &shopping-cart.items.item-2.unit-price.currency=USD
  &shopping-cart.items.item-2.merchant-private-item-data=merchant-product-id%3D1234567890
  &order-adjustment.total-tax=11.05
  &order-adjustment.total-tax.currency=USD
  &order-adjustment.shipping.flat-rate-shipping-adjustment.shipping-name=SuperShip
  &order-adjustment.shipping.flat-rate-shipping-adjustment.shipping-cost=9.95
  &order-adjustment.shipping.flat-rate-shipping-adjustment.shipping-cost.currency=USD
  &order-total=190.98
  &order-total.currency=USD
  &buyer-marketing-preferences.email-allowed=false
  ×tamp=2007-03-19T15%3A06%3A26.051Z
  &order-summary...
  ... [order-summary parameters]	


		*/	
	}
}
?>