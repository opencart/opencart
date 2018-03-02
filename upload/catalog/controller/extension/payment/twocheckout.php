<?php
class ControllerExtensionPaymentTwoCheckout extends Controller {

	// https://www.2checkout.com/documentation/checkout/inline-checkout

	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data['action'] = 'https://www.2checkout.com/checkout/purchase';

		$data['sid'] = $this->config->get('payment_twocheckout_account');
		//$data['currency_code'] = $order_info['currency_code'];
		$data['currency_code'] = 'USD';
		//$data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['total'] = $this->currency->format($order_info['total'], 'USD', $this->currency->getValue('USD'), false);
		$data['merchant_order_id'] = $this->session->data['order_id'];
		$data['card_holder_name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$data['street_address'] = $order_info['payment_address_1'];
		$data['city'] = $order_info['payment_city'];

		if ($order_info['payment_iso_code_2'] == 'US' || $order_info['payment_iso_code_2'] == 'CA') {
			$data['state'] = $order_info['payment_zone'];
		} else {
			$data['state'] = 'XX';
		}

		$data['zip'] = $order_info['payment_postcode'];
		$data['country'] = $order_info['payment_country'];
		$data['email'] = $order_info['email'];
		$data['phone'] = $order_info['telephone'];

		$data['shipping'] = (int)$this->cart->hasShipping();

		if ($data['shipping']) {
			$data['ship_street_address'] = $order_info['shipping_address_1'];
			$data['ship_city'] = $order_info['shipping_city'];
			$data['ship_state'] = $order_info['shipping_zone'];
			$data['ship_zip'] = $order_info['shipping_postcode'];
			$data['ship_country'] = $order_info['shipping_country'];
		} else {
			$data['ship_street_address'] = $order_info['payment_address_1'];
			$data['ship_city'] = $order_info['payment_city'];
			$data['ship_state'] = $order_info['payment_zone'];
			$data['ship_zip'] = $order_info['payment_postcode'];
			$data['ship_country'] = $order_info['payment_country'];
		}

		$data['products'] = array();

		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$data['products'][] = array(
				'product_id'  => $product['product_id'],
				'name'        => $product['name'],
				'description' => $product['name'],
				'quantity'    => $product['quantity'],
				'price'       => $this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value'], false),
				'shipping'    => !empty($product['shipping']) ? 'Y' : 'N'
			);
		}

		if ($this->config->get('payment_twocheckout_test')) {
			$data['demo'] = 'Y';
		} else {
			$data['demo'] = '';
		}

		if ($this->config->get('payment_twocheckout_display')) {
			$data['display'] = 'Y';
		} else {
			$data['display'] = '';
		}

		$data['lang'] = $this->session->data['language'];

		$data['return_url'] = $this->url->link('extension/payment/twocheckout/callback', '', true);

		return $this->load->view('extension/payment/twocheckout', $data);
	}

	public function callback() {
		$this->load->model('checkout/order');

		$redirect = $this->url->link('checkout/checkout');

		$order_info = array();

		if (empty($this->request->post)) {
			echo 'Redirecting...<br><br> If you see this message more than one minute, something went wrong with your payment, contact website administrator.';
			return false;
		}

		if (!empty($this->request->post['merchant_order_id'])) {
			$order_info = $this->model_checkout_order->getOrder($this->request->post['merchant_order_id']);
		}

		if (empty($order_info)) {
			$this->log->write('2CO error: wrong or empty merchant_order_id');
			echo 'Wrong parameters! Contact website administrator!';
			return false;
		} else {
			$redirect = $this->url->link('checkout/success');
		}

		if (!$this->config->get('payment_twocheckout_test')) {
			$order_number = $this->request->post['order_number'];
		} else {
			$order_number = '1';
		}

		if (strtoupper(md5($this->config->get('payment_twocheckout_secret') . $this->config->get('payment_twocheckout_account') . $order_number . $this->request->post['total'])) == $this->request->post['key']) {
			$comment = '';

			ksort($this->request->post);

			foreach ($this->request->post as $param => $value) {
				$comment .= $param . ': ' . print_r($value, true) . "\n";
			}

			$comment = trim($comment);

			if ($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) == $this->request->post['total']) {
				$this->model_checkout_order->addOrderHistory($this->request->post['merchant_order_id'], $this->config->get('payment_twocheckout_order_status_id'), $comment, false);
			} else {
				$this->model_checkout_order->addOrderHistory($this->request->post['merchant_order_id'], $this->config->get('config_order_status_id'), $comment, false);// Ugh. Some one've faked the sum. What should we do? Probably drop a mail to the shop owner?
				$this->log->write('2CO: Total value is different from order total' . $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) . ' => ' . $this->request->post['total']);
			}

			//exit();
		} else {
			echo 'The response from 2checkout.com can\'t be parsed. Contact site administrator, please!';
			return false;
		}

			// We can't use $this->response->redirect() here, because of 2CO behavior. It fetches this page
			// on behalf of the user and thus user (and his browser) see this as located at 2checkout.com
			// domain. So user's cookies are not here and he will see empty basket and probably other
			// weird things.

		echo '<html>' . "\n";
		echo '<head>' . "\n";
		echo '  <meta http-equiv="Refresh" content="0; url=' . $redirect . '">' . "\n";
		echo '</head>' . "\n";
		echo '<body>' . "\n";
		echo '  <p>Please follow <a href="' . $redirect . '">link</a>!</p>' . "\n";
		echo '</body>' . "\n";
		echo '</html>' . "\n";
	}
}