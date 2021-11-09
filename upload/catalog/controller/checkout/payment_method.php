<?php
namespace Opencart\Catalog\Controller\Checkout;
class PaymentMethod extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('checkout/checkout');

		$data['language'] = $this->config->get('config_language');

		if (isset($this->session->data['payment_methods'])) {
			$data['payment_methods'] = $this->session->data['payment_methods'];
		} elseif (isset($this->session->data['payment_address'])) {
			// Shipping Methods
			$this->load->model('checkout/payment_method');

			$data['payment_methods'] = $this->model_checkout_payment_method->getMethods($this->session->data['payment_address']);




		} else {
			$data['payment_methods'] = [];
		}

		if (isset($this->session->data['payment_method'])) {
			$data['code'] = $this->session->data['payment_method'];
		} else {
			$data['code'] = '';
		}

		return $this->load->view('checkout/payment_method', $data);
	}

	public function getMethods(): void {
		$this->load->language('checkout/checkout');

		$json = [];

		if (!isset($this->session->data['payment_address'])) {
			$json['error'] = $this->language->get('error_payment_address');
		}

		if ($this->config->get('config_checkout_address') && isset($this->session->data['payment_address'])) {
			$payment_address = $this->session->data['payment_address'];
		} else {
			$payment_address = [
				'address_id'     => 0,
				'firstname'      => '',
				'lastname'       => '',
				'company'        => '',
				'address_1'      => '',
				'address_2'      => '',
				'city'           => '',
				'postcode'       => '',
				'zone_id'        => 0,
				'zone'           => '',
				'zone_code'      => '',
				'country_id'     => 0,
				'country'        => '',
				'iso_code_2'     => '',
				'iso_code_3'     => '',
				'address_format' => '',
				'custom_field'   => []
			];
		}

		// Payment Methods
		$this->load->model('checkout/payment_method');

		$payment_methods = $this->model_checkout_payment_method->getMethods($payment_address);

		if (!$payment_methods) {
			$json['error'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact', 'language=' . $this->config->get('config_language')));
		}

		if ($payment_methods) {
			// Store payment methods in session
			$this->session->data['payment_methods'] = $payment_methods;

			$json['payment_methods'] = $payment_methods;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function save(): void {
		$this->load->language('checkout/checkout');

		$json = [];

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);

				break;
			}
		}

		// Validate if shipping method has been set.
		if (!isset($this->session->data['customer'])) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate if payment address is set if required in settings
		if ($this->config->get('config_checkout_address') && !isset($this->session->data['payment_address'])) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate if shipping address or shipping method has been set.
		if ($this->cart->hasShipping()) {

			if (!isset($this->session->data['shipping_address']) || !isset($this->session->data['shipping_method'])) {
				$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
			}

		} else {
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		if (!$json) {

			if (!isset($this->request->get['payment_method']) || !isset($this->session->data['payment_methods'][$this->request->get['payment_method']])) {
				$json['error'] = $this->language->get('error_payment');
			}

		}

		if (!$json) {
			$this->session->data['payment_method'] = $this->request->get['payment_method'];

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
