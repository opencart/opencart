<?php
namespace Opencart\Catalog\Controller\Checkout;
class PaymentMethod extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('checkout/payment_method');

		$data['language'] = $this->config->get('config_language');

		$status = false;

		if (isset($this->session->data['customer'])) {


		}

		if ($this->cart->hasShipping()) {

			// Validate shipping address
			if (!isset($this->session->data['shipping_address'])) {
				$status = false;

				echo 'shipping_address not set';
			}

			// Validate shipping method
			if (isset($this->session->data['shipping_method']) && isset($this->session->data['shipping_methods'])) {
				$shipping = explode('.', $this->session->data['shipping_method']);

				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
					$status = false;

					echo 'shipping_method not recognised';
				}
			} else {
				$status = false;

				echo 'shipping_method not set';
			}

		}



		if (isset($this->session->data['payment_methods'])) {
			$data['payment_methods'] = $this->session->data['payment_methods'];
		} elseif (isset($this->session->data['customer'])) {




			if ($this->config->get('config_checkout_address') && isset($this->session->data['payment_address'])) {
				$payment_address = $this->session->data['payment_address'];
			} else {
				$payment_address = [
					'address_id' => 0,
					'firstname' => '',
					'lastname' => '',
					'company' => '',
					'address_1' => '',
					'address_2' => '',
					'city' => '',
					'postcode' => '',
					'zone_id' => 0,
					'zone' => '',
					'zone_code' => '',
					'country_id' => 0,
					'country' => '',
					'iso_code_2' => '',
					'iso_code_3' => '',
					'address_format' => '',
					'custom_field' => []
				];
			}

			$this->load->model('checkout/payment_method');

			$data['payment_methods'] = $this->model_checkout_payment_method->getMethods($payment_address);

			$this->session->data['payment_methods'] = $data['payment_methods'];



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
		$this->load->language('checkout/payment_method');

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
		$this->load->language('checkout/payment_method');

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
			// Validate payment method
			if (!isset($this->request->post['payment_method']) || !isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
				$json['error'] = $this->language->get('error_payment');
			}
		}

		if (!$json) {
			$this->session->data['payment_method'] = $this->request->post['payment_method'];

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function comment(): void {


		if (!$json) {
			$this->session->data['comment'] = $this->request->post['comment'];;

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function agree(): void {


	}
}
