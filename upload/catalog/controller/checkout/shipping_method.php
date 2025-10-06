<?php
namespace Opencart\Catalog\Controller\Checkout;
/**
 * Class ShippingMethod
 *
 * @package Opencart\Catalog\Controller\Checkout
 */
class ShippingMethod extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('checkout/shipping_method');

		if (isset($this->session->data['shipping_method'])) {
			$data['shipping_method'] = $this->session->data['shipping_method']['name'];
			$data['code'] = $this->session->data['shipping_method']['code'];
		} else {
			$data['shipping_method'] = '';
			$data['code'] = '';
		}

		$data['language'] = $this->config->get('config_language');
		$data['currency'] = $this->session->data['currency'];

		return $this->load->view('checkout/shipping_method', $data);
	}

	/**
	 * Quote
	 *
	 * @return void
	 */
	public function quote(): void {
		$this->load->language('checkout/shipping_method');

		$json = [];

		// Validate cart has products and has stock.
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) || !$this->cart->hasMinimum()) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			// Validate if customer data is set
			if (!isset($this->session->data['customer'])) {
				$json['error'] = $this->language->get('error_customer');
			}

			// Validate if payment address is set if required, in settings
			if ($this->config->get('config_checkout_payment_address') && !isset($this->session->data['payment_address'])) {
				$json['error'] = $this->language->get('error_payment_address');
			}

			// Validate if shipping not required. If not the customer should not have reached this page.
			if ($this->cart->hasShipping() && !isset($this->session->data['shipping_address']['address_id'])) {
				$json['error'] = $this->language->get('error_shipping_address');
			}
		}

		if (!$json) {
			// Shipping Methods
			$this->load->model('checkout/shipping_method');

			$shipping_methods = $this->model_checkout_shipping_method->getMethods($this->session->data['shipping_address']);

			if ($shipping_methods) {
				$json['shipping_methods'] = $this->session->data['shipping_methods'] = $shipping_methods;
			} else {
				$json['error'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact', 'language=' . $this->config->get('config_language')));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('checkout/shipping_method');

		$json = [];

		// Validate cart has products and has stock.
		if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) || !$this->cart->hasMinimum()) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			// Validate if customer is logged in or customer session data is not set
			if (!isset($this->session->data['customer'])) {
				$json['error'] = $this->language->get('error_customer');
			}

			// Validate if payment address is set if required, in settings
			if ($this->config->get('config_checkout_payment_address') && !isset($this->session->data['payment_address'])) {
				$json['error'] = $this->language->get('error_payment_address');
			}

			// Validate if shipping is required. If not, the customer should not have reached this page.
			if ($this->cart->hasShipping() && !isset($this->session->data['shipping_address']['address_id'])) {
				$json['error'] = $this->language->get('error_shipping_address');
			}

			if (isset($this->request->post['shipping_method'])) {
				$shipping = explode('.', $this->request->post['shipping_method']);

				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
					$json['error'] = $this->language->get('error_shipping_method');
				}
			} else {
				$json['error'] = $this->language->get('error_shipping_method');
			}
		}

		if (!$json) {
			$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

			$json['success'] = $this->language->get('text_success');

			// Clear payment methods
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
