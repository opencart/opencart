<?php
namespace Opencart\catalog\controller\api;
/**
 * Class Payment Method
 *
 * @package Opencart\Catalog\Controller\Api
 */
class PaymentMethod extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return array<string, mixed>
	 */
	public function index(): array {
		$this->load->language('api/payment_method');

		$output = [];

		// 1. Validate customer data exists
		if (!isset($this->session->data['customer'])) {
			$output['error'] = $this->language->get('error_customer');
		}

		// 2. Validate cart has products
		if (!$this->cart->hasProducts()) {
			$output['error'] = $this->language->get('error_product');
		}

		// 3. Validate shipping address and method if required
		if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_address'])) {
				$output['error'] = $this->language->get('error_shipping_address');
			}

			if (!isset($this->session->data['shipping_method'])) {
				$output['error'] = $this->language->get('error_shipping_method');
			}
		}

		// 4. Validate payment address if required
		if ($this->config->get('config_checkout_payment_address') && !isset($this->session->data['payment_address'])) {
			$output['error'] = $this->language->get('error_payment_address');
		}

		// 5. Validate payment Method
		$keys = [
			'name',
			'code'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post['payment_method'][$key])) {
				$output['error'] = $this->language->get('error_payment_method');

				break;
			}
		}

		if (!$output) {
			$this->session->data['payment_method'] = [
				'name' => $this->request->post['payment_method']['name'],
				'code' => $this->request->post['payment_method']['code']
			];

			$output['success'] = $this->language->get('text_success');
		}

		return $output;
	}

	/**
	 * Get Payment Methods
	 *
	 * @return array<string, mixed>
	 */
	public function getPaymentMethods(): array {
		$this->load->language('api/payment_method');

		$output = [];

		// 1. Validate customer data exists
		if (!isset($this->session->data['customer'])) {
			$output['error'] = $this->language->get('error_customer');
		}

		// 2. Validate cart has products
		if (!$this->cart->hasProducts()) {
			$output['error'] = $this->language->get('error_product');
		}

		// 3. Validate shipping address and method if required
		if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_address'])) {
				$output['error'] = $this->language->get('error_shipping_address');
			}

			if (!isset($this->session->data['shipping_method'])) {
				$output['error'] = $this->language->get('error_shipping_method');
			}
		}

		// 4. Validate payment address if required
		if ($this->config->get('config_checkout_payment_address') && !isset($this->session->data['payment_address'])) {
			$output['error'] = $this->language->get('error_payment_address');
		}

		if (!$output) {
			if (isset($this->session->data['payment_address'])) {
				$payment_address = $this->session->data['payment_address'];
			} else {
				$payment_address = [];
			}

			$this->load->model('checkout/payment_method');

			$payment_methods = $this->model_checkout_payment_method->getMethods($payment_address);

			if ($payment_methods) {
				$output['payment_methods'] = $payment_methods;
			} else {
				$output['error'] = $this->language->get('error_no_payment');
			}
		}

		return $output;
	}
}
