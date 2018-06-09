<?php
// Nochex via form will work for both simple "Seller" account and "Merchant" account holders
// Nochex via APC maybe only avaiable to "Merchant" account holders only - site docs a bit vague on this point
class ControllerExtensionPaymentNochex extends Controller {
	public function index() {
		$this->load->language('extension/payment/nochex');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$data['action'] = 'https://secure.nochex.com/';

		// Nochex minimum requirements
		// The merchant ID is usually your Nochex registered email address but can be altered for "Merchant" accounts see below
		if ($this->config->get('payment_nochex_email') != $this->config->get('payment_nochex_merchant')) {
			// This MUST be changed on your Nochex account!!!!
			$data['merchant_id'] = $this->config->get('payment_nochex_merchant');
		} else {
			$data['merchant_id'] = $this->config->get('payment_nochex_email');
		}

		$data['amount'] = $this->currency->format($order_info['total'], 'GBP', false, false);
		$data['order_id'] = $this->session->data['order_id'];
		$data['description'] = $this->config->get('config_name');

		$data['billing_fullname'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];

		if ($order_info['payment_address_2']) {
			$data['billing_address']  = $order_info['payment_address_1'] . "\r\n" . $order_info['payment_address_2'] . "\r\n" . $order_info['payment_city'] . "\r\n" . $order_info['payment_zone'] . "\r\n";
		} else {
			$data['billing_address']  = $order_info['payment_address_1'] . "\r\n" . $order_info['payment_city'] . "\r\n" . $order_info['payment_zone'] . "\r\n";
		}

		$data['billing_postcode'] = $order_info['payment_postcode'];

		if ($this->cart->hasShipping()) {
			$data['delivery_fullname'] = $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'];

			if ($order_info['shipping_address_2']) {
				$data['delivery_address'] = $order_info['shipping_address_1'] . "\r\n" . $order_info['shipping_address_2'] . "\r\n" . $order_info['shipping_city'] . "\r\n" . $order_info['shipping_zone'] . "\r\n";
			} else {
				$data['delivery_address'] = $order_info['shipping_address_1'] . "\r\n" . $order_info['shipping_city'] . "\r\n" . $order_info['shipping_zone'] . "\r\n";
			}

			$data['delivery_postcode'] = $order_info['shipping_postcode'];
		} else {
			$data['delivery_fullname'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];

			if ($order_info['payment_address_2']) {
				$data['delivery_address'] = $order_info['payment_address_1'] . "\r\n" . $order_info['payment_address_2'] . "\r\n" . $order_info['payment_city'] . "\r\n" . $order_info['payment_zone'] . "\r\n";
			} else {
				$data['delivery_address'] = $order_info['shipping_address_1'] . "\r\n" . $order_info['payment_city'] . "\r\n" . $order_info['payment_zone'] . "\r\n";
			}

			$data['delivery_postcode'] = $order_info['payment_postcode'];
		}

		$data['email_address'] = $order_info['email'];
		$data['customer_phone_number']= $order_info['telephone'];
		$data['test'] = $this->config->get('payment_nochex_test');
		$data['success_url'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'));
		$data['cancel_url'] = $this->url->link('checkout/payment', 'language=' . $this->config->get('config_language'));
		$data['declined_url'] = $this->url->link('extension/payment/nochex/callback', 'language=' . $this->config->get('config_language') . '&method=decline');
		$data['callback_url'] = $this->url->link('extension/payment/nochex/callback', 'language=' . $this->config->get('config_language') . '&order=' . $this->session->data['order_id']);

		return $this->load->view('extension/payment/nochex', $data);
	}

	public function callback() {
		$this->load->language('extension/payment/nochex');

		if (isset($this->request->get['method']) && $this->request->get['method'] == 'decline') {
			$this->session->data['error'] = $this->language->get('error_declined');

			$this->response->redirect($this->url->link('checkout/cart', 'language=' . $this->config->get('config_language')));
		}

		if (isset($this->request->post['order_id'])) {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			$this->session->data['error'] = $this->language->get('error_no_order');

			$this->response->redirect($this->url->link('checkout/cart', 'language=' . $this->config->get('config_language')));
		}

		// Fraud Verification Step.
		$request = '';

		foreach ($this->request->post as $key => $value) {
			$request .= '&' . $key . '=' . urlencode(stripslashes($value));
		}

		$curl = curl_init('https://www.nochex.com/nochex.dll/apc/apc');

		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, trim($request, '&'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($curl);

		curl_close($curl);

		if (strcmp($response, 'AUTHORISED') == 0) {
			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_nochex_order_status_id'));
		} else {
			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('config_order_status_id'), 'Auto-Verification step failed. Manually check the transaction.');
		}

		// Since it returned, the customer should see success.
		// It's up to the store owner to manually verify payment.
		$this->response->redirect($this->url->link('checkout/success', 'language=' . $this->config->get('config_language')));
	}
}