<?php
namespace Opencart\Catalog\Controller\Checkout;
class ShippingMethod extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('checkout/shipping_method');

		$data['language'] = $this->config->get('config_language');

		$status = true;

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$status = false;
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			if (!$product['minimum']) {
				$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);

				break;
			}
		}

		// Validate if customer session data is set
		if (!isset($this->session->data['customer'])) {
			$status = false;
		}

		// Validate if payment address is set if required in settings
		if ($this->config->get('config_checkout_address') && !isset($this->session->data['payment_address'])) {
			$status = false;
		}

		// Validate if shipping not required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping() || !isset($this->session->data['shipping_address'])) {
			$status = false;
		}

		$data['shipping_methods'] = [];

		if ($status) {
			if (isset($this->session->data['shipping_methods'])) {
				$data['shipping_methods'] = $this->session->data['shipping_methods'];
			} else {
				// Shipping methods
				$this->load->model('checkout/shipping_method');

				$data['shipping_methods'] = $this->model_checkout_shipping_method->getMethods($this->session->data['shipping_address']);

				// Store shipping methods in session
				$this->session->data['shipping_methods'] = $data['shipping_methods'];
			}
		} else {
			// Remove any shipping methods that does not meet checkout validation requirements
			unset($this->session->data['shipping_methods']);
		}

		if (isset($this->session->data['shipping_method'])) {
			$data['code'] = $this->session->data['shipping_method'];
		} else {
			$data['code'] = '';
		}

		return $this->load->view('checkout/shipping_method', $data);
	}

	public function getMethods(): void {
		$this->load->language('checkout/shipping_method');

		$json = [];

		$status = true;

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$status = false;
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			if (!$product['minimum']) {
				$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);

				break;
			}
		}

		// Validate if customer is logged in or customer session data is not set
		if (!isset($this->session->data['customer'])) {
			$status = false;
		}

		// Validate if payment address is set if required in settings
		if ($this->config->get('config_checkout_address') && !isset($this->session->data['payment_address'])) {
			$status = false;
		}

		// Validate if shipping not required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping() || !isset($this->session->data['shipping_address'])) {
			$status = false;
		}

		if ($status) {
			// Shipping Methods
			$this->load->model('checkout/shipping_method');

			$shipping_methods = $this->model_checkout_shipping_method->getMethods($this->session->data['shipping_address']);

			if ($shipping_methods) {
				// Store shipping methods in session
				$this->session->data['shipping_methods'] = $shipping_methods;

				$json['shipping_methods'] = $shipping_methods;
			} else {
				$json['error'] = sprintf($this->language->get('error_no_shipping'), $this->url->link('information/contact', 'language=' . $this->config->get('config_language')));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function save(): void {
		$this->load->language('checkout/shipping_method');

		$json = [];

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			if (!$product['minimum']) {
				$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);

				break;
			}
		}

		// Validate if customer is logged in or customer session data is not set
		if (!isset($this->session->data['customer'])) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate if payment address is set if required in settings
		if ($this->config->get('config_checkout_address') && !isset($this->session->data['payment_address'])) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate if shipping not required. If not the customer should not have reached this page.
		if (!$this->cart->hasShipping() || !isset($this->session->data['shipping_address'])) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
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
			$this->session->data['shipping_method'] = $this->request->post['shipping_method'];

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
