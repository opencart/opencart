<?php
namespace Opencart\Catalog\Controller\Checkout;
class PaymentMethod extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('checkout/checkout');

		$data['language'] = $this->config->get('config_language');

		if (isset($this->session->data['payment_methods'])) {
			$data['payment_methods'] = $this->session->data['payment_methods'];
		} else {
			$data['payment_methods'] = $this->getMethods();
		}

		if (isset($this->session->data['payment_method'])) {
			$data['code'] = $this->session->data['payment_method'];
		} else {
			$data['code'] = '';
		}

		return $this->load->view('checkout/payment_method', $data);
	}

	public function getMethods(): array {
		// Totals
		$totals = [];
		$taxes = $this->cart->getTaxes();
		$total = 0;

		$this->load->model('setting/extension');

		$sort_order = [];

		$results = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			if ($this->config->get('total_' . $result['code'] . '_status')) {
				$this->load->model('extension/' . $result['extension'] . '/total/' . $result['code']);

				// __call can not pass-by-reference so we get PHP to call it as an anonymous function.
				($this->{'model_extension_' . $result['extension'] . '_total_' . $result['code']}->getTotal)($totals, $taxes, $total);
			}
		}

		if (isset($this->session->data['payment_address'])) {
			$payment_address = $this->session->data['payment_address'];
		} else {
			$payment_address = [
				'address_id'   => 0,
				'firstname'    => '',
				'lastname'     => '',
				'company'      => '',
				'address_1'    => '',
				'address_2'    => '',
				'city'         => '',
				'postcode'     => '',
				'country_id'   => 0,
				'zone_id'      => 0,
				'custom_field' => []
			];
		}

		// Payment Methods
		$method_data = [];

		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getExtensionsByType('payment');

		$recurring = $this->cart->hasRecurringProducts();

		foreach ($results as $result) {
			if ($this->config->get('payment_' . $result['code'] . '_status')) {
				$this->load->model('extension/' . $result['extension'] . '/payment/' . $result['code']);

				$method = $this->{'model_extension_' . $result['extension'] . '_payment_' . $result['code']}->getMethod($payment_address, $total);

				if ($method) {
					if ($recurring) {
						if (property_exists($this->{'model_extension_' . $result['extension'] . '_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_' . $result['extension'] . '_payment_' . $result['code']}->recurringPayments()) {
							$method_data[$result['code']] = $method;
						}
					} else {
						$method_data[$result['code']] = $method;
					}
				}
			}
		}

		$sort_order = [];

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);

		// Store payment methods in session
		$this->session->data['payment_methods'] = $method_data;

		return $method_data;
	}

	public function methods(): void {
		$this->load->language('checkout/checkout');

		$json = [];

		if ($this->config->get('config_checkout_address') && !isset($this->session->data['payment_address'])) {
			$json['error'] = $this->language->get('error_payment_address');
		}

		$payment_methods = $this->getMethods();

		if (!$payment_methods) {
			$json['error'] = sprintf($this->language->get('error_no_payment'), $this->url->link('information/contact', 'language=' . $this->config->get('config_language')));
		}

		if (!$json) {
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
