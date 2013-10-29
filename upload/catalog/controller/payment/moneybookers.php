<?php
class ControllerPaymentMoneybookers extends Controller {
	protected function index() {
		$this->load->model('checkout/order');

		$this->language->load('payment/moneybookers');

		$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['action'] = 'https://www.moneybookers.com/app/payment.pl?p=OpenCart';

		$this->data['pay_to_email'] = $this->config->get('moneybookers_email');
		$this->data['platform'] = '31974336';
		$this->data['description'] = $this->config->get('config_name');
		$this->data['transaction_id'] = $this->session->data['order_id'];
		$this->data['return_url'] = $this->url->link('checkout/success');
		$this->data['cancel_url'] = $this->url->link('checkout/checkout', '', 'SSL');
		$this->data['status_url'] = $this->url->link('payment/moneybookers/callback');
		$this->data['language'] = $this->session->data['language'];		
		$this->data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->data['pay_from_email'] = $order_info['email'];
		$this->data['firstname'] = $order_info['payment_firstname'];
		$this->data['lastname'] = $order_info['payment_lastname'];
		$this->data['address'] = $order_info['payment_address_1'];
		$this->data['address2'] = $order_info['payment_address_2'];
		$this->data['phone_number'] = $order_info['telephone'];
		$this->data['postal_code'] = $order_info['payment_postcode'];
		$this->data['city'] = $order_info['payment_city'];
		$this->data['state'] = $order_info['payment_zone'];
		$this->data['country'] = $order_info['payment_iso_code_3'];
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$this->data['currency'] = $order_info['currency_code'];

		$products = '';

		foreach ($this->cart->getProducts() as $product) {
			$products .= $product['quantity'] . ' x ' . $product['name'] . ', ';
		}

		$this->data['detail1_text'] = $products;

		$this->data['order_id'] = $this->session->data['order_id'];

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/moneybookers.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/moneybookers.tpl';
		} else {
			$this->template = 'default/template/payment/moneybookers.tpl';
		}	

		$this->render();
	}

	public function callback() {
		if (isset($this->request->post['order_id'])) {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);

		if ($order_info) {
			$this->model_checkout_order->confirm($order_id, $this->config->get('config_order_status_id'));

			$verified = true;

			// md5sig validation
			if ($this->config->get('moneybookers_secret')) {
				$hash  = $this->request->post['merchant_id'];
				$hash .= $this->request->post['transaction_id'];
				$hash .= strtoupper(md5($this->config->get('moneybookers_secret')));
				$hash .= $this->request->post['mb_amount'];
				$hash .= $this->request->post['mb_currency'];
				$hash .= $this->request->post['status'];

				$md5hash = strtoupper(md5($hash));
				$md5sig = $this->request->post['md5sig'];

				if ($md5hash != $md5sig) {
					$verified = false;
				}
			}

			if ($verified) {
				switch($this->request->post['status']) {
					case '2':
						$this->model_checkout_order->update($order_id, $this->config->get('moneybookers_order_status_id'), '', true);
						break;
					case '0':
						$this->model_checkout_order->update($order_id, $this->config->get('moneybookers_pending_status_id'), '', true);
						break;
					case '-1':
						$this->model_checkout_order->update($order_id, $this->config->get('moneybookers_canceled_status_id'), '', true);
						break;
					case '-2':
						$this->model_checkout_order->update($order_id, $this->config->get('moneybookers_failed_status_id'), '', true);
						break;
					case '-3':
						$this->model_checkout_order->update($order_id, $this->config->get('moneybookers_chargeback_status_id'), '', true);
						break;
				}
			} else {
				$this->log->write('md5sig returned (' + $md5sig + ') does not match generated (' + $md5hash + '). Verify Manually. Current order state: ' . $this->config->get('config_order_status_id'));
			}
		}
	}
}
?>